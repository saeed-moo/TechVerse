<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Basket;
use App\Models\Product;

class BasketController extends Controller
{

    public function index()
    {
        $basketItems = Basket::with('product')
            ->where('user_id', auth()->id())
            ->get();

        $total = $basketItems->sum(function($item) {
            return $item->product->price * $item->quantity;
        });

        return view('basket.index', compact('basketItems', 'total'));
    }

    public function add(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        // Check stock availability
        if ($product->stock_quantity < $request->quantity) {
            return back()->with('error', 'Not enough stock available');
        }

        $basket = Basket::where('user_id', auth()->id())
            ->where('product_id', $product->id)
            ->first();

        if ($basket) {
            // Update existing basket item
            $newQuantity = $basket->quantity + $request->quantity;
            if ($product->stock_quantity < $newQuantity) {
                return back()->with('error', 'Not enough stock available');
            }
            $basket->update(['quantity' => $newQuantity]);
        } else {
            // Create new basket item
            Basket::create([
                'user_id' => auth()->id(),
                'product_id' => $product->id,
                'quantity' => $request->quantity,
            ]);
        }

        return back()->with('success', 'Product added to basket!');
    }

    public function update(Request $request, Basket $basket)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        if ($basket->product->stock_quantity < $request->quantity) {
            return back()->with('error', 'Not enough stock available');
        }

        $basket->update(['quantity' => $request->quantity]);

        return back()->with('success', 'Basket updated!');
    }

    public function destroy(Basket $basket)
    {
        $basket->delete();
        return back()->with('success', 'Item removed from basket');
    }
}
