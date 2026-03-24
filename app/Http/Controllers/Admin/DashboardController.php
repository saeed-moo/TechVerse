<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Review;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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
        'average_rating' => Review::avg('rating') ?? 0,
    ];

    $recentOrders = Order::with('user')
        ->orderBy('created_at', 'desc')
        ->limit(10)
        ->get();

    $lowStockProducts = Product::with('category')
        ->where('stock_status', 'low_stock')
        ->orWhere('stock_status', 'out_of_stock')
        ->orderBy('stock_quantity', 'asc')
        ->limit(10)
        ->get();

    // Sales chart data (last 7 days)
    $salesChartData = $this->getSalesChartData();

    // NEW: Top 5 Best Selling Products
    $bestSellingProducts = $this->getBestSellingProducts();

    // NEW: Order Status Breakdown
    $orderStatusData = $this->getOrderStatusData();

    // NEW: Month Comparison
    $monthComparison = $this->getMonthComparison();

    return view('admin.dashboard', compact(
        'stats',
        'recentOrders',
        'lowStockProducts',
        'salesChartData',
        'bestSellingProducts',
        'orderStatusData',
        'monthComparison'
    ));
}

    /**
     * Get sales data for the last 7 days
     */
    protected function getSalesChartData()
    {
        $days = [];
        $sales = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);

            $days[] = $date->format('M d');

            $dailySales = Order::whereDate('created_at', $date)
                ->where('status', '!=', 'cancelled')
                ->sum('total_amount');

            $sales[] = round($dailySales, 2);
        }

        return [
            'labels' => $days,
            'data' => $sales,
        ];
    }
    /**
 * Get top 5 best-selling products
 */
protected function getBestSellingProducts()
{
    return Product::select(
            'products.id',
            'products.name',
            'products.slug',
            'products.category_id',
            'products.price',
            'products.description',
            'products.image',
            'products.stock_quantity',
            'products.stock_status',
            'products.low_stock_threshold',
            'products.created_at',
            'products.updated_at',
            DB::raw('SUM(order_items.quantity) as total_sold')
        )
        ->join('order_items', 'products.id', '=', 'order_items.product_id')
        ->join('orders', 'order_items.order_id', '=', 'orders.id')
        ->where('orders.status', '!=', 'cancelled')
        ->groupBy(
            'products.id',
            'products.name',
            'products.slug',
            'products.category_id',
            'products.price',
            'products.description',
            'products.image',
            'products.stock_quantity',
            'products.stock_status',
            'products.low_stock_threshold',
            'products.created_at',
            'products.updated_at'
        )
        ->with('category')
        ->orderBy('total_sold', 'desc')
        ->limit(5)
        ->get();
}

/**
 * Get order status breakdown for donut chart
 */
protected function getOrderStatusData()
{
    return [
        'labels' => ['Pending', 'Processing', 'Shipped', 'Delivered', 'Cancelled'],
        'data' => [
            Order::where('status', 'pending')->count(),
            Order::where('status', 'processing')->count(),
            Order::where('status', 'shipped')->count(),
            Order::where('status', 'delivered')->count(),
            Order::where('status', 'cancelled')->count(),
        ],
        'colors' => ['#EAB308', '#3B82F6', '#A855F7', '#10B981', '#EF4444'],
    ];
}

/**
 * Get this month vs last month comparison
 */
protected function getMonthComparison()
{
    $thisMonthStart = Carbon::now()->startOfMonth();
    $lastMonthStart = Carbon::now()->subMonth()->startOfMonth();
    $lastMonthEnd = Carbon::now()->subMonth()->endOfMonth();

    $thisMonthRevenue = Order::where('created_at', '>=', $thisMonthStart)
        ->where('status', '!=', 'cancelled')
        ->sum('total_amount');

    $lastMonthRevenue = Order::whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])
        ->where('status', '!=', 'cancelled')
        ->sum('total_amount');

    $thisMonthOrders = Order::where('created_at', '>=', $thisMonthStart)->count();
    $lastMonthOrders = Order::whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])->count();

    return [
        'revenue' => [
            'current' => $thisMonthRevenue,
            'previous' => $lastMonthRevenue,
            'change' => $lastMonthRevenue > 0
                ? round((($thisMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100, 1)
                : 0,
        ],
        'orders' => [
            'current' => $thisMonthOrders,
            'previous' => $lastMonthOrders,
            'change' => $lastMonthOrders > 0
                ? round((($thisMonthOrders - $lastMonthOrders) / $lastMonthOrders) * 100, 1)
                : 0,
        ],
    ];
}
}
