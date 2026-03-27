@extends('layouts.app')

@section('title', 'My Wishlist - TechVerse')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    {{-- Page Header --}}
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">My Wishlist</h1>
            <p class="text-gray-600 dark:text-gray-400">
                {{ $products->count() }} {{ Str::plural('item', $products->count()) }} saved
            </p>
        </div>

        @if($products->count() > 0)
            <form action="{{ route('wishlist.clear') }}" method="POST" onsubmit="return confirm('Are you sure you want to clear your entire wishlist?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-500 font-semibold transition">
                    Clear All
                </button>
            </form>
        @endif
    </div>

    {{-- Wishlist Items --}}
    @if($products->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($products as $product)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md hover:shadow-xl transition-all duration-200 overflow-hidden relative">
                    {{-- Remove Button --}}
                    <form action="{{ route('wishlist.remove', $product) }}" method="POST" class="absolute top-2 right-2 z-10">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-white dark:bg-gray-700 p-2 rounded-full shadow-lg hover:bg-red-50 dark:hover:bg-red-900 transition group" title="Remove from wishlist">
                            <svg class="w-5 h-5 text-red-500 group-hover:text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </form>

                    {{-- Product Image --}}
                    <a href="{{ route('products.show', $product->slug) }}">
                        <div class="h-48 bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                            @if($product->image && file_exists(public_path('images/products/' . $product->image)))
                                <img src="{{ asset('images/products/' . $product->image) }}"
                                     alt="{{ $product->name }}"
                                     class="w-full h-full object-cover">
                            @else
                                <span class="text-6xl">📱</span>
                            @endif
                        </div>
                    </a>

                    {{-- Product Info --}}
                    <div class="p-4">
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1 uppercase">{{ $product->category->name }}</p>
                        <a href="{{ route('products.show', $product->slug) }}">
                            <h3 class="font-semibold text-gray-800 dark:text-white mb-2 hover:text-purple-600 dark:hover:text-purple-400 transition">
                                {{ $product->name }}
                            </h3>
                        </a>
                        <p class="text-sm text-gray-600 dark:text-gray-300 mb-3">{{ Str::limit($product->description, 60) }}</p>

                        <div class="flex items-center justify-between mb-3">
                            <span class="text-2xl font-bold text-purple-600 dark:text-purple-400">£{{ number_format($product->price, 2) }}</span>
                            @if($product->stock_quantity > 0)
                                <span class="text-xs text-green-600 dark:text-green-400 font-medium px-2 py-1 bg-green-100 dark:bg-green-900 rounded">In Stock</span>
                            @else
                                <span class="text-xs text-red-600 dark:text-red-400 font-medium px-2 py-1 bg-red-100 dark:bg-red-900 rounded">Out of Stock</span>
                            @endif
                        </div>

                        {{-- Actions --}}
                        @if($product->stock_quantity > 0)
                            <form action="{{ route('wishlist.move-to-basket', $product) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full bg-purple-600 dark:bg-purple-500 text-white py-2 rounded-lg hover:bg-purple-700 dark:hover:bg-purple-600 transition-colors duration-200 font-semibold">
                                    Move to Basket
                                </button>
                            </form>
                        @else
                            <button disabled class="w-full bg-gray-400 dark:bg-gray-600 text-white py-2 rounded-lg cursor-not-allowed">
                                Out of Stock
                            </button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @else
        {{-- Empty Wishlist --}}
        <div class="text-center py-16 bg-white dark:bg-gray-800 rounded-lg shadow-md">
            <svg class="mx-auto h-24 w-24 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
            </svg>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Your wishlist is empty</h2>
            <p class="text-gray-600 dark:text-gray-400 mb-6">Save your favorite products for later!</p>
            <a href="{{ route('products.index') }}" class="inline-block bg-purple-600 dark:bg-purple-500 text-white px-6 py-3 rounded-lg hover:bg-purple-700 dark:hover:bg-purple-600 transition-colors duration-200 font-semibold">
                Browse Products
            </a>
        </div>
    @endif
</div>
@endsection
