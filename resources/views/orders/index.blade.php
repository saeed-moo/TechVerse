<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders - TechVerse</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <nav class="bg-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <a href="{{ route('home') }}" class="text-2xl font-bold">
                    <span class="text-purple-600">Tech</span><span class="text-gray-800">Verse</span>
                </a>
                <div class="space-x-6">
                    <a href="{{ route('home') }}" class="text-gray-700 hover:text-purple-600">Home</a>
                    <a href="{{ route('products.index') }}" class="text-gray-700 hover:text-purple-600">Products</a>
                    <a href="{{ route('basket.index') }}" class="text-gray-700 hover:text-purple-600">Basket</a>
                    <a href="{{ route('orders.index') }}" class="text-purple-600 font-semibold">Orders</a>
                    <a href="{{ route('profile.index') }}" class="text-gray-700 hover:text-purple-600">Profile</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-6xl mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-6">My Orders</h1>

        @if($orders->isEmpty())
            <div class="bg-white rounded-lg shadow-md p-12 text-center">
                <div class="text-6xl mb-4">📦</div>
                <h3 class="text-xl font-semibold text-gray-800 mb-2">No orders yet</h3>
                <p class="text-gray-600 mb-4">Start shopping to place your first order</p>
                <a href="{{ route('products.index') }}" class="inline-block bg-purple-600 text-white px-6 py-2 rounded-lg hover:bg-purple-700">
                    Browse Products
                </a>
            </div>
        @else
            <div class="space-y-4">
                @foreach($orders as $order)
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="text-xl font-bold text-gray-800">Order #{{ $order->order_number }}</h3>
                                <p class="text-sm text-gray-600">Placed on {{ $order->created_at->format('d M Y, H:i') }}</p>
                            </div>
                            <div class="text-right">
                                <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold
                                    @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($order->status === 'processing') bg-blue-100 text-blue-800
                                    @elseif($order->status === 'shipped') bg-purple-100 text-purple-800
                                    @elseif($order->status === 'delivered') bg-green-100 text-green-800
                                    @else bg-red-100 text-red-800
                                    @endif">
                                    {{ ucfirst($order->status) }}
                                </span>
                                <p class="text-xl font-bold text-purple-600 mt-2">£{{ number_format($order->total_amount, 2) }}</p>
                            </div>
                        </div>
                        <a href="{{ route('orders.show', $order) }}" class="inline-block bg-purple-600 text-white px-6 py-2 rounded-lg hover:bg-purple-700">
                            View Details
                        </a>
                    </div>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</body>
</html>
