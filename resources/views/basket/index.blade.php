@extends('layouts.app')

@section('title', 'Shopping Basket - TechVerse')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6 text-gray-900 dark:text-white">Shopping Basket</h1>

    @if($basketItems->isEmpty())
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-12 text-center transition-colors duration-200">
            <div class="text-6xl mb-4">🛒</div>
            <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-2">Your basket is empty</h3>
            <p class="text-gray-600 dark:text-gray-400 mb-4">Start shopping to add items to your basket</p>
            <a href="{{ route('products.index') }}" class="inline-block bg-purple-600 dark:bg-purple-500 text-white px-6 py-2 rounded-lg hover:bg-purple-700 dark:hover:bg-purple-600 transition">
                Browse Products
            </a>
        </div>
    @else
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md transition-colors duration-200">
            @foreach($basketItems as $item)
                <div class="flex items-center gap-4 p-6 border-b dark:border-gray-700 last:border-b-0">
                    <div class="w-24 h-24 bg-gray-200 dark:bg-gray-700 rounded-lg flex items-center justify-center overflow-hidden">
    @if($item->product->image && file_exists(public_path('images/products/' . $item->product->image)))
        <img src="{{ asset('images/products/' . $item->product->image) }}"
             alt="{{ $item->product->name }}"
             class="w-full h-full object-cover">
    @else
        <span class="text-4xl">📱</span>
    @endif
</div>

                    <div class="flex-1">
                        <h3 class="font-semibold text-gray-800 dark:text-white mb-1">{{ $item->product->name }}</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $item->product->category->name }}</p>
                        <p class="text-lg font-bold text-purple-600 dark:text-purple-400 mt-2">£{{ number_format($item->product->price, 2) }}</p>
                    </div>

                    <div class="flex items-center gap-4">
                        <form action="{{ route('basket.update', $item) }}" method="POST" class="flex items-center gap-2">
                            @csrf
                            @method('PATCH')
                            <input type="number"
                                   name="quantity"
                                   value="{{ $item->quantity }}"
                                   min="1"
                                   max="{{ $item->product->stock_quantity }}"
                                   class="w-20 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-center dark:bg-gray-700 dark:text-white">
                            <button type="submit" class="bg-purple-600 dark:bg-purple-500 text-white px-4 py-2 rounded-lg hover:bg-purple-700 dark:hover:bg-purple-600 text-sm transition">
                                Update
                            </button>
                        </form>

                        <form action="{{ route('basket.destroy', $item) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </form>
                    </div>

                    <div class="text-right min-w-[100px]">
                        <p class="text-lg font-bold text-gray-800 dark:text-white">
                            £{{ number_format($item->product->price * $item->quantity, 2) }}
                        </p>
                    </div>
                </div>
            @endforeach

            <div class="p-6 bg-gray-50 dark:bg-gray-900 transition-colors duration-200">
                <div class="flex justify-between items-center mb-4">
                    <span class="text-xl font-semibold text-gray-900 dark:text-white">Total:</span>
                    <span class="text-3xl font-bold text-purple-600 dark:text-purple-400">£{{ number_format($total, 2) }}</span>
                </div>

                <div class="flex gap-4">
                    <a href="{{ route('products.index') }}" class="flex-1 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 py-3 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 text-center font-semibold transition">
                        Continue Shopping
                    </a>
                    <a href="{{ route('checkout.index') }}" class="flex-1 bg-purple-600 dark:bg-purple-500 text-white py-3 rounded-lg hover:bg-purple-700 dark:hover:bg-purple-600 text-center font-semibold transition">
                        Proceed to Checkout
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
