@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    {{-- Back Button --}}
    <a href="{{ route('orders.index') }}" class="inline-flex items-center text-purple-600 dark:text-purple-400 hover:text-purple-700 dark:hover:text-purple-500 font-semibold mb-6">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
        </svg>
        Back to Orders
    </a>

    <h1 class="text-3xl font-bold mb-8 text-gray-900 dark:text-white">Track Order</h1>

    {{-- Order Info Card --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-8">
        <div class="flex flex-wrap items-center justify-between mb-4 gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $order->order_number }}</h2>
                <p class="text-gray-600 dark:text-gray-400">Placed on {{ $order->created_at->format('M d, Y g:i A') }}</p>
            </div>
            <div class="text-right">
                <span class="px-4 py-2 text-sm font-semibold rounded-full inline-block
                    @if($order->status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                    @elseif($order->status === 'processing') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                    @elseif($order->status === 'shipped') bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200
                    @elseif($order->status === 'delivered') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                    @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                    @endif">
                    {{ ucfirst($order->status) }}
                </span>
                @if($order->status !== 'cancelled' && $order->status !== 'delivered')
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                        <span class="font-semibold">Estimated Delivery:</span><br>
                        {{ $estimatedDelivery }}
                    </p>
                @endif
            </div>
        </div>
    </div>

    {{-- Progress Timeline --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-8">
        <h3 class="text-xl font-bold mb-6 text-gray-900 dark:text-white">Order Progress</h3>

        <div class="relative">
            @foreach($timeline as $index => $event)
                <div class="flex items-start mb-8 last:mb-0">
                    {{-- Timeline Line --}}
                    @if(!$loop->last)
                        <div class="absolute left-4 top-10 bottom-0 w-0.5 {{ $event['completed'] ? 'bg-purple-600 dark:bg-purple-500' : 'bg-gray-300 dark:bg-gray-600' }}" style="height: calc(100% - {{ $index * 8 }}rem);"></div>
                    @endif

                    {{-- Status Icon --}}
                    <div class="relative z-10 flex-shrink-0">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center
                            {{ $event['completed'] ? 'bg-purple-600 dark:bg-purple-500' : 'bg-gray-300 dark:bg-gray-600' }}">
                            @if($event['completed'])
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            @else
                                <div class="w-3 h-3 bg-white rounded-full"></div>
                            @endif
                        </div>
                    </div>

                    {{-- Event Details --}}
                    <div class="ml-4 flex-1">
                        <h4 class="text-lg font-semibold {{ $event['completed'] ? 'text-gray-900 dark:text-white' : 'text-gray-500 dark:text-gray-400' }}">
                            {{ $event['title'] }}
                        </h4>
                        <p class="text-sm {{ $event['completed'] ? 'text-gray-600 dark:text-gray-300' : 'text-gray-400 dark:text-gray-500' }}">
                            {{ $event['description'] }}
                        </p>
                        @if($event['date'])
                            <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">
                                {{ $event['date']->format('M d, Y g:i A') }}
                            </p>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Shipping Information --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-8">
        <h3 class="text-xl font-bold mb-4 text-gray-900 dark:text-white">Shipping Information</h3>
        <div class="grid md:grid-cols-2 gap-6">
            <div>
                <p class="text-sm font-semibold text-gray-600 dark:text-gray-400 mb-2">Delivery Address</p>
                <p class="text-gray-900 dark:text-white">{{ $order->shipping_address }}</p>
                <p class="text-gray-900 dark:text-white">{{ $order->shipping_city }}, {{ $order->shipping_postcode }}</p>
            </div>
            <div>
                <p class="text-sm font-semibold text-gray-600 dark:text-gray-400 mb-2">Contact Information</p>
                <p class="text-gray-900 dark:text-white">{{ $order->user->name }}</p>
                <p class="text-gray-900 dark:text-white">{{ $order->contact_phone }}</p>
                <p class="text-gray-900 dark:text-white">{{ $order->user->email }}</p>
            </div>
        </div>
    </div>

    {{-- Order Items --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <h3 class="text-xl font-bold mb-4 text-gray-900 dark:text-white">Order Items</h3>
        <div class="space-y-4">
            @foreach($order->items as $item)
                <div class="flex items-center gap-4 pb-4 border-b border-gray-200 dark:border-gray-700 last:border-b-0 last:pb-0">
                    @if($item->product->image)
                        <img src="{{ asset('images/products/' . $item->product->image) }}"
                             alt="{{ $item->product->name }}"
                             class="w-20 h-20 object-cover rounded">
                    @else
                        <div class="w-20 h-20 bg-gray-200 dark:bg-gray-700 rounded flex items-center justify-center text-3xl">
                            📦
                        </div>
                    @endif
                    <div class="flex-1">
                        <h4 class="font-semibold text-gray-900 dark:text-white">{{ $item->product->name }}</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Quantity: {{ $item->quantity }}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold text-gray-900 dark:text-white">£{{ number_format($item->unit_price, 2) }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Total: £{{ number_format($item->subtotal, 2) }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Order Total --}}
        <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
            <div class="flex justify-between items-center">
                <span class="text-xl font-bold text-gray-900 dark:text-white">Order Total:</span>
                <span class="text-2xl font-bold text-purple-600 dark:text-purple-400">£{{ number_format($order->total_amount, 2) }}</span>
            </div>
        </div>
    </div>

    {{-- Help Section --}}
    <div class="bg-blue-50 dark:bg-blue-900 border border-blue-200 dark:border-blue-700 rounded-lg p-6 mt-8">
        <h3 class="font-semibold text-blue-900 dark:text-blue-200 mb-2">Need Help?</h3>
        <p class="text-sm text-blue-800 dark:text-blue-300 mb-4">
            If you have any questions about your order, please contact our customer support team.
        </p>
        <a href="mailto:support@techverse.com" class="inline-flex items-center text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 font-semibold">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
            </svg>
            support@techverse.com
        </a>
    </div>
</div>
@endsection
