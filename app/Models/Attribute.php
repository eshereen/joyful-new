<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Attribute extends Model
{
    /** @use HasFactory<\Database\Factories\AttributeFactory> */
    use HasFactory;
    protected $fillable = ['name'];

    /**
     * Get the values for this attribute (e.g. Size → 200g, 400g).
     */
    public function values(): HasMany
    {
        return $this->hasMany(AttributeValue::class);
    }
}
