<?php

namespace App\Models;

use Database\Factories\CollectionFactory;
use App\Traits\Sluggable;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Collection extends Model implements HasMedia
{
    /** @use HasFactory<CollectionFactory> */
    use HasFactory, Sluggable,InteractsWithMedia;
    protected $fillable = ['name', 'slug', 'description', 'price', 'stock', 'active'];

    protected $casts = [
        'price' => 'decimal:2',
        'stock' => 'integer',
        'active' => 'boolean',
    ];

    protected $attributes = [
        'price' => null,
        'stock' => 0,
    ];

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'collection_products', 'collection_id', 'product_id')
            ->using(CollectionProduct::class)
            ->withTimestamps();
    }

    /**
     * Get the collection price (use set price or calculate from products)
     */
    public function getPriceAttribute($value)
    {
        // If price is set in database, return it
        if ($value !== null && $value > 0) {
            return (float) $value;
        }

        // Otherwise, calculate from products
        if ($this->relationLoaded('products')) {
            return $this->products
                ->where('active', true)
                ->sum(function ($product) {
                    return $product->price ?? 0;
                });
        }

        return $this->products()
            ->where('active', true)
            ->get()
            ->sum(function ($product) {
                return $product->price ?? 0;
            });
    }


     //register media collections
   // Register media collections
public function registerMediaCollections(?Media $media = null): void
{
    // Main image (single file)
    $this->addMediaCollection('main_image')
        ->singleFile()
        ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp'])
        ->registerMediaConversions(function (Media $media) {

            // Always keep original (JPG/PNG/etc.)
            // Convert optimized versions:

            // WebP versions
            $this->addMediaConversion('thumb_webp')
                ->format('webp')
                ->width(150)
                ->height(150)
                ->sharpen(10)

                ->nonQueued();

            $this->addMediaConversion('medium_webp')
                ->format('webp')
                ->width(400)
                ->height(400)

                ->nonQueued();

            $this->addMediaConversion('large_webp')
                ->format('webp')
                ->width(800)
                ->height(800)

                ->nonQueued();

            // AVIF conversions disabled - requires PHP with AVIF support
            // Uncomment these if you have imageavif() function available
            // $this->addMediaConversion('thumb_avif')
            //     ->format('avif')
            //     ->width(150)
            //     ->height(150)
            //     ->nonQueued();
            //
            // $this->addMediaConversion('medium_avif')
            //     ->format('avif')
            //     ->width(400)
            //     ->height(400)
            //     ->nonQueued();
            //
            // $this->addMediaConversion('large_avif')
            //     ->format('avif')
            //     ->width(800)
            //     ->height(800)
            //     ->nonQueued();
        });


}
}
