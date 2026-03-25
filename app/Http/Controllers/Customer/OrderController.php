<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    /**
     * Display user's orders
     */
    public function index()
    {
        $orders = Order::where('user_id', auth()->id())
            ->with(['items.product'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    /**
     * Show order details with tracking
     */
    public function show(Order $order)
    {
        // Check authorization
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access to this order.');
        }

        $order->load('items.product');

        // Calculate estimated delivery date
        $estimatedDelivery = $this->calculateEstimatedDelivery($order);

        // Get order timeline
        $timeline = $this->getOrderTimeline($order);

        return view('orders.show', compact('order', 'estimatedDelivery', 'timeline'));
    }

    /**
     * Track order status
     */
    public function track(Order $order)
    {
        // Check authorization
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access to this order.');
        }

        $order->load('items.product');

        $estimatedDelivery = $this->calculateEstimatedDelivery($order);
        $timeline = $this->getOrderTimeline($order);

        return view('orders.track', compact('order', 'estimatedDelivery', 'timeline'));
    }

    /**
     * Calculate estimated delivery date
     */
    protected function calculateEstimatedDelivery(Order $order)
    {
        $created = $order->created_at;

        switch ($order->status) {
            case 'pending':
                return $created->addDays(5)->format('M d, Y');
            case 'processing':
                return $created->addDays(4)->format('M d, Y');
            case 'shipped':
                return $created->addDays(2)->format('M d, Y');
            case 'delivered':
                return $order->delivered_at ? $order->delivered_at->format('M d, Y') : 'Delivered';
            case 'cancelled':
                return 'N/A';
            default:
                return $created->addDays(5)->format('M d, Y');
        }
    }

    /**
     * Get order timeline events
     */
    protected function getOrderTimeline(Order $order)
    {
        $timeline = [];

        // Order Placed
        $timeline[] = [
            'status' => 'placed',
            'title' => 'Order Placed',
            'description' => 'Your order has been received',
            'date' => $order->created_at,
            'completed' => true,
        ];

        // Processing
        $timeline[] = [
            'status' => 'processing',
            'title' => 'Processing',
            'description' => 'Your order is being prepared',
            'date' => $order->processed_at,
            'completed' => in_array($order->status, ['processing', 'shipped', 'delivered']),
        ];

        // Shipped
        $timeline[] = [
            'status' => 'shipped',
            'title' => 'Shipped',
            'description' => 'Your order is on the way',
            'date' => $order->shipped_at,
            'completed' => in_array($order->status, ['shipped', 'delivered']),
        ];

        // Delivered
        $timeline[] = [
            'status' => 'delivered',
            'title' => 'Delivered',
            'description' => 'Your order has been delivered',
            'date' => $order->delivered_at,
            'completed' => $order->status === 'delivered',
        ];

        // Check if cancelled
        if ($order->status === 'cancelled') {
            $timeline = [
                [
                    'status' => 'placed',
                    'title' => 'Order Placed',
                    'description' => 'Your order was received',
                    'date' => $order->created_at,
                    'completed' => true,
                ],
                [
                    'status' => 'cancelled',
                    'title' => 'Cancelled',
                    'description' => 'Your order has been cancelled',
                    'date' => $order->updated_at,
                    'completed' => true,
                ],
            ];
        }

        return $timeline;
    }
}
