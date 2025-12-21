<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Collection;
use App\Models\Subcategory;
use App\Models\Review;
use Illuminate\Http\Request;

class FrontendController extends Controller
{
    public function index()
    {
        $title = 'Joyful|Home';
        // Load main categories with their own products only
        $categories = cache()->remember('home_categories', 1800, function () {
            return Category::orderBy('categories.name', 'asc') // Order by name for consistency
                ->take(4)
                ->with(['products' => function ($query) {
                    $query->with(['media' => function ($q) {
                            $q->select('id', 'model_id', 'model_type', 'collection_name', 'file_name', 'disk')
                            ->whereIn('collection_name', ['main_image','product_images']);
                        }, 'category:id,name,slug'])
                        ->where('products.active', true)
                        ->take(8);
                }])
                ->get();
        });

        // Collections - eager load media for images and products
        $collections = cache()->remember('home_collections', 1800, function () {
            return Collection::with([
                'media' => function ($q) {
                    $q->where('collection_name', 'main_image');
                },
                'products' => function ($q) {
                    $q->where('products.active', true);
                }
            ])
                ->withCount(['products' => function ($q) {
                    $q->where('products.active', true);
                }])
                ->where('collections.active', true)
                ->take(4)
                ->get();
        });

        // Featured products for slider - cached
        $products = cache()->remember('home_featured_products', 1800, function () {
            return Product::with([
                'category:id,name,slug',
                'media' => function ($query) {
                    $query->select('id', 'model_id', 'model_type', 'collection_name', 'file_name', 'disk')
                          ->whereIn('collection_name', ['main_image', 'product_images'])
                          ->whereNotNull('disk');
                },
                'variants:id,product_id,price,stock'
            ])
            ->select('id', 'name', 'slug', 'description', 'category_id', 'active',  'featured', 'created_at')
            ->where('active', true)
            ->orderBy('featured', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();
        });
        $reviews = Review::latest()->get();

        return view('home-1', compact('title', 'categories', 'collections', 'products','reviews'));
    }



   public function thankyou()
   {

    $title = 'Joyful|Thank you';
       return view('thankyou',compact('title'));
   }


   public function terms()
   {
    $title = 'Joyful|Terms & Conditions';
       return view( 'terms',compact('title'));
   }


   public function privacy()
   {
    $title = 'Joyful|Privacy Policy';
       return view( 'privacy',compact('title'));
   }
   public function about()
   {
    $title = 'Joyful|About Us';
       return view('about',compact('title'));
   }

    public function return()
    {
        $title = 'Joyful|Return Policy';
        return view('return',compact('title'));
    }
    public function location()
    {
        $title = 'Joyful|Locations';
        return view( 'location',compact('title'));
    }



}
