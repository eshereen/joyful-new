<?php

namespace App\Providers;

use App\Contracts\CartServiceContract;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use App\Models\OrderItem;
use App\Events\OrderPlaced;
use App\Observers\OrderObserver;
use App\Observers\ProductObserver;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Events\PaymentStatusChanged;
use App\Observers\MediaSyncObserver;
use App\Observers\OrderItemObserver;
use Illuminate\Support\Facades\View;
use App\Listeners\AwardLoyaltyPoints;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use App\Listeners\HandlePaymentStatusChange;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use App\Services\CartService;

class AppServiceProvider extends ServiceProvider
{
    protected $listen = [
        OrderPlaced::class => [
            AwardLoyaltyPoints::class,
        ],
        PaymentStatusChanged::class => [
            HandlePaymentStatusChange::class,
        ],
    ];

    public function register(): void
    {
        $this->app->singleton(CartServiceContract::class, CartService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
         Schema::defaultStringLength(191);
        // Temporarily disabled to debug 502 on checkout (emails in observer)
         Order::observe(OrderObserver::class);
        OrderItem::observe(OrderItemObserver::class);
        Product::observe(ProductObserver::class);

        // Share categories only with navbar (not all views) - Optimized with caching and eager loading
        View::composer(['layouts.navbar', 'layouts.app'], function ($view) {
            $categories = cache()->remember('joyful_cache_header_categories', 1800, function () {
                return Category::with(['media'])
                    ->withCount(['products' => function ($query) {
                        $query->where('products.active', true);
                    }])
                    ->orderBy('name')
                    ->take(4)
                    ->get()
                    ->map(function ($category) {
                        $category->media_url = $category->getFirstMediaUrl('main_image', 'small_webp');
                        return $category;
                    });
            });

            // Get collections for dropdown - eager load media
            $collections = cache()->remember('joyful_cache_header_collections', 1800, function () {
                return \App\Models\Collection::where('collections.active', true)
                    ->with(['media' => function ($q) {
                        $q->where('collection_name', 'main_image');
                    }])
                    ->withCount(['products' => function ($query) {
                        $query->where('products.active', true);
                    }])
                    ->orderBy('name')
                    ->get();
            });
            
            // Get products for dropdown - with variants eager loaded to prevent N+1
            $products = cache()->remember('joyful_cache_header_products', 1800, function () {
                return Product::where('products.active', true)
                    ->with(['variants:id,product_id,price,compare_price,stock'])
                    ->orderBy('name')
                    ->take(20) // Limit to first 20 products
                    ->get();
            });

            $view->with('categories', $categories);
            $view->with('collections', $collections);
            $view->with('products', $products);
        });

        // Share all categories for category pages - Cached separately
        View::composer(['livewire.categories-grid', 'livewire.category-products'], function ($view) {
            $allCategories = cache()->remember('all_categories', 900, function () {
                return Category::where('categories.active', true)
                    ->with(['media'])
                    ->withCount(['products' => function ($query) {
                        $query->where('products.active', true);
                    }])
                    ->with(['subcategories' => function ($query) {
                        $query->where('subcategories.active', true);
                    }])
                    ->orderBy('name')
                    ->get()
                    ->map(function ($category) {
                        $category->media_url = $category->getFirstMediaUrl('main_image', 'small_webp');
                        return $category;
                    });
            });

            $view->with('allCategories', $allCategories);
        });

        // Cache clearing events for categories
        Event::listen(['eloquent.created: App\Models\Category', 'eloquent.updated: App\Models\Category', 'eloquent.deleted: App\Models\Category'], function () {
            Cache::forget('joyful_cache_header_categories');
            Cache::forget('all_categories');
        });

        // Cache clearing events for collections
        Event::listen(['eloquent.created: App\Models\Collection', 'eloquent.updated: App\Models\Collection', 'eloquent.deleted: App\Models\Collection'], function () {
            Cache::forget('joyful_cache_header_collections');
        });

        // Cache clearing events for subcategories
        Event::listen(['eloquent.created: App\Models\Subcategory', 'eloquent.updated: App\Models\Subcategory', 'eloquent.deleted: App\Models\Subcategory'], function () {
            Cache::forget('joyful_cache_header_categories');
            Cache::forget('all_categories');
        });

        // Cache clearing events for products (affects category counts)
        Event::listen(['eloquent.created: App\Models\Product', 'eloquent.updated: App\Models\Product', 'eloquent.deleted: App\Models\Product'], function () {
            Cache::forget('joyful_cache_header_categories');
            Cache::forget('all_categories');
            Cache::forget('joyful_cache_header_collections');
            Cache::forget('joyful_cache_header_products');
        });

        // Cache clearing events for orders and order items (affects best seller calculations)
        Event::listen(['eloquent.created: App\Models\Order', 'eloquent.updated: App\Models\Order', 'eloquent.deleted: App\Models\Order'], function () {
            // Clear best seller cache when orders change
            $bestSellerService = app(\App\Services\BestSellerService::class);
            $bestSellerService->clearCache();
        });

        Event::listen(['eloquent.created: App\Models\OrderItem', 'eloquent.updated: App\Models\OrderItem', 'eloquent.deleted: App\Models\OrderItem'], function () {
            // Clear best seller cache when order items change
            $bestSellerService = app(\App\Services\BestSellerService::class);
            $bestSellerService->clearCache();
        });

        // Optimize database queries with query logging in development
        if (app()->environment('local')) {
            DB::listen(function ($query) {
                if ($query->time > 100) { // Log slow queries (>100ms)
                    Log::info('Slow Query: ' . $query->sql, [
                        'time' => $query->time,
                        'bindings' => $query->bindings
                    ]);
                }
            });
        }
        Media::observe(MediaSyncObserver::class);
    }
}
