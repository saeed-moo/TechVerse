<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details - TechVerse</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 dark:bg-gray-900">
    <nav class="bg-white dark:bg-gray-800 shadow-md">
        <div class="max-w-7xl mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <a href="{{ route('home') }}" class="text-2xl font-bold">
                    <span class="text-purple-600 dark:text-purple-400">Tech</span><span class="text-gray-800 dark:text-white">Verse</span>
                </a>
                <a href="{{ route('orders.index') }}" class="text-gray-700 dark:text-gray-300 hover:text-purple-600 dark:hover:text-purple-400">← Back to Orders</a>
            </div>
        </div>
    </nav>

    <div class="max-w-4xl mx-auto px-4 py-8">
        @if(session('success'))
            <div class="bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-600 text-green-700 dark:text-green-200 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6">
            <div class="flex flex-wrap justify-between items-start mb-6 gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Order #{{ $order->order_number }}</h1>
                    <p class="text-gray-600 dark:text-gray-400">Placed on {{ $order->created_at->format('d M Y, H:i') }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <span class="px-4 py-2 rounded-full text-sm font-semibold
                        @if($order->status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                        @elseif($order->status === 'processing') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                        @elseif($order->status === 'shipped') bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200
                        @elseif($order->status === 'delivered') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                        @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                        @endif">
                        {{ ucfirst($order->status) }}
                    </span>

                    {{-- Track Order Button --}}
                    @if(in_array($order->status, ['pending', 'processing', 'shipped']))
                        <a href="{{ route('orders.track', $order) }}" class="inline-flex items-center gap-2 bg-purple-600 dark:bg-purple-500 text-white px-4 py-2 rounded-lg hover:bg-purple-700 dark:hover:bg-purple-600 font-semibold text-sm transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                            </svg>
                            Track Order
                        </a>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <h3 class="font-bold text-gray-800 dark:text-white mb-2">Shipping Address</h3>
                    <p class="text-gray-600 dark:text-gray-400">{{ $order->shipping_address }}</p>
                    <p class="text-gray-600 dark:text-gray-400">{{ $order->shipping_city }}, {{ $order->shipping_postcode }}</p>
                    <p class="text-gray-600 dark:text-gray-400 mt-2">Phone: {{ $order->contact_phone }}</p>
                </div>
                <div>
                    <h3 class="font-bold text-gray-800 dark:text-white mb-2">Order Summary</h3>
                    <p class="text-gray-600 dark:text-gray-400">Items: {{ $order->orderItems->count() }}</p>
                    <p class="text-2xl font-bold text-purple-600 dark:text-purple-400 mt-2">£{{ number_format($order->total_amount, 2) }}</p>
                </div>
            </div>

            <h3 class="font-bold text-gray-800 dark:text-white mb-4">Order Items</h3>
            <div class="space-y-4">
                @foreach($order->orderItems as $item)
                    <div class="flex items-center gap-4 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        @if($item->product->image)
                            <img src="{{ asset('images/products/' . $item->product->image) }}"
                                 alt="{{ $item->product->name }}"
                                 class="w-20 h-20 object-cover rounded">
                        @else
                            <div class="w-20 h-20 bg-gray-200 dark:bg-gray-600 rounded flex items-center justify-center">
                                <span class="text-3xl">📱</span>
                            </div>
                        @endif
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-800 dark:text-white">{{ $item->product->name }}</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Quantity: {{ $item->quantity }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Unit Price: £{{ number_format($item->unit_price, 2) }}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-gray-800 dark:text-white">£{{ number_format($item->subtotal, 2) }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</body>
</html>
