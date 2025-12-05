@extends('layouts.app')

@section('title', $product->name . ' - TechVerse')

@section('content')
<!-- Breadcrumb -->
<div class="bg-white dark:bg-gray-800 border-b dark:border-gray-700 transition-colors duration-200">
    <div class="max-w-7xl mx-auto px-4 py-4">
        <nav class="text-sm">
            <a href="{{ route('home') }}" class="text-purple-600 dark:text-purple-400 hover:text-purple-700 dark:hover:text-purple-300">Home</a>
            <span class="mx-2 text-gray-600 dark:text-gray-400">/</span>
            <a href="{{ route('products.index') }}" class="text-purple-600 dark:text-purple-400 hover:text-purple-700 dark:hover:text-purple-300">Products</a>
            <span class="mx-2 text-gray-600 dark:text-gray-400">/</span>
            <span class="text-gray-600 dark:text-gray-400">{{ $product->name }}</span>
        </nav>
    </div>
</div>

<!-- Product Details -->
<div class="max-w-7xl mx-auto px-4 py-12">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
        <!-- Product Image -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-8 transition-colors duration-200">
            @if($product->image && file_exists(public_path('images/products/' . $product->image)))
    <img src="{{ asset('images/products/' . $product->image) }}"
                     alt="{{ $product->name }}"
                     class="w-full h-auto rounded-lg">
            @else
                <div class="w-full h-96 bg-gray-200 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                    <span class="text-9xl">📱</span>
                </div>
            @endif
        </div>

        <!-- Product Info -->
        <div>
            <div class="mb-4">
                <span class="text-sm font-bold text-purple-600 dark:text-purple-400 uppercase">{{ $product->category->name }}</span>
            </div>

            <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-4">{{ $product->name }}</h1>

            <!-- Stock Status -->
            <div class="mb-6">
                @if($product->stock_status === 'in_stock')
                    <span class="inline-block bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 text-sm font-semibold px-4 py-2 rounded-full">
                        ✓ In Stock
                    </span>
                @elseif($product->stock_status === 'low_stock')
                    <span class="inline-block bg-orange-100 dark:bg-orange-900 text-orange-800 dark:text-orange-200 text-sm font-semibold px-4 py-2 rounded-full">
                        ⚠ Low Stock
                    </span>
                @else
                    <span class="inline-block bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200 text-sm font-semibold px-4 py-2 rounded-full">
                        ✗ Out of Stock
                    </span>
                @endif
            </div>

            <!-- Price -->
            <div class="mb-8">
                <span class="text-5xl font-bold text-purple-600 dark:text-purple-400">£{{ number_format($product->price, 2) }}</span>
            </div>

            <!-- Description -->
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Description</h2>
                <p class="text-gray-700 dark:text-gray-300 leading-relaxed">{{ $product->description }}</p>
            </div>

            <!-- Add to Basket Button -->
            @if($product->stock_status === 'in_stock')
                @auth
                    <!-- User is logged in - show the form -->
                    <form method="POST" action="{{ route('basket.add', $product) }}" class="mb-8">
                        @csrf
                        <div class="flex items-center space-x-4 mb-4">
                            <label for="quantity" class="font-semibold text-gray-700 dark:text-gray-300">Quantity:</label>
                            <input type="number"
                                   name="quantity"
                                   id="quantity"
                                   value="1"
                                   min="1"
                                   max="{{ $product->stock_quantity }}"
                                   class="w-20 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600 dark:bg-gray-700 dark:text-white">
                        </div>
                        <button type="submit"
                                class="w-full bg-purple-600 dark:bg-purple-500 text-white px-8 py-4 rounded-lg font-bold text-lg hover:bg-purple-700 dark:hover:bg-purple-600 transition shadow-lg">
                            Add to Basket
                        </button>
                    </form>
                @else
                    <!-- User is NOT logged in - show login button -->
                    <div class="mb-8">
                        <a href="{{ route('login') }}"
                           class="block w-full bg-purple-600 dark:bg-purple-500 text-white px-8 py-4 rounded-lg font-bold text-lg hover:bg-purple-700 dark:hover:bg-purple-600 transition shadow-lg text-center">
                            Login to Add to Basket
                        </a>
                        <p class="text-center text-sm text-gray-600 dark:text-gray-400 mt-3">
                            Don't have an account?
                            <a href="{{ route('register') }}" class="text-purple-600 dark:text-purple-400 hover:text-purple-700 dark:hover:text-purple-300 font-semibold">Sign up here</a>
                        </p>
                    </div>
                @endauth
            @else
                <button disabled
                        class="w-full bg-gray-400 dark:bg-gray-600 text-white px-8 py-4 rounded-lg font-bold text-lg cursor-not-allowed">
                    Out of Stock
                </button>
            @endif

            <!-- Product Specs -->
            <div class="bg-gray-50 dark:bg-gray-800 rounded-xl p-6 transition-colors duration-200">
                <h3 class="font-bold text-gray-900 dark:text-white mb-3">Product Information</h3>
                <ul class="space-y-2 text-sm text-gray-600 dark:text-gray-300">
                    <li><strong>Category:</strong> {{ $product->category->name }}</li>
                    <li><strong>Stock Quantity:</strong> {{ $product->stock_quantity }} units</li>
                    <li><strong>Status:</strong> {{ ucfirst(str_replace('_', ' ', $product->stock_status)) }}</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
        <div class="mt-16">
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-8">Related Products</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($relatedProducts as $relatedProduct)
                    <a href="{{ route('products.show', $relatedProduct->slug) }}"
                       class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 hover:-translate-y-3 overflow-hidden group border border-gray-100 dark:border-gray-700">
                        <div class="relative h-48 bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                            @if($relatedProduct->image && file_exists(public_path('images/' . $relatedProduct->image)))
                                <img src="{{ asset('images/' . $relatedProduct->image) }}"
                                     alt="{{ $relatedProduct->name }}"
                                     class="w-full h-full object-cover">
                            @else
                                <span class="text-5xl">📱</span>
                            @endif
                        </div>
                        <div class="p-4">
                            <p class="text-xs text-purple-600 dark:text-purple-400 font-bold mb-1">{{ $relatedProduct->category->name }}</p>
                            <h3 class="font-bold text-gray-800 dark:text-white mb-2">{{ $relatedProduct->name }}</h3>
                            <p class="text-xl font-bold text-purple-600 dark:text-purple-400">£{{ number_format($relatedProduct->price, 2) }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
