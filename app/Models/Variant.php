<?php

namespace App\Models;

use Exception;
use App\Traits\HasSku;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Database\Factories\VariantFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Variant extends Model
{
    /** @use HasFactory<VariantFactory> */
    use HasFactory, HasSku;

    protected $fillable = ['product_id',  'sku', 'stock', 'price', 'weight', 'compare_price', 'wick_type', 'size'];

    protected $casts = [
        'price' => 'decimal:2',
        'weight' => 'decimal:2',
        'stock' => 'integer',
        'compare_price' => 'decimal:2',
    ];

    const WICK_TYPES = [
        'wooden' => 'Wooden',
        'cotton' => 'Cotton',
    ];
    const SIZE = [
        '200' => '200',
        '250' => '250',
        '300' => '300',
        '350' => '350',
        '400' => '400',
        '450' => '450',
        '500' => '500',
    ];





    /**
     * Check if variant is in stock
     */
    public function getIsInStockAttribute()
    {
        return $this->stock > 0;
    }

    /**
     * Get formatted price with currency
     */
    public function getFormattedPriceAttribute()
    {
        return '$' . number_format($this->price, 2);
    }

    /**
     * Get stock status text
     */
    public function getStockStatusAttribute()
    {
        if ($this->stock <= 0) {
            return 'Out of Stock';
        } elseif ($this->stock <= 5) {
            return 'Low Stock';
        } else {
            return 'In Stock';
        }
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Scope to get variants in stock
     */
    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0);
    }

    public function options()
    {
        return $this->hasMany(VariantOption::class);
    }

    /**
     * Scope to get variants by size
     */
    public function scopeBySize($query, $size)
    {
        return $query->where('size', $size);
    }







    /**
     * Update stock with cache invalidation
     */
    public function updateStock($newStock)
    {
        $this->stock = $newStock;
        $this->save();

        // Clear related caches
        $this->clearRelatedCaches();
    }

}
