<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

class DashboardController extends Controller
{

    public function index()
    {
        $stats = [
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'total_revenue' => Order::where('status', '!=', 'cancelled')->sum('total_amount'),
            'total_customers' => User::where('role', 'customer')->count(),
            'low_stock_products' => Product::where('stock_status', 'low_stock')->count(),
            'out_of_stock_products' => Product::where('stock_status', 'out_of_stock')->count(),
        ];

        $recentOrders = Order::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $lowStockProducts = Product::where('stock_status', 'low_stock')
            ->orWhere('stock_status', 'out_of_stock')
            ->orderBy('stock_quantity', 'asc')
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentOrders', 'lowStockProducts'));
    }
}
