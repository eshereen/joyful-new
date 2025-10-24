<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{
   protected $fillable=['country_id','name'];
   public function country()
   {
    return $this->belongsTo(Country::class);
   }
   public function orders()
   {
    return $this->hasMany(Order::class);
   }
   public function shippings()
   {
    return $this->hasMany(Shipping::class);
   }
}
