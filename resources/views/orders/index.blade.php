@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8 text-gray-900 dark:text-white">My Orders</h1>

    @if($orders->isEmpty())
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-12 text-center">
            <svg class="w-24 h-24 mx-auto text-gray-400 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
            </svg>
            <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-2">No orders yet</h2>
            <p class="text-gray-600 dark:text-gray-400 mb-6">Start shopping to see your orders here!</p>
            <a href="{{ route('home') }}" class="inline-block bg-purple-600 dark:bg-purple-500 text-white px-6 py-3 rounded-lg hover:bg-purple-700 dark:hover:bg-purple-600 font-semibold transition">
                Start Shopping
            </a>
        </div>
    @else
        <div class="space-y-4">
            @foreach($orders as $order)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
                    <div class="p-6">
                        {{-- Order Header --}}
                        <div class="flex flex-wrap items-center justify-between mb-4 gap-4">
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Order {{ $order->order_number }}</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Placed on {{ $order->created_at->format('M d, Y') }}</p>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="px-3 py-1 text-sm font-semibold rounded-full
                                    @if($order->status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                    @elseif($order->status === 'processing') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                    @elseif($order->status === 'shipped') bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200
                                    @elseif($order->status === 'delivered') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                    @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                    @endif">
                                    {{ ucfirst($order->status) }}
                                </span>
                                <span class="text-xl font-bold text-purple-600 dark:text-purple-400">£{{ number_format($order->total_amount, 2) }}</span>
                            </div>
                        </div>

                        {{-- Order Items Preview --}}
                        <div class="mb-4">
                            <div class="flex flex-wrap gap-2">
                                @foreach($order->items->take(3) as $item)
                                    <div class="text-sm text-gray-600 dark:text-gray-400">
                                        {{ $item->product->name }}
                                        @if(!$loop->last), @endif
                                    </div>
                                @endforeach
                                @if($order->items->count() > 3)
                                    <div class="text-sm text-gray-500 dark:text-gray-500">
                                        +{{ $order->items->count() - 3 }} more
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="flex flex-wrap gap-3">
                            <a href="{{ route('orders.show', $order) }}" class="px-4 py-2 bg-purple-600 dark:bg-purple-500 text-white rounded-lg hover:bg-purple-700 dark:hover:bg-purple-600 font-semibold text-sm transition">
                                View Details
                            </a>
                            @if(in_array($order->status, ['pending', 'processing', 'shipped']))
                                <a href="{{ route('orders.track', $order) }}" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 font-semibold text-sm transition">
                                    Track Order
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-6">
            {{ $orders->links() }}
        </div>
    @endif
</div>
@endsection
