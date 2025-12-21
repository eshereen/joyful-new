<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Collection;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Services\CartService;
use App\Models\Variant;
use App\Services\CountryCurrencyService;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\CartUpdateRequest;

class CartController extends Controller
{
    /** @var \App\Services\CartService */
    protected $cartService;
    protected $currencyService;

    public function __construct(CartService $cartService, CountryCurrencyService $currencyService)
    {
        $this->cartService = $cartService;
        $this->currencyService = $currencyService;
    }

    /**
     * Display the shopping cart page
     */
    public function index()
    {
        $title = 'Joyful|Cart';
        // Get current currency info
        $currencyInfo = $this->currencyService->getCurrentCurrencyInfo();

        // Just render the view - the Livewire component will handle all cart operations
        return view('cart', compact('currencyInfo'));
    }


    // ▶ Add item to cart (works for guests & logged-in users)
    public function add(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:10',

        ]);

        $this->cartService->addItem(
            $product,
            $request->quantity,

        );

        return response()->json([
            'success' => true,
            'cartCount' => $this->cartService->getCount(),
            'message' => 'Item added to cart'
        ]);
    }



    /**
     * Update cart item quantity
     */
    public function update(CartUpdateRequest $request, $rowId)
    {
        $this->cartService->updateQuantity($rowId, $request->validated()['quantity']);

        return response()->json([
            'success' => true,
            'subtotal' => $this->cartService->getSubtotal(),
            'total' => $this->cartService->getTotal(),
            'cartCount' => $this->cartService->getCount()
        ]);
    }

    /**
     * Remove item from cart
     */
    public function remove($rowId)
    {
        $this->cartService->removeItem($rowId);

        return response()->json([
            'success' => true,
            'subtotal' => $this->cartService->getSubtotal(),
            'total' => $this->cartService->getTotal(),
            'cartCount' => $this->cartService->getCount()
        ]);
    }

    /**
     * Clear the entire cart
     */
    public function clear()
    {
        $this->cartService->clearCart();

        return redirect()->route('cart.index')
            ->with('success', 'Your cart has been cleared');
    }

    /**
     * Get cart count (for AJAX requests)
     */
    public function count()
    {
        return response()->json([
            'count' => $this->cartService->getCount()
        ]);
    }
    // For simple products
public function quickAdd(Request $request, Product $product)
{
    $request->validate(['quantity' => 'required|integer|min:1|max:10']);

    $this->cartService->addItem($product, $request->quantity);

    return response()->json([
        'success' => true,
        'cartCount' => $this->cartService->getCount(),
        'message' => 'Product added to cart'
    ]);
}

// For products with variants
public function addWithVariant(Request $request, Product $product)
{
    $validated = $request->validate([
        'quantity' => 'required|integer|min:1|max:10',
        'variant_id' => 'required|exists:variants,id'
    ]);

    $variant = Variant::findOrFail($validated['variant_id']);

    $this->cartService->addItemWithVariant(
        $product,
        $variant,
        $validated['quantity']
    );

    return response()->json([
        'success' => true,
        'cartCount' => $this->cartService->getCount(),
        'message' => 'Product added to cart'
    ]);
}

    /**
     * Add collection to cart (adds all products in the collection)
     */
    public function addCollection(Request $request, Collection $collection)
    {
        $request->validate([
            'quantity' => 'sometimes|integer|min:1|max:10'
        ]);

        $quantity = $request->input('quantity', 1);

        try {
            if (method_exists($this->cartService, 'addCollection')) {
                $result = call_user_func([$this->cartService, 'addCollection'], $collection, $quantity);
            } else {
                $result = $this->addCollectionManually($collection, $quantity);
            }

            $message = "Collection '{$collection->name}' added to cart.";

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'cartCount' => $this->cartService->getCount(),
                    'message' => $message,
                    'cartItem' => $result['item'] ?? null,
                ]);
            }

            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 400);
            }

            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Fallback collection handling when the cart service
     * does not provide a dedicated addCollection method.
     */
    protected function addCollectionManually(Collection $collection, int $quantity = 1): array
    {
        $available = (int) ($collection->stock ?? 0);
        if ($available <= 0) {
            throw new \RuntimeException("Collection '{$collection->name}' is out of stock.");
        }

        if ($quantity > $available) {
            throw new \RuntimeException("Only {$available} set(s) available for '{$collection->name}'. Please reduce the quantity.");
        }

        $products = $collection->products()->where('active', true)->get();

        if ($products->isEmpty()) {
            throw new \RuntimeException('Collection has no active products');
        }

        $added = 0;
        foreach ($products as $product) {
            if ($product->variants()->exists()) {
                $variant = $product->variants()->where('stock', '>', 0)->first();
                if ($variant) {
                    $this->cartService->addItemWithVariant($product, $variant, $quantity);
                    $added++;
                }
            } else {
                $this->cartService->addItem($product, $quantity);
                $added++;
            }
        }

        return [
            'success' => true,
            'item' => [
                'type' => 'collection_fallback',
                'name' => $collection->name,
                'quantity' => $quantity,
                'price' => (float) ($collection->price ?? 0),
                'products_added' => $added,
            ],
        ];
    }
}
