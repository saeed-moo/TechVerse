<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Basket;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
class CheckoutController extends Controller
{

    public function index()
    {
        $basketItems = Basket::with('product')
            ->where('user_id', auth()->id())
            ->get();

        if ($basketItems->isEmpty()) {
            return redirect()->route('basket.index')
                ->with('error', 'Your basket is empty');
        }

        $total = $basketItems->sum(function($item) {
            return $item->product->price * $item->quantity;
        });

        return view('checkout.index', compact('basketItems', 'total'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'shipping_address' => 'required|string|max:255',
            'shipping_city' => 'required|string|max:100',
            'shipping_postcode' => 'required|string|max:20',
            'contact_phone' => 'required|string|max:20',
        ]);

        $basketItems = Basket::with('product')
            ->where('user_id', auth()->id())
            ->get();

        if ($basketItems->isEmpty()) {
            return redirect()->route('basket.index')
                ->with('error', 'Your basket is empty');
        }

        // Check stock availability for all items
        foreach ($basketItems as $item) {
            if ($item->product->stock_quantity < $item->quantity) {
                return back()->with('error',
                    "Not enough stock for {$item->product->name}");
            }
        }

        DB::beginTransaction();
        try {
            // Calculate total
            $total = $basketItems->sum(function($item) {
                return $item->product->price * $item->quantity;
            });

            // Create order
            $order = Order::create([
                'user_id' => auth()->id(),
                'order_number' => 'ORD-' . strtoupper(uniqid()),
                'total_amount' => $total,
                'status' => 'pending',
                'shipping_address' => $request->shipping_address,
                'shipping_city' => $request->shipping_city,
                'shipping_postcode' => $request->shipping_postcode,
                'contact_phone' => $request->contact_phone,
            ]);

            // Create order items and update stock
            foreach ($basketItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->product->price,
                    'subtotal' => $item->product->price * $item->quantity,
                ]);

                // Decrease stock
                $item->product->decrementStock($item->quantity);
            }

            // Clear basket
            Basket::where('user_id', auth()->id())->delete();

            DB::commit();

            return redirect()->route('orders.show', $order)
                ->with('success', 'Order placed successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to process order. Please try again.');
        }
    }
}
