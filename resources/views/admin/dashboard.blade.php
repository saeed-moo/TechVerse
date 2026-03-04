<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - TechVerse</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <nav class="bg-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <a href="{{ route('home') }}" class="text-2xl font-bold">
                    <span class="text-purple-600">Tech</span
<span class="text-purple-600">Tech</span><span class="text-gray-800">Verse</span> <span class="text-sm text-gray-500">Admin</span>
                </a>
                <div class="space-x-6">
                    <a href="{{ route('admin.dashboard') }}" class="text-purple-600 font-semibold">Dashboard</a>
                    <a href="{{ route('admin.inventory.index') }}" class="text-gray-700 hover:text-purple-600">Inventory</a>
                    <a href="{{ route('home') }}" class="text-gray-700 hover:text-purple-600">View Site</a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-gray-700 hover:text-purple-600">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-8">Admin Dashboard</h1>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm">Total Orders</p>
                        <p class="text-3xl font-bold text-gray-800">{{ $stats['total_orders'] }}</p>
                    </div>
                    <div class="text-4xl">📦</div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm">Pending Orders</p>
                        <p class="text-3xl font-bold text-yellow-600">{{ $stats['pending_orders'] }}</p>
                    </div>
                    <div class="text-4xl">⏳</div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm">Total Revenue</p>
                        <p class="text-3xl font-bold text-green-600">£{{ number_format($stats['total_revenue'], 2) }}</p>
                    </div>
                    <div class="text-4xl">💰</div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm">Total Customers</p>
                        <p class="text-3xl font-bold text-blue-600">{{ $stats['total_customers'] }}</p>
                    </div>
                    <div class="text-4xl">👥</div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm">Low Stock Products</p>
                        <p class="text-3xl font-bold text-orange-600">{{ $stats['low_stock_products'] }}</p>
                    </div>
                    <div class="text-4xl">⚠️</div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm">Out of Stock</p>
                        <p class="text-3xl font-bold text-red-600">{{ $stats['out_of_stock_products'] }}</p>
                    </div>
                    <div class="text-4xl">❌</div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Recent Orders -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold mb-4">Recent Orders</h2>
                <div class="space-y-3">
                    @forelse($recentOrders as $order)
                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                            <div>
                                <p class="font-semibold text-gray-800">{{ $order->order_number }}</p>
                                <p class="text-sm text-gray-600">{{ $order->user->name }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-purple-600">£{{ number_format($order->total_amount, 2) }}</p>
                                <span class="text-xs px-2 py-1 rounded-full
                                    @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($order->status === 'processing') bg-blue-100 text-blue-800
                                    @else bg-green-100 text-green-800
                                    @endif">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-4">No orders yet</p>
                    @endforelse
                </div>
            </div>

            <!-- Low Stock Products -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold mb-4">Low Stock Alert</h2>
                <div class="space-y-3">
                    @forelse($lowStockProducts as $product)
                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                            <div>
                                <p class="font-semibold text-gray-800">{{ $product->name }}</p>
                                <p class="text-sm text-gray-600">{{ $product->category->name }}</p>
                            </div>
                            <div class="text-right">
                                <span class="px-3 py-1 rounded-full text-sm font-semibold
                                    @if($product->stock_quantity === 0) bg-red-100 text-red-800
                                    @else bg-orange-100 text-orange-800
                                    @endif">
                                    {{ $product->stock_quantity }} units
                                </span>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-4">All products in stock</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</body>
</html>
