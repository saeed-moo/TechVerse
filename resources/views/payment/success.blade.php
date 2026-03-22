<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Successful - TechVerse</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 dark:bg-gray-900">
    <div class="min-h-screen flex items-center justify-center px-4">
        <div class="max-w-md w-full">
            {{-- Success Animation --}}
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-24 h-24 bg-green-100 dark:bg-green-900 rounded-full mb-4">
                    <svg class="w-16 h-16 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Payment Successful!</h1>
                <p class="text-gray-600 dark:text-gray-400">Thank you for your order</p>
            </div>

            {{-- Order Details Card --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 mb-6">
                <div class="border-b border-gray-200 dark:border-gray-700 pb-4 mb-4">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Order Confirmation</h2>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Order Number:</span>
                            <span class="font-semibold text-gray-900 dark:text-white">{{ $order->order_number }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Order Date:</span>
                            <span class="font-semibold text-gray-900 dark:text-white">{{ $order->created_at->format('M d, Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Payment Status:</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                Paid
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Order Items --}}
                <div class="mb-4">
                    <h3 class="font-semibold text-gray-900 dark:text-white mb-3">Order Items:</h3>
                    <div class="space-y-3">
                        @foreach($order->items as $item)
                            <div class="flex justify-between text-sm">
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">{{ $item->product->name }}</p>
                                    <p class="text-gray-500 dark:text-gray-400">Qty: {{ $item->quantity }}</p>
                                </div>
                                <p class="font-semibold text-gray-900 dark:text-white">£{{ number_format($item->subtotal, 2) }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Total --}}
                <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                    <div class="flex justify-between">
                        <span class="text-lg font-bold text-gray-900 dark:text-white">Total Paid:</span>
                        <span class="text-lg font-bold text-purple-600 dark:text-purple-400">£{{ number_format($order->total_amount, 2) }}</span>
                    </div>
                </div>
            </div>

            {{-- Shipping Information --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 mb-6">
                <h3 class="font-semibold text-gray-900 dark:text-white mb-3">Shipping Address:</h3>
                <div class="text-sm text-gray-600 dark:text-gray-400">
                    <p>{{ $order->shipping_address }}</p>
                    <p>{{ $order->shipping_city }}, {{ $order->shipping_postcode }}</p>
                    <p class="mt-2">Contact: {{ $order->contact_phone }}</p>
                </div>
            </div>

            {{-- Email Confirmation Notice --}}
            <div class="bg-blue-50 dark:bg-blue-900 border border-blue-200 dark:border-blue-700 rounded-lg p-4 mb-6">
                <div class="flex">
                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    <div>
                        <p class="text-sm font-medium text-blue-800 dark:text-blue-200">Order confirmation sent!</p>
                        <p class="text-sm text-blue-700 dark:text-blue-300 mt-1">We've sent an email confirmation to {{ auth()->user()->email }}</p>
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <a href="{{ route('orders.track', $order) }}" class="bg-purple-600 dark:bg-purple-500 text-white text-center py-3 rounded-lg hover:bg-purple-700 dark:hover:bg-purple-600 font-semibold transition flex items-center justify-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
        </svg>
        Track Order
    </a>
    <a href="{{ route('orders.show', $order) }}" class="bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white text-center py-3 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 font-semibold transition">
        View Details
    </a>
    <a href="{{ route('home') }}" class="col-span-full text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white text-center py-2 font-medium transition">
        Continue Shopping
    </a>
</div>
        </div>
    </div>
</body>
</html>
