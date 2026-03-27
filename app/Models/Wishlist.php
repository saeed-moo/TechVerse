<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
    ];

    /**
     * Get the user that owns the wishlist
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the wishlist items
     */
    public function items()
    {
        return $this->hasMany(WishlistItem::class);
    }

    /**
     * Get the products in this wishlist
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'wishlist_items')
                    ->withTimestamps();
    }
}
