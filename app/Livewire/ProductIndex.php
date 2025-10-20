<?php

namespace App\Livewire;

use Exception;
use App\Models\Product;
use App\Models\Variant;
use App\Services\CartService;
use App\Services\CountryCurrencyService;
use App\Services\BestSellerService;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class ProductIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $sortBy = 'newest';
    public $category = '';
    public $wishlistProductIds = [];
    public $currencyCode = 'EGP';
    public $currencySymbol = 'E£';
    public $isAutoDetected = false;
    public $useBestSellerLogic = false;

    // Flag to disable all Livewire reactivity on home page
    public $isStaticMode = false;

    // Cart modal properties
    public $showVariantModal = false;
    public $selectedProduct = null;
    public $selectedVariantId = null;
    public $selectedVariant = null;
    public $quantity = 1;

    // Removed: public $products = null; - conflicts with view variable

    #[On('wishlistUpdated')]
    public function loadWishlist()
    {
        if (Auth::check()) {
            // Cache wishlist for better performance
            $this->wishlistProductIds = cache()->remember(
                'user_wishlist_' . Auth::id(),
                600, // 10 minutes cache
                function () {
                    return Auth::user()->wishlist()->pluck('product_id')->toArray();
                }
            );
        } else {
            $this->wishlistProductIds = [];
        }
    }

        #[On('currencyChanged')]
    public function refreshCurrency()
    {
        // Skip on static mode (home page)
        if ($this->isStaticMode) {
            return;
        }

        Log::info('Currency change event received in ProductIndex');

        $this->loadCurrencyInfo();

        // Only force re-render on product pages, not home page to avoid loops
        if (!request()->routeIs('home')) {
            $this->dispatch('$refresh');
        }

        // Log the currency change
        Log::info('Currency changed in ProductIndex', [
            'new_currency' => $this->currencyCode,
            'new_symbol' => $this->currencySymbol
        ]);
    }

    #[On('currency-changed')]
    public function handleCurrencyChanged($currencyCode = null)
    {
        // Skip on static mode (home page)
        if ($this->isStaticMode) {
            return;
        }

        Log::info('ProductIndex: Received currency-changed event', ['currency_code' => $currencyCode]);
        $this->loadCurrencyInfo();
    }

    #[On('global-currency-changed')]
    public function handleGlobalCurrencyChanged($currencyCode = null)
    {
        // Skip on static mode (home page)
        if ($this->isStaticMode) {
            return;
        }
        Log::info('ProductIndex: Received global-currency-changed event', ['currency_code' => $currencyCode]);
        $this->loadCurrencyInfo();
    }

    // Alternative method to handle currency changes
    public function handleCurrencyChange($currencyCode = null)
    {
        Log::info('Manual currency change triggered', ['currency' => $currencyCode]);
        $this->loadCurrencyInfo();

        // If modal is open, re-convert variant prices
        if ($this->showVariantModal && $this->selectedProduct) {
            $this->convertVariantPrices();
        }
    }

    public $passedProducts = null;

    public function mount($products = null)
    {
        try {
            // Convert collection/array to collection if needed
            if (is_array($products)) {
                $this->passedProducts = collect($products);
            } else {
                $this->passedProducts = $products;
            }

            // For home page with passed products, enable static mode and skip all processing
            if (request()->routeIs('home') && $this->passedProducts) {
                $this->isStaticMode = true;
                // Set default currency info without processing
                $this->currencyCode = 'EGP';
                $this->currencySymbol = 'E£';
                return; // Skip all other processing to prevent loops
            }

            $this->loadWishlist();
            $this->loadCurrencyInfo();

            // Only check currency change on non-home pages to avoid reload loops
            if (!request()->routeIs('home')) {
                $this->checkCurrencyChange();
            }
        } catch (Exception $e) {
            // Handle wishlist loading error silently
        }
    }

    public function loadCurrencyInfo()
    {
        try {
            $currencyService = app(CountryCurrencyService::class);
            $currencyInfo = $currencyService->getCurrentCurrencyInfo();

            Log::info('ProductIndex: Loading currency info', [
                'currency_info' => $currencyInfo
            ]);

            $this->currencyCode = $currencyInfo['currency_code'];
            $this->currencySymbol = $currencyInfo['currency_symbol'];
            $this->isAutoDetected = $currencyInfo['is_auto_detected'];

            Log::info('ProductIndex: Currency info loaded', [
                'currency_code' => $this->currencyCode,
                'currency_symbol' => $this->currencySymbol,
                'is_auto_detected' => $this->isAutoDetected
            ]);

            // Convert product prices to current currency
            $this->convertProductPrices();
        } catch (Exception $e) {
            Log::error('ProductIndex: Error loading currency info', ['error' => $e->getMessage()]);
        }
    }

    protected function convertProductPrices($products = null)
    {
        if ($this->currencyCode === 'USD') {
            return; // No conversion needed
        }

        try {
            $currencyService = app(CountryCurrencyService::class);

            // Convert all product prices in the collection
            $productsToConvert = $products;
            if ($productsToConvert) {
                foreach ($productsToConvert as $product) {
                    // Convert variant prices
                    if ($product->variants && $product->variants->isNotEmpty()) {
                        $product->variants->transform(function ($variant) use ($currencyService) {
                            if ($variant->price) {
                                $variant->converted_price = $currencyService->convertFromUSD($variant->price, $this->currencyCode);
                            }
                            if ($variant->compare_price && $variant->compare_price > 0) {
                                $variant->converted_compare_price = $currencyService->convertFromUSD($variant->compare_price, $this->currencyCode);
                            }
                            return $variant;
                        });
                    }
                }
            }
        } catch (Exception $e) {
            // Handle conversion error silently
        }
    }

    protected function convertVariantPrices()
    {
        if ($this->currencyCode === 'USD' || !$this->selectedProduct) {
            Log::info('ProductIndex: Skipping conversion', [
                'currency_code' => $this->currencyCode,
                'has_product' => !$this->selectedProduct
            ]);
            return; // No conversion needed
        }

        try {
            $currencyService = app(CountryCurrencyService::class);

            Log::info('ProductIndex: Starting variant price conversion', [
                'currency_code' => $this->currencyCode,
                'currency_symbol' => $this->currencySymbol,
                'product_price' => $this->selectedProduct->price
            ]);

            // Note: Product price conversion removed - we only convert variant prices now

            // Convert variant prices with bulk processing
            if ($this->selectedProduct->variants) {
                $this->selectedProduct->variants->transform(function ($variant) use ($currencyService) {
                    if ($variant->price) {
                        $originalPrice = $variant->price;
                        $variant->converted_price = $currencyService->convertFromUSD($originalPrice, $this->currencyCode);
                        Log::info('ProductIndex: Variant price converted', [
                            'variant_id' => $variant->id,
                            'original' => $originalPrice,
                            'converted' => $variant->converted_price
                        ]);
                    }
                    return $variant;
                });
            }
        } catch (Exception $e) {
            Log::error('ProductIndex: Conversion error', ['error' => $e->getMessage()]);
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function toggleWishlist($productId)
    {
        if (!Auth::check()) {
            $this->dispatch('showNotification', [
                'message' => 'Please login to add items to your wishlist.',
                'type' => 'error'
            ]);
            return;
        }

        try {
            $user = Auth::user();
            $existingWishlist = $user->wishlist()->where('product_id', $productId)->first();
            $message = '';

            if ($existingWishlist) {
                // Remove from wishlist
                $existingWishlist->delete();
                $message = 'Product removed from wishlist!';
                // Remove from local array
                $this->wishlistProductIds = array_filter($this->wishlistProductIds, function($id) use ($productId) {
                    return $id != $productId;
                });
            } else {
                // Add to wishlist
                $user->wishlist()->create([
                    'product_id' => $productId
                ]);
                $message = 'Product added to wishlist!';
                // Add to local array
                $this->wishlistProductIds[] = $productId;
            }

            // Clear wishlist cache
            cache()->forget('user_wishlist_' . Auth::id());

            // Emit events
            $this->dispatch('wishlistUpdated');
            $this->dispatch('showNotification', [
                'message' => $message,
                'type' => 'success'
            ]);

        } catch (Exception $e) {
            // Log::error('Error in toggleWishlist: ' . $e->getMessage());
            $this->dispatch('showNotification', [
                'message' => 'An error occurred while updating your wishlist: ' . $e->getMessage(),
                'type' => 'error'
            ]);
        }
    }

    public function openVariantModal($productId)
    {
        // Ensure we have the latest currency info
        $this->loadCurrencyInfo();

        // Get product with variants - no caching to ensure fresh currency conversion
        $this->selectedProduct = Product::with([
                'variants' => function ($q) {
                    $q->select('id', 'product_id', 'price', 'compare_price', 'stock', 'size', 'wick_type');
                }
            ])
            ->select('id', 'name', 'slug')
            ->find($productId);

        $this->selectedVariantId = null;
        $this->selectedVariant = null;
        $this->quantity = 1;
        $this->showVariantModal = true;

        // Convert variant prices to current currency
        $this->convertVariantPrices();
    }

    public function selectVariant($variantId)
    {
        Log::info('ProductIndex: selectVariant called', [
            'variant_id' => $variantId,
            'currency_code' => $this->currencyCode,
            'currency_symbol' => $this->currencySymbol
        ]);

        $this->selectedVariantId = $variantId;
        $this->selectedVariant = $this->selectedProduct && $this->selectedProduct->variants
            ? $this->selectedProduct->variants->firstWhere('id', (int) $variantId)
            : null;

        Log::info('ProductIndex: Variant found', [
            'variant_id' => $variantId,
            'variant_price' => $this->selectedVariant ? $this->selectedVariant->price : 'NULL',
            'variant_converted_price' => $this->selectedVariant ? ($this->selectedVariant->converted_price ?? 'NULL') : 'NULL',
            'variant_stock' => $this->selectedVariant ? $this->selectedVariant->stock : 'NULL',
            'quantity' => $this->quantity
        ]);


        // Ensure the selected variant has converted prices
        if ($this->selectedVariant && $this->currencyCode !== 'USD') {
            $currencyService = app(CountryCurrencyService::class);

            // If variant has its own price, convert it
            if ($this->selectedVariant->price) {
                $originalPrice = $this->selectedVariant->price;
                $this->selectedVariant->converted_price = $currencyService->convertFromUSD($originalPrice, $this->currencyCode);

                Log::info('ProductIndex: Variant price converted in selectVariant', [
                    'variant_id' => $variantId,
                    'original_price' => $originalPrice,
                    'converted_price' => $this->selectedVariant->converted_price,
                    'currency_code' => $this->currencyCode
                ]);
            } else {
                // If variant has no price, use product price
                if ($this->selectedProduct->price) {
                    $originalPrice = $this->selectedProduct->price;
                    $this->selectedVariant->converted_price = $currencyService->convertFromUSD($originalPrice, $this->currencyCode);

                    Log::info('ProductIndex: Using product price for variant', [
                        'variant_id' => $variantId,
                        'product_price' => $originalPrice,
                        'converted_price' => $this->selectedVariant->converted_price,
                        'currency_code' => $this->currencyCode
                    ]);
                }
            }
        }

        // Reset quantity if it exceeds stock or if stock is invalid
        if ($this->selectedVariant) {
            $stock = $this->selectedVariant->stock;

            // If stock is negative or zero, set quantity to 0 (out of stock)
            if ($stock <= 0) {
                $this->quantity = 0;
                Log::info('ProductIndex: Variant out of stock', [
                    'variant_id' => $variantId,
                    'stock' => $stock,
                    'quantity_set_to' => 0
                ]);
            }
            // If quantity exceeds stock, reset to stock level
            elseif ($this->quantity > $stock) {
                $this->quantity = $stock;
                Log::info('ProductIndex: Quantity adjusted to stock level', [
                    'variant_id' => $variantId,
                    'stock' => $stock,
                    'quantity_set_to' => $stock
                ]);
            }
        }
    }
    public function incrementQty()
    {
        $maxQty = $this->selectedVariant ? $this->selectedVariant->stock : 10;
        if ($this->quantity < $maxQty && $this->quantity < 10) {
            $this->quantity++;
        }
    }

    public function decrementQty()
    {
        if ($this->quantity > 1) {
            $this->quantity--;
        }
    }

    public function addToCart()
    {
        if (!$this->selectedVariant) {
            $this->dispatch('showNotification', [
                'message' => 'Please select a variant before adding to cart.',
                'type' => 'error'
            ]);
            return;
        }

        try {
            $cartService = app(CartService::class);
            $cartService->addItemWithVariant($this->selectedProduct, $this->selectedVariant, $this->quantity);

            // Close modal and reset
            $this->showVariantModal = false;
            $this->selectedProduct = null;
            $this->selectedVariantId = null;
            $this->selectedVariant = null;
            $this->quantity = 1;

            // Emit events
            $this->dispatch('cartUpdated');
            $this->dispatch('showNotification', [
                'message' => 'Product added to cart successfully!',
                'type' => 'success'
            ]);

        } catch (Exception $e) {
            $this->dispatch('showNotification', [
                'message' => 'Error adding product to cart: ' . $e->getMessage(),
                'type' => 'error'
            ]);
        }
    }



    public function addSimpleProductToCart($productId, $quantity = 1)
    {
        Log::info('addSimpleProductToCart called', [
            'product_id' => $productId,
            'quantity' => $quantity
        ]);

        try {
            $cartService = app(CartService::class);
            $product = Product::find($productId);

            if (!$product) {
                Log::warning('Product not found for cart addition', ['product_id' => $productId]);
                $this->dispatch('showNotification', [
                    'message' => 'Product not found.',
                    'type' => 'error'
                ]);
                return;
            }

            // Check stock before adding to cart
            if ($product->quantity < $quantity) {
                if ($product->quantity <= 0) {
                    $this->dispatch('showNotification', [
                        'message' => 'This product is currently out of stock.',
                        'type' => 'error'
                    ]);
                } else {
                    $this->dispatch('showNotification', [
                        'message' => 'Only ' . $product->quantity . ' items available in stock.',
                        'type' => 'error'
                    ]);
                }
                return;
            }

            $cartService->addItem($product, $quantity);

            Log::info('Simple product added to cart successfully', [
                'product_id' => $productId,
                'product_name' => $product->name,
                'quantity' => $quantity
            ]);

            $this->dispatch('cartUpdated');
            $this->dispatch('showNotification', [
                'message' => 'Product added to cart successfully!',
                'type' => 'success'
            ]);

        } catch (Exception $e) {
            Log::error('Error adding simple product to cart', [
                'product_id' => $productId,
                'error' => $e->getMessage()
            ]);
            $this->dispatch('showNotification', [
                'message' => 'Error adding product to cart: ' . $e->getMessage(),
                'type' => 'error'
            ]);
        }
    }

    public function render()
    {
        try {
            // Simple fast path for home page with passed products - NO processing at all
            if (request()->routeIs('home') && $this->passedProducts && $this->passedProducts->isNotEmpty()) {
                return view('livewire.product-index', [
                    'products' => $this->passedProducts
                ]);
            }

            // Use passed products if available (from homepage sections)
            if ($this->passedProducts && $this->passedProducts->isNotEmpty()) {
                $productsToDisplay = $this->passedProducts;
                // Convert currency for passed products
                $this->convertProductPricesOptimized($productsToDisplay);
            } else {
                // Use cached query logic for product index pages
                $cacheKey = $this->buildCacheKey();
                $cacheTime = request()->routeIs('home') ? 60 : 180;
                $productsToDisplay = cache()->remember($cacheKey, $cacheTime, function () {
                // Optimized eager loading with specific selects
                $with = [
                    'category:id,name,slug',
                    'media' => function ($query) {
                        $query->select('id', 'model_id', 'model_type', 'collection_name', 'file_name', 'disk')
                              ->whereIn('collection_name', ['main_image', 'product_images'])
                              ->whereNotNull('disk')
                              ->orderBy('collection_name', 'asc')
                              ->orderBy('id', 'asc');
                    }
                ];

                // Always load variants for product index pages to avoid N+1 queries
                if (!request()->routeIs('home'))

                // Always load variants to prevent N+1 queries
                $with[] = 'variants:id,product_id,price,compare_price,stock';

                $query = Product::with($with)
                    ->select('id', 'name', 'slug', 'description', 'category_id', 'active', 'featured', 'created_at')
                    ->where('products.active', true);

                // Optimized search with full-text search if available
                if ($this->search) {
                    $query->where(function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%')
                          ->orWhere('description', 'like', '%' . $this->search . '%');
                    });
                }

                if ($this->category) {
                    $query->where('category_id', $this->category);
                }

                // Apply best seller logic if enabled
                if ($this->useBestSellerLogic && $this->sortBy === 'newest') {
                    $bestSellerService = app(BestSellerService::class);
                    $perPage = request()->routeIs('home') ? 8 : 12;

                    // Get products with best seller priority
                    $products = $bestSellerService->getProductsWithBestSellerPriority(
                        $query,
                        $perPage,
                        $this->category
                    );

                    // Return simple collection for home page
                    if (request()->routeIs('home')) {
                        return $products;
                    }

                    // Convert to paginated format for other pages
                    return new \Illuminate\Pagination\LengthAwarePaginator(
                        $products,
                        $products->count(),
                        $perPage,
                        1,
                        ['path' => request()->url(), 'pageName' => 'page']
                    );
                }

                // Optimized sorting
                switch ($this->sortBy) {
                    case 'price_low':
                        $query->orderBy('price', 'asc')->orderBy('created_at', 'desc');
                        break;
                    case 'price_high':
                        $query->orderBy('price', 'desc')->orderBy('created_at', 'desc');
                        break;
                    case 'newest':
                    default:
                        $query->orderBy('featured', 'desc')->orderBy('created_at', 'desc');
                        break;
                }

                $perPage = request()->routeIs('home') ? 8 : 12;

                // Return simple collection for home page (no pagination)
                if (request()->routeIs('home')) {
                    return $query->limit($perPage)->get();
                }

                // Return paginated results for product index page
                return $query->paginate($perPage);
            });
            }

            // Products fetched successfully from cache

            // Don't convert paginated results to collections - this breaks pagination
            if (!$productsToDisplay) {
                // Only fallback if completely null - run query directly
                $query = Product::with([
                    'category:id,name,slug',
                    'media' => function ($query) {
                        $query->select('id', 'model_id', 'model_type', 'collection_name', 'file_name', 'disk')
                              ->whereIn('collection_name', ['main_image', 'product_images'])
                              ->whereNotNull('disk')
                              ->orderBy('collection_name', 'asc')
                              ->orderBy('id', 'asc');
                    },
                    'variants:id,product_id,price,compare_price,stock'
                ])
                ->select('id', 'name', 'slug', 'description','category_id','active', 'featured', 'created_at')
                ->where('products.active', true)
                ->orderBy('created_at', 'desc');

                // Return simple collection for home page (no pagination)
                if (request()->routeIs('home')) {
                    $productsToDisplay = $query->limit(8)->get();
                } else {
                    $productsToDisplay = $query->paginate(12);
                }
            }

            // Convert product prices to current currency (optimized) - only if not already converted
            if (!($this->passedProducts && $this->passedProducts->isNotEmpty())) {
                $this->convertProductPricesOptimized($productsToDisplay);
            }

            // Pre-compute variants data to avoid N+1 queries
            $this->precomputeVariantsData($productsToDisplay);

            return view('livewire.product-index', [
                'products' => $productsToDisplay
            ]);
        } catch (\Exception $e) {
            // Log error and return fallback products query
            Log::error('ProductIndex render error: ' . $e->getMessage());

            // Return a basic query as fallback
            $query = Product::with([
                'category:id,name,slug',
                'media' => function ($query) {
                    $query->select('id', 'model_id', 'model_type', 'collection_name', 'file_name', 'disk')
                          ->whereIn('collection_name', ['main_image', 'product_images'])
                          ->whereNotNull('disk');
                },
                'variants:id,product_id,color,size,price,stock'
            ])
            ->select('id', 'name', 'slug', 'description', 'category_id','active', 'featured', 'created_at')
            ->where('products.active', true)
            ->orderBy('created_at', 'desc');

            // Return simple collection for home page (no pagination)
            if (request()->routeIs('home')) {
                $fallbackProducts = $query->limit(8)->get();
            } else {
                $fallbackProducts = $query->paginate(12);
            }

            return view('livewire.product-index', [
                'products' => $fallbackProducts
            ]);
        }
    }

        /**
     * Build a unique cache key for the current query
     */
    protected function buildCacheKey()
    {
        $params = [
            'search' => $this->search,
            'sort' => $this->sortBy,
            'category' => $this->category,
            'page' => request()->get('page', 1),
            'per_page' => request()->routeIs('home') ? 8 : 12,
            'route' => request()->route()->getName(),
            'currency' => $this->currencyCode
        ];

        $cacheKey = 'products_index_' . md5(serialize($params));

        // Track cache keys for cleanup
        $keys = Cache::get('product_index_cache_keys', []);
        if (!in_array($cacheKey, $keys)) {
            $keys[] = $cacheKey;
            Cache::put('product_index_cache_keys', $keys, 3600);
        }

        return $cacheKey;
    }

        /**
     * Optimized price conversion with bulk processing
     */
    protected function convertProductPricesOptimized($products)
    {
        try {
            $currencyService = app(CountryCurrencyService::class);

            // Skip conversion if disabled or if already in default currency
            if (!$currencyService->isConversionEnabled() || $this->currencyCode === $currencyService->getDefaultCurrency()) {
                return; // No conversion needed
            }

            // Get the collection to transform
            if ($products instanceof \Illuminate\Pagination\LengthAwarePaginator || $products instanceof \Illuminate\Pagination\Paginator) {
                $collection = $products->getCollection();
            } else {
                $collection = $products;
            }

            // Transform the collection
            $collection->transform(function ($product) use ($currencyService) {
                if ($product->price) {
                    $product->converted_price = $currencyService->convertFromUSD($product->price, $this->currencyCode);
                }
                if ($product->compare_price && $product->compare_price > 0) {
                    $product->converted_compare_price = $currencyService->convertFromUSD($product->compare_price, $this->currencyCode);
                }
                return $product;
            });

            // If it was a paginator, set the transformed collection back
            if ($products instanceof \Illuminate\Pagination\LengthAwarePaginator || $products instanceof \Illuminate\Pagination\Paginator) {
                $products->setCollection($collection);
            }
        } catch (Exception $e) {
            // Handle conversion error silently
        }
    }

    /**
     * Pre-compute variants data to avoid N+1 queries
     */
    protected function precomputeVariantsData($products)
    {
        // Get the collection to transform
        if ($products instanceof \Illuminate\Pagination\LengthAwarePaginator || $products instanceof \Illuminate\Pagination\Paginator) {
            $collection = $products->getCollection();
        } else {
            $collection = $products;
        }

        // Transform the collection
        $collection->transform(function ($product) {
            // Add computed properties to avoid individual queries
            $product->has_variants = $product->variants && $product->variants->isNotEmpty();
            $product->variants_count = $product->variants ? $product->variants->count() : 0;

            // Pre-compute unique colors if variants exist
            if ($product->has_variants) {
                $product->unique_colors = $product->variants->unique('color')->pluck('color');
                $product->first_variant = $product->variants->first();
            }

            return $product;
        });

        // If it was a paginator, set the transformed collection back
        if ($products instanceof \Illuminate\Pagination\LengthAwarePaginator || $products instanceof \Illuminate\Pagination\Paginator) {
            $products->setCollection($collection);
        }
    }

    protected function checkCurrencyChange()
    {
        try {
            // Use the already cached currency info from the service
            $currencyService = app(CountryCurrencyService::class);
            $currentInfo = $currencyService->getCurrentCurrencyInfo();

            if ($this->currencyCode !== $currentInfo['currency_code']) {
                Log::info('Currency change detected in render', [
                    'old_currency' => $this->currencyCode,
                    'new_currency' => $currentInfo['currency_code']
                ]);

                $this->currencyCode = $currentInfo['currency_code'];
                $this->currencySymbol = $currentInfo['currency_symbol'];
                $this->isAutoDetected = $currentInfo['is_auto_detected'];

                // Clear product cache when currency changes
                $this->clearProductCache();
            }
        } catch (Exception $e) {
            // Handle error silently
        }
    }

    /**
     * Clear product cache when currency changes
     */
    protected function clearProductCache()
    {
        // Clear all product index caches
        $keys = Cache::get('product_index_cache_keys', []);
        foreach ($keys as $key) {
            Cache::forget($key);
        }
        Cache::forget('product_index_cache_keys');

        // Clear currency cache to force refresh
        Cache::forget("currency_info_{$this->currencyCode}");
    }

    /**
     * Get the hex color code for a color name from config
     */
    public function getColorCode($colorName)
    {
        $colors = config('colors');
        return $colors[$colorName] ?? '#808080'; // Default to gray if color not found
    }

    /**
     * Safely get media URL with error handling
     */
    protected function getSafeMediaUrl($product, $collectionName = 'main_image', $conversionName = '')
    {
        try {
            if (!$product->media || $product->media->isEmpty()) {
                return null;
            }

            $media = $product->media->where('collection_name', $collectionName)->first();
            if (!$media || !$media->disk) {
                return null;
            }

            return $product->getFirstMediaUrl($collectionName, $conversionName);
        } catch (Exception $e) {
            Log::warning('Failed to get media URL', [
                'product_id' => $product->id,
                'collection' => $collectionName,
                'conversion' => $conversionName,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Get contrasting text color (black or white) for a given background color
     */
    public function getContrastColor($hexColor)
    {
        // Remove # if present
        $hex = ltrim($hexColor, '#');

        // Convert to RGB
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));

        // Calculate luminance
        $luminance = (0.299 * $r + 0.587 * $g + 0.114 * $b) / 255;

        // Return black for light backgrounds, white for dark backgrounds
        return $luminance > 0.5 ? '#000000' : '#FFFFFF';
    }

    /**
     * Check if a product is a best seller
     */
    public function isBestSeller($productId)
    {
        if (!$this->useBestSellerLogic) {
            return false;
        }

        $bestSellerService = app(BestSellerService::class);
        return $bestSellerService->isBestSeller($productId, $this->category);
    }
}
