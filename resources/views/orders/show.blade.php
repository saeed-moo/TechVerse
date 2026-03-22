<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details - TechVerse</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <nav class="bg-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <a href="{{ route('home') }}" class="text-2xl font-bold">
                    <span class="text-purple-600">Tech</span><span class="text-gray-800">Verse</span>
                </a>
                <a href="{{ route('orders.index') }}" class="text-gray-700 hover:text-purple-600">← Back to Orders</a>
            </div>
        </div>
    </nav>

    <div class="max-w-4xl mx-auto px-4 py-8">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Order #{{ $order->order_number }}</h1>
                    <p class="text-gray-600">Placed on {{ $order->created_at->format('d M Y, H:i') }}</p>
                </div>
                <span class="px-4 py-2 rounded-full text-sm font-semibold
                    @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                    @elseif($order->status === 'processing') bg-blue-100 text-blue-800
                    @elseif($order->status === 'shipped') bg-purple-100 text-purple-800
                    @elseif($order->status === 'delivered') bg-green-100 text-green-800
                    @else bg-red-100 text-red-800
                    @endif">
                    {{ ucfirst($order->status) }}
                </span>
            </div>

            <div class="grid grid-cols-2 gap-6 mb-6">
                <div>
                    <h3 class="font-bold text-gray-800 mb-2">Shipping Address</h3>
                    <p class="text-gray-600">{{ $order->shipping_address }}</p>
                    <p class="text-gray-600">{{ $order->shipping_city }}, {{ $order->shipping_postcode }}</p>
                    <p class="text-gray-600 mt-2">Phone: {{ $order->contact_phone }}</p>
                </div>
                <div>
                    <h3 class="font-bold text-gray-800 mb-2">Order Summary</h3>
                    <p class="text-gray-600">Items: {{ $order->orderItems->count() }}</p>
                    <p class="text-2xl font-bold text-purple-600 mt-2">£{{ number_format($order->total_amount, 2) }}</p>
                </div>
            </div>

            <h3 class="font-bold text-gray-800 mb-4">Order Items</h3>
            <div class="space-y-4">
                @foreach($order->orderItems as $item)
                    <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-lg">
                        <div class="w-20 h-20 bg-gray-200 rounded flex items-center justify-center">
                            <span class="text-3xl">📱</span>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-800">{{ $item->product->name }}</h4>
                            <p class="text-sm text-gray-600">Quantity: {{ $item->quantity }}</p>
                            <p class="text-sm text-gray-600">Unit Price: £{{ number_format($item->unit_price, 2) }}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-gray-800">£{{ number_format($item->subtotal, 2) }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</body>
</html>
