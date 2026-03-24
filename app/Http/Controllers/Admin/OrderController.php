<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderStatusUpdated;

class OrderController extends Controller
{
    /**
     * Display all orders
     */
    public function index(Request $request)
    {
        $query = Order::with(['user', 'items.product']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search by order number or customer name
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('order_number', 'like', '%' . $request->search . '%')
                  ->orWhereHas('user', function($q) use ($request) {
                      $q->where('name', 'like', '%' . $request->search . '%');
                  });
            });
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Show order details
     */
    public function show(Order $order)
    {
        $order->load(['user', 'items.product']);

        return view('admin.orders.show', compact('order'));
    }

    /**
     * Update order status
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
        ]);

        $oldStatus = $order->status;

        $order->update([
            'status' => $request->status,
        ]);

        // Send email notification to customer
        $order->load(['user', 'items.product']);
        Mail::to($order->user->email)->send(new OrderStatusUpdated($order, $oldStatus));

        return redirect()->back()->with('success', 'Order status updated and customer notified!');
    }
}
