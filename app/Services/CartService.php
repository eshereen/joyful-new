<?php

namespace App\Services;

use App\Contracts\CartServiceContract;
use Exception;
use App\Models\Product;
use App\Models\Collection;
// Note: We avoid hard-coding a specific Variant class here because the
// project uses a custom variants schema (size, wick_type) instead of color.
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class CartService implements CartServiceContract
{
    protected $cartKey = 'shopping_cart';

    public function __construct()
    {
        Log::info('CartService initialized with session-based storage');
    }

    protected function getCartKey()
    {
        return $this->cartKey;
    }

    public function getCart()
    {
        $cart = Session::get($this->getCartKey(), collect());
        Log::info('Getting cart from session', ['count' => $cart->count()]);

        // Ensure we return a collection of arrays, not objects
        $cartArray = $cart->map(function($item) {
            return (array) $item;
        });

        return $cartArray->sortBy('name');
    }

    public function addItem(Product $product, $quantity = 1, $size = null, $color = null)
    {
        try {
            $cart = $this->getCart();

            $options = [
                'image' => $product->getFirstMediaUrl('main_image'),
                'slug' => $product->slug,
                'item_type' => 'product',
            ];

            // Check if product already exists in cart
            $existingItem = $cart->firstWhere('id', $product->id);

            if ($existingItem) {
                // Update existing item quantity
                $existingItem['quantity'] += $quantity;
                $cart = $cart->map(function($item) use ($existingItem) {
                    if ($item['id'] === $existingItem['id']) {
                        return $existingItem;
                    }
                    return $item;
                });
            } else {
                // Add new item
                $newItem = [
                    'id' => $product->id,
                    'rowId' => uniqid('item_'),
                    'name' => $product->name,
                    'price' => $product->price,
                    'quantity' => $quantity,
                    'attributes' => $options,
                    'associatedModel' => $product
                ];
                $cart->push($newItem);
            }

            // Save cart to session
            Session::put($this->getCartKey(), $cart);

            Log::info('Product added to cart', [
                'product_id' => $product->id,
                'quantity' => $quantity,
                'cart_count' => $this->getCount(),
                'session_id' => session()->getId()
            ]);

            return $cart->last();
        } catch (Exception $e) {
            Log::error('Error adding product to cart', [
                'product_id' => $product->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function updateQuantity($rowId, $quantity)
    {
        try {


            $cart = $this->getCart();
            Log::info('Cart before quantity update', ['count' => $cart->count()]);

            // Find the item to be updated for logging
            $itemToUpdate = $cart->firstWhere('rowId', $rowId);
            if (!$itemToUpdate) {
                Log::warning('Item not found for quantity update', ['rowId' => $rowId]);
                return false;
            }

            if (($itemToUpdate['attributes']['item_type'] ?? null) === 'collection') {
                $collectionId = $itemToUpdate['attributes']['collection_id'] ?? null;
                if (!$collectionId) {
                    throw new Exception('Collection reference missing from cart item.');
                }

                $collection = Collection::find($collectionId);
                if (!$collection) {
                    throw new Exception('Collection no longer exists.');
                }

                if ($collection->stock <= 0) {
                    throw new Exception("Collection '{$collection->name}' is out of stock.");
                }

                if ($quantity > $collection->stock) {
                    throw new Exception("Only {$collection->stock} set(s) of '{$collection->name}' are available.");
                }
            }

            Log::info('Item found for quantity update', [
                'rowId' => $rowId,
                'item_name' => $itemToUpdate['name'],
                'old_quantity' => $itemToUpdate['quantity'],
                'new_quantity' => $quantity
            ]);

            // Create a new collection with the updated item
            $updatedCart = $cart->map(function($item) use ($rowId, $quantity) {
                if ($item['rowId'] === $rowId) {
                    $item['quantity'] = $quantity;
                }
                return $item;
            });

            // Update the session
            Session::put($this->getCartKey(), $updatedCart);

            Log::info('Quantity updated successfully', [
                'rowId' => $rowId,
                'newQuantity' => $quantity,
                'cart_count_after' => $updatedCart->count()
            ]);

            return true;
        } catch (Exception $e) {
            Log::error('Error updating quantity', [
                'rowId' => $rowId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return false;
        }
    }

    public function removeItem($rowId)
    {
        try {
            Log::info('CartService removeItem called', ['rowId' => $rowId]);

            $cart = $this->getCart();
            Log::info('Cart before removal', ['count' => $cart->count()]);

            // Find the item to be removed for logging
            $itemToRemove = $cart->firstWhere('rowId', $rowId);
            if ($itemToRemove) {
                Log::info('Item found for removal', [
                    'rowId' => $rowId,
                    'item_name' => $itemToRemove['name'],
                    'item_price' => $itemToRemove['price']
                ]);
            } else {
                Log::warning('Item not found in cart', ['rowId' => $rowId]);
                return false;
            }

            $cart = $cart->reject(function($item) use ($rowId) {
                return $item['rowId'] === $rowId;
            });

            Log::info('Cart after removal', ['count' => $cart->count()]);

            Session::put($this->getCartKey(), $cart);

            Log::info('Item removed successfully', ['rowId' => $rowId]);

            return true;
        } catch (Exception $e) {
            Log::error('Error removing item from cart', [
                'rowId' => $rowId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return false;
        }
    }

    public function clearCart()
    {
        Session::forget($this->getCartKey());
        Log::info('Cart cleared');
    }

    public function getSubtotal()
    {
        $cart = $this->getCart();
        return $cart->sum(function($item) {
            return $item['price'] * $item['quantity'];
        });
    }

    public function getTotal()
    {
        return $this->getSubtotal() + $this->getShippingCost() + $this->getTaxAmount();
    }

    public function getCount()
    {
        $cart = $this->getCart();
        return $cart->sum('quantity');
    }

    public function getShippingCost()
    {
        return 0; // Free shipping
    }

    public function getTaxAmount()
    {
        return 0; // No tax
    }

    public function isEmpty()
    {
        return $this->getCart()->isEmpty();
    }

    public function addItemWithVariant(Product $product, $variant, $quantity = 1)
    {
        try {
            $cart = $this->getCart();

            $options = [
                'image' => $product->getFirstMediaUrl('main_image'),
                'size' => $variant->size ?? null,
                // Support either wick-based variants or color-based ones
                'wick_type' => $variant->wick_type ?? null,

                'slug' => $product->slug,
                'variant_id' => $variant->id,
                'item_type' => 'product',
            ];

            // Use variant price if available, otherwise product price
            $price = isset($variant->price) && is_numeric($variant->price)
                ? $variant->price
                : $product->price;

            // Check if product with same variant already exists in cart
            $existingItem = $cart->firstWhere('id', $product->id . '-' . $variant->id);

            if ($existingItem) {
                // Update existing item quantity
                $existingItem['quantity'] += $quantity;
                $cart = $cart->map(function($item) use ($existingItem) {
                    if ($item['id'] === $existingItem['id']) {
                        return $existingItem;
                    }
                    return $item;
                });

                Log::info('Product with variant quantity updated in cart', [
                    'product_id' => $product->id,
                    'variant_id' => $variant->id,
                    'old_quantity' => $existingItem['quantity'] - $quantity,
                    'new_quantity' => $existingItem['quantity'],
                    'cart_count' => $this->getCount()
                ]);
            } else {
                // Add new item
                $newItem = [
                    'id' => $product->id . '-' . $variant->id,
                    'rowId' => uniqid('variant_'),
                    'name' => $product->name,
                    'price' => $price,
                    'quantity' => $quantity,
                    'attributes' => $options,
                    'associatedModel' => $product
                ];

                $cart->push($newItem);

                Log::info('Product with variant added to cart', [
                    'product_id' => $product->id,
                    'variant_id' => $variant->id,
                    'quantity' => $quantity,
                    'cart_count' => $this->getCount()
                ]);
            }

            Session::put($this->getCartKey(), $cart);

            return $existingItem ?? $newItem;
        } catch (Exception $e) {
            Log::error('Error adding product with variant to cart', [
                'product_id' => $product->id,
                'variant_id' => $variant->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * Add a collection as a single bundled item to the cart.
     */
    public function addCollection(Collection $collection, $quantity = 1)
    {
        try {
            $collection = Collection::find($collection->id) ?? $collection;

            $price = (float) ($collection->price ?? 0);

            if ($price <= 0) {
                throw new Exception('Collection price is not set.');
            }

            if ($quantity < 1) {
                throw new Exception('Quantity must be at least 1.');
            }

            $stock = (int) ($collection->stock ?? 0);
            if ($stock <= 0) {
                throw new Exception("Collection '{$collection->name}' is out of stock.");
            }

            $cart = $this->getCart();
            $itemId = 'collection-' . $collection->id;
            $options = [
                'image' => $collection->getFirstMediaUrl('main_image'),
                'slug' => $collection->slug,
                'item_type' => 'collection',
                'collection_id' => $collection->id,
            ];

            $existingItem = $cart->firstWhere('id', $itemId);
            $existingQuantity = $existingItem['quantity'] ?? 0;
            $newTotalQuantity = $existingQuantity + $quantity;

            if ($newTotalQuantity > $stock) {
                $remaining = max($stock - $existingQuantity, 0);
                $message = $remaining > 0
                    ? "Only {$remaining} more set(s) of '{$collection->name}' are available. Please reduce the quantity."
                    : "All available stock for '{$collection->name}' is already in your cart. Please adjust the quantity before adding more.";
                throw new Exception($message);
            }

            if ($existingItem) {
                $updatedItem = $existingItem;
                $updatedItem['quantity'] = $newTotalQuantity;

                $cart = $cart->map(function ($item) use ($itemId, $updatedItem) {
                    return $item['id'] === $itemId ? $updatedItem : $item;
                });

                $cartItem = $updatedItem;
            } else {
                $cartItem = [
                    'id' => $itemId,
                    'rowId' => uniqid('collection_'),
                    'name' => $collection->name,
                    'price' => $price,
                    'quantity' => $quantity,
                    'attributes' => $options,
                    'associatedModel' => $collection,
                ];

                $cart->push($cartItem);
            }

            Session::put($this->getCartKey(), $cart);

            Log::info('Collection bundle added to cart', [
                'collection_id' => $collection->id,
                'collection_name' => $collection->name,
                'quantity_added' => $quantity,
                'cart_count' => $this->getCount(),
            ]);

            return [
                'success' => true,
                'item' => $cartItem,
            ];
        } catch (Exception $e) {
            Log::error('Error adding collection to cart', [
                'collection_id' => $collection->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }
}
