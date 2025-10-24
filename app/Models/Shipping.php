<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shipping extends Model
{
      protected $fillable=['state_id','price'];

    public function state()
    {
        return $this->belongsTo(State::class);
    }
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
