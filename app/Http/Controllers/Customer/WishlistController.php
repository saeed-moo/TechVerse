<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    /**
     * Display the wishlist
     */
    public function index()
    {
        $wishlist = auth()->user()->getOrCreateWishlist();
        $products = $wishlist->products()->with('category')->get();

        return view('wishlist.index', compact('products'));
    }

    /**
     * Add product to wishlist
     */
    public function add(Product $product)
    {
        $wishlist = auth()->user()->getOrCreateWishlist();

        // Check if already in wishlist
        if ($wishlist->products()->where('product_id', $product->id)->exists()) {
            return redirect()->back()->with('error', 'Product is already in your wishlist!');
        }

        // Add to wishlist
        $wishlist->products()->attach($product->id);

        return redirect()->back()
        ->with('success', 'Added to wishlist! ❤️');
    }

    /**
     * Remove product from wishlist
     */
    public function remove(Product $product)
    {
        $wishlist = auth()->user()->getOrCreateWishlist();

        $wishlist->products()->detach($product->id);

        return redirect()->back()->with('success', 'Product removed from wishlist!');
    }

/**
 * Move product from wishlist to basket
 */
public function moveToBasket(Product $product)
{
    // Check stock
    if ($product->stock_quantity <= 0) {
        return redirect()->back()->with('error', 'Product is out of stock!');
    }

    // Check if product already in user's basket
    $basketItem = auth()->user()->baskets()
        ->where('product_id', $product->id)
        ->first();

    if ($basketItem) {
        // Increase quantity
        $basketItem->increment('quantity');
    } else {
        // Add to basket
        auth()->user()->baskets()->create([
            'product_id' => $product->id,
            'quantity' => 1,
        ]);
    }

    // Remove from wishlist
    $wishlist = auth()->user()->wishlist;
    if ($wishlist) {
        $wishlist->products()->detach($product->id);
    }

    return redirect()->route('basket.index')->with('success', 'Product moved to basket!');
}
/**
 * Clear all items from wishlist
 */
public function clear()
{
    $wishlist = auth()->user()->wishlist;

    if ($wishlist) {
        $wishlist->products()->detach(); // Remove all products
    }

    return redirect()->route('wishlist.index')
        ->with('success', 'Wishlist cleared successfully!');
}
}
