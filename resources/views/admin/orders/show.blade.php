<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order #{{ $order->order_number }} - TechVerse Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 dark:bg-gray-900">
    <nav class="bg-white dark:bg-gray-800 shadow-md">
        <div class="max-w-7xl mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <a href="{{ route('home') }}" class="text-2xl font-bold">
                    <span class="text-purple-600 dark:text-purple-400">Tech</span><span class="text-gray-800 dark:text-white">Verse</span> <span class="text-sm text-gray-500 dark:text-gray-400">Admin</span>
                </a>
                <div class="space-x-6">
                    <a href="{{ route('admin.dashboard') }}" class="text-gray-700 dark:text-gray-300 hover:text-purple-600 dark:hover:text-purple-400">Dashboard</a>
                    <a href="{{ route('admin.inventory.index') }}" class="text-gray-700 dark:text-gray-300 hover:text-purple-600 dark:hover:text-purple-400">Inventory</a>
                    <a href="{{ route('admin.orders.index') }}" class="text-purple-600 dark:text-purple-400 font-semibold">Orders</a>
                    <a href="{{ route('home') }}" class="text-gray-700 dark:text-gray-300 hover:text-purple-600 dark:hover:text-purple-400">View Site</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 py-8">
        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-600 text-green-700 dark:text-green-200 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        {{-- Back Button --}}
        <div class="mb-6">
            <a href="{{ route('admin.orders.index') }}" class="text-purple-600 dark:text-purple-400 hover:text-purple-700 dark:hover:text-purple-500 font-semibold">
                ← Back to Orders
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Order Details --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Order Header --}}
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Order #{{ $order->order_number }}</h1>
                        <span class="px-3 py-1 text-sm font-semibold rounded-full
                            @if($order->status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                            @elseif($order->status === 'processing') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                            @elseif($order->status === 'shipped') bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200
                            @elseif($order->status === 'delivered') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                            @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                            @endif">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <p class="text-gray-500 dark:text-gray-400">Order Date</p>
                            <p class="font-semibold text-gray-900 dark:text-white">{{ $order->created_at->format('F j, Y g:i A') }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500 dark:text-gray-400">Total Amount</p>
                            <p class="font-semibold text-purple-600 dark:text-purple-400 text-xl">£{{ number_format($order->total_amount, 2) }}</p>
                        </div>
                    </div>
                </div>

                {{-- Order Items --}}
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Order Items</h2>
                    <div class="space-y-4">
                        @foreach($order->items as $item)
                            <div class="flex items-center justify-between border-b border-gray-200 dark:border-gray-700 pb-4 last:border-b-0 last:pb-0">
                                <div class="flex items-center gap-4">
                                    @if($item->product->image)
                                        <img src="{{ asset('images/products/' . $item->product->image) }}"
                                             alt="{{ $item->product->name }}"
                                             class="w-16 h-16 object-cover rounded">
                                    @else
                                        <div class="w-16 h-16 bg-gray-200 dark:bg-gray-700 rounded flex items-center justify-center text-2xl">
                                            📱
                                        </div>
                                    @endif
                                    <div>
                                        <p class="font-semibold text-gray-900 dark:text-white">{{ $item->product->name }}</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Quantity: {{ $item->quantity }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-semibold text-gray-900 dark:text-white">£{{ number_format($item->unit_price, 2) }}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Subtotal: £{{ number_format($item->subtotal, 2) }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Customer Information --}}
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Customer Information</h2>
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Customer Name</p>
                            <p class="font-semibold text-gray-900 dark:text-white">{{ $order->user->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Email</p>
                            <p class="font-semibold text-gray-900 dark:text-white">{{ $order->user->email }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Contact Phone</p>
                            <p class="font-semibold text-gray-900 dark:text-white">{{ $order->contact_phone }}</p>
                        </div>
                    </div>
                </div>

                {{-- Shipping Information --}}
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Shipping Address</h2>
                    <div class="text-gray-700 dark:text-gray-300">
                        <p>{{ $order->shipping_address }}</p>
                        <p>{{ $order->shipping_city }}, {{ $order->shipping_postcode }}</p>
                    </div>
                </div>
            </div>

            {{-- Update Status Sidebar --}}
            <div class="lg:col-span-1">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 sticky top-4">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Update Order Status</h2>

                    <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                Change Status
                            </label>
                            <select
                                name="status"
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-600 dark:bg-gray-700 dark:text-white"
                                required
                            >
                                <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
                                <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                                <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                                <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>

                        <div class="bg-blue-50 dark:bg-blue-900 border border-blue-200 dark:border-blue-700 rounded-lg p-4 mb-4">
                            <p class="text-sm text-blue-800 dark:text-blue-200">
                                📧 Customer will be notified via email when status is updated.
                            </p>
                        </div>

                        <button
                            type="submit"
                            class="w-full bg-purple-600 dark:bg-purple-500 text-white py-2 px-4 rounded-lg hover:bg-purple-700 dark:hover:bg-purple-600 font-semibold transition"
                        >
                            Update Status & Notify Customer
                        </button>
                    </form>

                    {{-- Status Timeline --}}
                    <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Status Timeline</h3>
                        <div class="space-y-2 text-sm">
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 rounded-full {{ $order->status === 'pending' ? 'bg-yellow-500' : 'bg-gray-300 dark:bg-gray-600' }}"></div>
                                <span class="text-gray-600 dark:text-gray-400">Pending</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 rounded-full {{ $order->status === 'processing' ? 'bg-blue-500' : 'bg-gray-300 dark:bg-gray-600' }}"></div>
                                <span class="text-gray-600 dark:text-gray-400">Processing</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 rounded-full {{ $order->status === 'shipped' ? 'bg-purple-500' : 'bg-gray-300 dark:bg-gray-600' }}"></div>
                                <span class="text-gray-600 dark:text-gray-400">Shipped</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 rounded-full {{ $order->status === 'delivered' ? 'bg-green-500' : 'bg-gray-300 dark:bg-gray-600' }}"></div>
                                <span class="text-gray-600 dark:text-gray-400">Delivered</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
