<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - TechVerse</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
</head>
<body class="bg-gray-50 dark:bg-gray-900">
    <nav class="bg-white dark:bg-gray-800 shadow-md">
        <div class="max-w-7xl mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <a href="{{ route('home') }}" class="text-2xl font-bold">
                    <span class="text-purple-600 dark:text-purple-400">Tech</span><span class="text-gray-800 dark:text-white">Verse</span>
                    <span class="text-sm text-gray-500 dark:text-gray-400">Admin</span>
                </a>
                <div class="space-x-6">
                    <a href="{{ route('admin.dashboard') }}" class="text-purple-600 dark:text-purple-400 font-semibold">Dashboard</a>
                    <a href="{{ route('admin.inventory.index') }}" class="text-gray-700 dark:text-gray-300 hover:text-purple-600 dark:hover:text-purple-400">Inventory</a>
                    <a href="{{ route('admin.orders.index') }}" class="text-gray-700 dark:text-gray-300 hover:text-purple-600 dark:hover:text-purple-400">Orders</a>
                    <a href="{{ route('home') }}" class="text-gray-700 dark:text-gray-300 hover:text-purple-600 dark:hover:text-purple-400">View Site</a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-gray-700 dark:text-gray-300 hover:text-purple-600 dark:hover:text-purple-400">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-8 text-gray-900 dark:text-white">Admin Dashboard</h1>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            {{-- Total Revenue --}}
            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm font-semibold uppercase tracking-wide">Total Revenue</p>
                        <p class="text-3xl font-bold mt-2">£{{ number_format($stats['total_revenue'], 2) }}</p>
                    </div>
                    <div class="text-5xl opacity-80">💰</div>
                </div>
            </div>

            {{-- Total Orders --}}
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm font-semibold uppercase tracking-wide">Total Orders</p>
                        <p class="text-3xl font-bold mt-2">{{ $stats['total_orders'] }}</p>
                    </div>
                    <div class="text-5xl opacity-80">📦</div>
                </div>
            </div>

            {{-- Total Customers --}}
            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-100 text-sm font-semibold uppercase tracking-wide">Total Customers</p>
                        <p class="text-3xl font-bold mt-2">{{ $stats['total_customers'] }}</p>
                    </div>
                    <div class="text-5xl opacity-80">👥</div>
                </div>
            </div>

            {{-- Average Rating - NEW! --}}
            <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-lg shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-yellow-100 text-sm font-semibold uppercase tracking-wide">Average Rating</p>
                        <p class="text-3xl font-bold mt-2">{{ number_format($stats['average_rating'], 1) }} ⭐</p>
                    </div>
                    <div class="text-5xl opacity-80">⭐</div>
                </div>
            </div>
        </div>

        <!-- Secondary Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 dark:text-gray-400 text-sm">Pending Orders</p>
                        <p class="text-3xl font-bold text-yellow-600 dark:text-yellow-500">{{ $stats['pending_orders'] }}</p>
                    </div>
                    <div class="text-4xl">⏳</div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 dark:text-gray-400 text-sm">Low Stock Products</p>
                        <p class="text-3xl font-bold text-orange-600 dark:text-orange-500">{{ $stats['low_stock_products'] }}</p>
                    </div>
                    <div class="text-4xl">⚠️</div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 dark:text-gray-400 text-sm">Out of Stock</p>
                        <p class="text-3xl font-bold text-red-600 dark:text-red-500">{{ $stats['out_of_stock_products'] }}</p>
                    </div>
                    <div class="text-4xl">❌</div>
                </div>
            </div>
        </div>

        {{-- Sales Chart - NEW! --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-xl font-bold mb-4 text-gray-900 dark:text-white">📈 Sales This Week</h2>
            <canvas id="salesChart" height="80"></canvas>
        </div>

        {{-- Month Comparison & Order Status --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
    {{-- This Month vs Last Month --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold mb-4 text-gray-900 dark:text-white">📅 Monthly Performance</h2>
        <div class="space-y-4">
            {{-- Revenue Comparison --}}
            <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm font-semibold text-gray-600 dark:text-gray-400">Revenue</span>
                    <span class="text-xs px-2 py-1 rounded-full font-bold
                        {{ $monthComparison['revenue']['change'] >= 0 ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                        {{ $monthComparison['revenue']['change'] >= 0 ? '+' : '' }}{{ $monthComparison['revenue']['change'] }}%
                    </span>
                </div>
                <div class="flex justify-between items-end">
                    <div>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">£{{ number_format($monthComparison['revenue']['current'], 2) }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">This Month</p>
                    </div>
                    <div class="text-right">
                        <p class="text-lg text-gray-600 dark:text-gray-400">£{{ number_format($monthComparison['revenue']['previous'], 2) }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Last Month</p>
                    </div>
                </div>
            </div>

            {{-- Orders Comparison --}}
            <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm font-semibold text-gray-600 dark:text-gray-400">Orders</span>
                    <span class="text-xs px-2 py-1 rounded-full font-bold
                        {{ $monthComparison['orders']['change'] >= 0 ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                        {{ $monthComparison['orders']['change'] >= 0 ? '+' : '' }}{{ $monthComparison['orders']['change'] }}%
                    </span>
                </div>
                <div class="flex justify-between items-end">
                    <div>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $monthComparison['orders']['current'] }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">This Month</p>
                    </div>
                    <div class="text-right">
                        <p class="text-lg text-gray-600 dark:text-gray-400">{{ $monthComparison['orders']['previous'] }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Last Month</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Order Status Breakdown (Donut Chart) --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold mb-4 text-gray-900 dark:text-white">📊 Order Status</h2>
        <canvas id="orderStatusChart" height="200"></canvas>
    </div>
</div>

{{-- Top 5 Best Selling Products --}}
<div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-8">
    <h2 class="text-xl font-bold mb-4 text-gray-900 dark:text-white">🏆 Top 5 Best-Selling Products</h2>
    @if($bestSellingProducts->isNotEmpty())
        <div class="space-y-3">
            @foreach($bestSellingProducts as $index => $product)
                <div class="flex items-center gap-4 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition">
                    {{-- Rank Badge --}}
                    <div class="flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center font-bold text-white
                        {{ $index === 0 ? 'bg-yellow-500' : ($index === 1 ? 'bg-gray-400' : ($index === 2 ? 'bg-orange-600' : 'bg-purple-600')) }}">
                        #{{ $index + 1 }}
                    </div>

                    {{-- Product Info --}}
                    <div class="flex-1">
                        <h3 class="font-bold text-gray-900 dark:text-white">{{ $product->name }}</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $product->category->name ?? 'N/A' }}</p>
                    </div>

                    {{-- Sales Stats --}}
                    <div class="text-right">
                        <p class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ $product->total_sold }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Units Sold</p>
                    </div>

                    {{-- Price --}}
                    <div class="text-right">
                        <p class="text-lg font-bold text-gray-900 dark:text-white">£{{ number_format($product->price, 2) }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Price</p>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-gray-500 dark:text-gray-400 text-center py-8">No sales data available yet</p>
    @endif
</div>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Recent Orders -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold mb-4 text-gray-900 dark:text-white">Recent Orders</h2>
                <div class="space-y-3">
                    @forelse($recentOrders as $order)
                        <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-700 rounded">
                            <div>
                                <p class="font-semibold text-gray-800 dark:text-white">{{ $order->order_number }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $order->user->name }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-purple-600 dark:text-purple-400">£{{ number_format($order->total_amount, 2) }}</p>
                                <span class="text-xs px-2 py-1 rounded-full
                                    @if($order->status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                    @elseif($order->status === 'processing') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                    @elseif($order->status === 'shipped') bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200
                                    @else bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                    @endif">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 dark:text-gray-400 text-center py-4">No orders yet</p>
                    @endforelse
                </div>
                <a href="{{ route('admin.orders.index') }}" class="block text-center mt-4 text-purple-600 dark:text-purple-400 hover:text-purple-700 dark:hover:text-purple-300 font-semibold">
                    View All Orders →
                </a>
            </div>

            <!-- Low Stock Products -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold mb-4 text-gray-900 dark:text-white">Low Stock Alert</h2>
                <div class="space-y-3">
                    @forelse($lowStockProducts as $product)
                        <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-700 rounded">
                            <div>
                                <p class="font-semibold text-gray-800 dark:text-white">{{ $product->name }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $product->category->name }}</p>
                            </div>
                            <div class="text-right">
                                <span class="px-3 py-1 rounded-full text-sm font-semibold
                                    @if($product->stock_quantity === 0) bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                    @else bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200
                                    @endif">
                                    {{ $product->stock_quantity }} units
                                </span>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 dark:text-gray-400 text-center py-4">All products in stock</p>
                    @endforelse
                </div>
                <a href="{{ route('admin.inventory.index') }}?stock_status=low_stock" class="block text-center mt-4 text-purple-600 dark:text-purple-400 hover:text-purple-700 dark:hover:text-purple-300 font-semibold">
                    View Inventory →
                </a>
            </div>
        </div>
    </div>

{{-- Chart.js Scripts --}}
<script>
    const isDarkMode = document.documentElement.classList.contains('dark');
    const textColor = isDarkMode ? '#E5E7EB' : '#374151';
    const gridColor = isDarkMode ? '#374151' : '#E5E7EB';

    // Sales Line Chart
    const salesCtx = document.getElementById('salesChart').getContext('2d');
    const salesChart = new Chart(salesCtx, {
        type: 'line',
        data: {
            labels: @json($salesChartData['labels']),
            datasets: [{
                label: 'Daily Sales (£)',
                data: @json($salesChartData['data']),
                backgroundColor: 'rgba(147, 51, 234, 0.1)',
                borderColor: 'rgba(147, 51, 234, 1)',
                borderWidth: 3,
                tension: 0.4,
                fill: true,
                pointBackgroundColor: 'rgba(147, 51, 234, 1)',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 7,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        color: textColor,
                        font: { size: 14, weight: 'bold' }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    padding: 12,
                    displayColors: false,
                    callbacks: {
                        label: function(context) {
                            return 'Sales: £' + context.parsed.y.toFixed(2);
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        color: textColor,
                        callback: function(value) {
                            return '£' + value;
                        }
                    },
                    grid: { color: gridColor }
                },
                x: {
                    ticks: { color: textColor },
                    grid: { color: gridColor }
                }
            }
        }
    });

    // Order Status Donut Chart
    const orderStatusCtx = document.getElementById('orderStatusChart').getContext('2d');
    const orderStatusChart = new Chart(orderStatusCtx, {
        type: 'doughnut',
        data: {
            labels: @json($orderStatusData['labels']),
            datasets: [{
                data: @json($orderStatusData['data']),
                backgroundColor: @json($orderStatusData['colors']),
                borderWidth: 0,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom',
                    labels: {
                        color: textColor,
                        padding: 15,
                        font: { size: 12 }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    padding: 12,
                }
            }
        }
    });
</script>
</body>
</html>
