<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VariantOption extends Model
{
    /** @use HasFactory<\Database\Factories\VariantOptionFactory> */
    use HasFactory;
    protected $fillable = ['variant_id', 'attribute_value_id'];

    public function variant()
    {
        return $this->belongsTo(Variant::class);
    }

    public function attributeValue()
    {
        return $this->belongsTo(AttributeValue::class);
    }
}
