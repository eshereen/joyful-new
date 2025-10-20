<?php

namespace App\Models;

use Database\Factories\NewsletterFactory;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Newsletter extends Model
{
    /** @use HasFactory<NewsletterFactory> */
    use HasFactory,SoftDeletes;
    protected $fillable = ['email', 'token', 'verified'];
    protected $casts = ['verified' => 'boolean'];


       // Relationship to user (optional)
       public function user()
       {
           return $this->belongsTo(User::class);
       }

       // Generate unsubscribe token
       public static function generateToken()
       {
           return hash_hmac('sha256', Str::random(40), config('app.key'));
       }

       // Scope for active subscriptions
       public function scopeVerified($query)
       {
           return $query->where('verified', true);
       }

      
}
