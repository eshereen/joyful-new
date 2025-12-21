<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    /**
     * Display product listing
     */
    public function index()
    {
        $title = 'Products | Joyful';

        // Cache the entire result for better performance
        $result = cache()->remember('products_index', 300, function () {
            // Optimized query with specific selects and eager loading
            return Product::with([
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
        });

        return view('products.index', compact('result', 'title'));
    }

    /**
     * Show single product page or collection bundle
     */
    public function show(Request $request, Product $product = null)
    {
        $collection = null;

        // Check if collection ID is provided in query parameter (for collection bundles)
        if ($request->has('collection')) {
            $collection = \App\Models\Collection::with('media')->find($request->get('collection'));

            if (!$collection) {
                abort(404, 'Collection not found');
            }

            // Collections are standalone bundles - no product needed
            $title = $collection->name . ' | Joyful';

            return view('products.show', [
                'product' => null, // No product for collections
                'collection' => $collection,
                'title' => $title
            ]);
        }

        // If no collection and no product, 404
        if (!$product) {
            abort(404, 'Product not found');
        }

        $title = $product->name . ' | Joyful';

        // Cache the product with eager loading for better performance
        $cacheKey = 'product_show_' . $product->id;
        
        $product = cache()->remember($cacheKey, 600, function () use ($product) {
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

            return $product;
        });

        return view('products.show', compact('product', 'collection', 'title'));
    }

    /**
     * Search products
     */
    public function search(Request $request)
    {
        $query = $request->get('q', '');
        $title = 'Search Results for "' . $query . '" | Joyful';

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

        return view('products.search', compact('products', 'query', 'title'));
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
