<?php

namespace App\Traits;

use App\Models\Category;

trait HasSku
{
    protected static function bootHasSku()
    {
        static::creating(function ($model) {
            if (empty($model->sku)) {
                $model->sku = $model->generateSku();
            }
        });
    }

    public function generateSku(): string
    {
        $categoryCode = $this->getCategoryCode();
        $productCode = $this->getProductCode();
        $uniqueId = $this->generateUniqueId();

        $sku = "{$categoryCode}-{$productCode}-{$uniqueId}";
        
        // Ensure SKU is unique by checking database and incrementing if needed
        $counter = 1;
        $originalUniqueId = $uniqueId;
        while (static::where('sku', $sku)->exists()) {
            $uniqueId = str_pad((int)$originalUniqueId + $counter, 3, '0', STR_PAD_LEFT);
            $sku = "{$categoryCode}-{$productCode}-{$uniqueId}";
            $counter++;
        }

        return $sku;
    }

    protected function getCategoryCode(): string
    {
        // For Variant models, get category from the related product
        if (get_class($this) === 'App\Models\Variant' && !empty($this->product_id)) {
            $product = \App\Models\Product::find($this->product_id);
            if ($product && !empty($product->category_id)) {
                $category = Category::find($product->category_id);
                if ($category) {
                    return strtoupper(substr(
                        preg_replace('/[^A-Z0-9]/', '', $category->name),
                        0,
                        3
                    ));
                }
            }
        }

        // If product has category_id directly
        if (!empty($this->category_id)) {
            $category = Category::find($this->category_id);
            if ($category) {
                return strtoupper(substr(
                    preg_replace('/[^A-Z0-9]/', '', $category->name),
                    0,
                    3
                ));
            }
        }

        return 'GEN';
    }

    protected function getProductCode(): string
    {
        // For Variant models, get the product name from the related product
        if (get_class($this) === 'App\Models\Variant' && !empty($this->product_id)) {
            $product = \App\Models\Product::find($this->product_id);
            if ($product && !empty($product->name)) {
                $cleanName = preg_replace('/[^A-Z0-9]/', '', strtoupper($product->name));
                if (!empty($cleanName)) {
                    return substr($cleanName, 0, 4);
                }
            }
        }

        // For Product models or when name is directly available
        if (!empty($this->name)) {
            $cleanName = preg_replace('/[^A-Z0-9]/', '', strtoupper($this->name));
            if (!empty($cleanName)) {
                return substr($cleanName, 0, 4);
            }
        }

        return 'PRD';
    }

    protected function generateUniqueId(): string
    {
        // For Variant models, use product_id to get the next variant number
        if (get_class($this) === 'App\Models\Variant' && !empty($this->product_id)) {
            $nextId = $this->getNextIdForProduct($this->product_id);
        } elseif (!empty($this->category_id)) {
            $nextId = $this->getNextIdForCategory($this->category_id);
        } else {
            $nextId = static::count() + 1;
        }

        return str_pad($nextId, 3, '0', STR_PAD_LEFT);
    }

    protected function getNextIdForCategory($categoryId): int
    {
        $count = static::where('category_id', $categoryId)->count();
        return $count + 1;
    }

    protected function getNextIdForProduct($productId): int
    {
        $count = static::where('product_id', $productId)->count();
        return $count + 1;
    }
}
