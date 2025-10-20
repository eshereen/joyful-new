<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\CountryCurrencyService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected $currencyService;

    public function __construct(CountryCurrencyService $currencyService)
    {
        $this->currencyService = $currencyService;
    }

    /**
     * Display product listing
     */
    public function index()
    {
        $title = 'Products | Joyful';

        // Get current currency info once
        $currencyInfo = $this->currencyService->getCurrentCurrencyInfo();

        // Build cache key for currency-aware caching
        $cacheKey = 'products_index_' . md5($currencyInfo['currency_code'] . '_' . request()->get('page', 1));

        // Cache the entire result for better performance
        $result = cache()->remember($cacheKey, 300, function () use ($currencyInfo) {
            // Optimized query with specific selects and eager loading
            $products = Product::with([
                'category:id,name,slug',

                'media' => function ($query) {
                    $query->select('id', 'model_id', 'model_type', 'collection_name', 'file_name', 'disk')
                        ->whereIn('collection_name', ['main_image', 'product_images'])
                        ->whereNotNull('disk')
                        ->orderBy('collection_name', 'asc')
                        ->orderBy('id', 'asc');
                },
                'variants:id,product_id,compare_price,price,stock'
            ])
                ->select('id', 'name', 'slug', 'description', 'category_id', 'active', 'featured', 'created_at')
                ->where('products.active', true)
                ->orderBy('created_at', 'desc')
                ->paginate(12);

            // Convert product prices to current currency (batch processing)
            $products->getCollection()->transform(function ($product) use ($currencyInfo) {
                // Convert variant prices
                if ($product->variants && $product->variants->isNotEmpty()) {
                    $product->variants->transform(function ($variant) use ($currencyInfo) {
                        if ($variant->price) {
                            $variant->converted_price = $this->currencyService->convertFromUSD($variant->price, $currencyInfo['currency_code']);
                        }
                        if ($variant->compare_price && $variant->compare_price > 0) {
                            $variant->converted_compare_price = $this->currencyService->convertFromUSD($variant->compare_price, $currencyInfo['currency_code']);
                        }
                        return $variant;
                    });
                }
                return $product;
            });

            return $products;
        });

        return view('products.index', compact('currencyInfo', 'title'));
    }

    /**
     * Show single product page
     */
    public function show(Product $product)
    {
        $title = $product->name . ' | Joyful';

        // Get current currency info
        $currencyInfo = $this->currencyService->getCurrentCurrencyInfo();

        // Build cache key for currency-aware caching
        $cacheKey = 'product_show_' . $product->id . '_' . md5($currencyInfo['currency_code']);

        // Cache the product with eager loading for better performance
        $product = cache()->remember($cacheKey, 600, function () use ($product, $currencyInfo) {
            // Eager load relationships to avoid N+1 queries
            $product->load([
                'category:id,name,slug',
                'variants:id,product_id,compare_price,price,stock',
                'media' => function ($query) {
                    $query->select('id', 'model_id', 'model_type', 'collection_name', 'file_name', 'disk')
                        ->whereIn('collection_name', ['main_image', 'product_images'])
                        ->whereNotNull('disk')
                        ->orderBy('collection_name', 'asc')
                        ->orderBy('id', 'asc');
                }
            ]);

            // Convert variant prices to current currency
            if ($product->variants && $product->variants->isNotEmpty()) {
                $product->variants->transform(function ($variant) use ($currencyInfo) {
                    if ($variant->price) {
                        $variant->converted_price = $this->currencyService->convertFromUSD($variant->price, $currencyInfo['currency_code']);
                    }
                    if ($variant->compare_price && $variant->compare_price > 0) {
                        $variant->converted_compare_price = $this->currencyService->convertFromUSD($variant->compare_price, $currencyInfo['currency_code']);
                    }
                    return $variant;
                });
            }

            return $product;
        });

        return view('products.show', compact('product', 'currencyInfo', 'title'));
    }

    /**
     * Search products
     */
    public function search(Request $request)
    {
        $query = $request->get('q', '');
        $title = 'Search Results for "' . $query . '" | Joyful';

        // Get current currency info
        $currencyInfo = $this->currencyService->getCurrentCurrencyInfo();

        // If no search query, redirect to products index
        if (empty($query)) {
            return redirect()->route('products.index');
        }

        // Search products
        $products = Product::with([
            'category:id,name,slug',
            'media' => function ($query) {
                $query->select('id', 'model_id', 'model_type', 'collection_name', 'file_name', 'disk')
                    ->whereIn('collection_name', ['main_image', 'product_images'])
                    ->whereNotNull('disk')
                    ->orderBy('collection_name', 'asc')
                    ->orderBy('id', 'asc');
            },
            'variants:id,product_id,color,size,price,stock'
        ])
            ->select('id', 'name', 'slug', 'description', 'category_id', 'active', 'featured', 'created_at')
            ->where('active', true)
            ->where(function ($queryBuilder) use ($query) {
                $queryBuilder->where('name', 'like', '%' . $query . '%')
                    ->orWhere('description', 'like', '%' . $query . '%')
                    ->orWhereHas('category', function ($q) use ($query) {
                        $q->where('name', 'like', '%' . $query . '%');
                    })
                    ->orWhereHas('variants', function ($q) use ($query) {
                        $q->where('name', 'like', '%' . $query . '%');
                    });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        // Convert product prices to current currency
        $products->getCollection()->transform(function ($product) use ($currencyInfo) {
            // Convert variant prices
            if ($product->variants && $product->variants->isNotEmpty()) {
                $product->variants->transform(function ($variant) use ($currencyInfo) {
                    if ($variant->price) {
                        $variant->converted_price = $this->currencyService->convertFromUSD($variant->price, $currencyInfo['currency_code']);
                    }
                    if ($variant->compare_price && $variant->compare_price > 0) {
                        $variant->converted_compare_price = $this->currencyService->convertFromUSD($variant->compare_price, $currencyInfo['currency_code']);
                    }
                    return $variant;
                });
            }
            return $product;
        });

        return view('products.search', compact('products', 'query', 'currencyInfo', 'title'));
    }

    /**
     * Clear product cache when products are updated
     */
    public static function clearProductCache()
    {
        // Clear all product-related cache
        $keys = cache()->get('product_index_cache_keys', []);
        foreach ($keys as $key) {
            cache()->forget($key);
        }
        cache()->forget('product_index_cache_keys');

        // Clear individual product cache patterns
        cache()->flush('product_show_*');
    }
}
