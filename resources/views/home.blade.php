@extends('layouts.app')

@section('title', 'TechVerse - Your Universe of Technology')

@section('content')
<!-- Hero Section -->
<div class="bg-gradient-to-r from-purple-600 via-purple-700 to-purple-800 dark:from-purple-700 dark:via-purple-800 dark:to-purple-900 text-white py-20 transition-colors duration-200">
    <div class="max-w-7xl mx-auto px-4 text-center">
        <h1 class="text-5xl font-bold mb-4">Your Universe of Technology</h1>
        <p class="text-xl mb-8 opacity-90">Premium technology products for students and professionals</p>
        <a href="{{ route('products.index') }}" class="bg-white text-purple-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 inline-block transition transform hover:scale-105 shadow-lg">
            Shop Now
        </a>
    </div>
</div>

<!-- Categories with Gradient Icons -->
<div class="bg-white dark:bg-gray-900 py-16 border-b dark:border-gray-800 transition-colors duration-200">
    <div class="max-w-7xl mx-auto px-4">
        <h2 class="text-3xl font-bold text-center mb-12 text-gray-800 dark:text-white">Shop by Category</h2>

        <!-- Horizontal Scrollable Categories -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6">
                @php
                    $gradientColors = [
                        'from-blue-400 to-blue-600',
                        'from-green-400 to-green-600',
                        'from-purple-400 to-purple-600',
                        'from-red-400 to-red-600',
                        'from-orange-400 to-orange-600',
                        'from-indigo-400 to-indigo-600',
                    ];
                @endphp

                @foreach($categories as $index => $category)
                    <a href="{{ route('products.index', ['category' => $category->slug]) }}"
                       class="flex-shrink-0 w-48 bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg cursor-pointer transition-all duration-300 hover:-translate-y-3 hover:shadow-2xl group border border-gray-100 dark:border-gray-700">
                        <div class="flex flex-col items-center">
                            <!-- Gradient Icon Box -->
                            <div class="bg-gradient-to-br {{ $gradientColors[$index % 6] }} p-5 rounded-2xl mb-4 shadow-lg transition-transform duration-300 group-hover:scale-110">
                                @if($category->icon && view()->exists('components.icons.' . $category->icon))
                                    @include('components.icons.' . $category->icon, ['attributes' => 'class="w-12 h-12 text-white"'])
                                @else
                                    <!-- Fallback icon -->
                                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" stroke-width="2">
                                        <rect x="2" y="2" width="20" height="20" rx="2" ry="2"></rect>
                                    </svg>
                                @endif
                            </div>
                            <h3 class="font-bold text-gray-800 dark:text-white text-center text-sm leading-tight">
                                {{ $category->name }}
                            </h3>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Featured Products -->
<div class="bg-gray-50 dark:bg-gray-800 py-16 transition-colors duration-200">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex justify-between items-center mb-12">
            <h2 class="text-3xl font-bold text-gray-800 dark:text-white">Featured Products</h2>
            <a href="{{ route('products.index') }}" class="text-purple-600 dark:text-purple-400 font-semibold hover:text-purple-700 dark:hover:text-purple-300 transition">
                View All →
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($featuredProducts as $product)
                <div class="bg-white dark:bg-gray-700 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 hover:-translate-y-3 overflow-hidden group border border-gray-100 dark:border-gray-600">
                    <!-- Product Image -->
                    <div class="relative h-64 bg-gray-100 dark:bg-gray-600 flex items-center justify-center overflow-hidden">
                       @if($product->image && file_exists(public_path('images/products/' . $product->image)))
                           <img src="{{ asset('images/products/' . $product->image) }}"
                                 alt="{{ $product->name }}"
                                 class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110">
                        @else
                            <!-- Placeholder icon -->
                            <svg class="w-24 h-24 text-purple-300 dark:text-purple-500" fill="currentColor" viewBox="0 0 24 24">
                                <rect x="5" y="2" width="14" height="20" rx="2" ry="2"></rect>
                                <line x1="12" y1="18" x2="12.01" y2="18" stroke="white" stroke-width="2"></line>
                            </svg>
                        @endif

                        <!-- In Stock Badge -->
                        @if($product->stock_status === 'in_stock')
                            <span class="absolute top-4 right-4 bg-green-500 text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-lg">
                                In Stock
                            </span>
                        @elseif($product->stock_status === 'low_stock')
                            <span class="absolute top-4 right-4 bg-orange-500 text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-lg">
                                Low Stock
                            </span>
                        @else
                            <span class="absolute top-4 right-4 bg-red-500 text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-lg">
                                Out of Stock
                            </span>
                        @endif

                        <!-- Quick View Button -->
                        <button class="absolute bottom-4 right-4 bg-white text-purple-600 p-3 rounded-full shadow-lg opacity-0 group-hover:opacity-100 transition-all transform group-hover:scale-110">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" stroke-width="2">
                                <path d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Product Info -->
                    <div class="p-5">
                        <p class="text-xs text-purple-600 dark:text-purple-400 font-bold mb-2 uppercase tracking-wide">{{ $product->category->name }}</p>
                        <h3 class="font-bold text-lg text-gray-800 dark:text-white mb-2 line-clamp-2">{{ $product->name }}</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-300 mb-4 line-clamp-2">{{ Str::limit($product->description, 80) }}</p>

                        <div class="flex items-center justify-between">
                            <span class="text-2xl font-bold text-purple-600 dark:text-purple-400">£{{ number_format($product->price, 2) }}</span>
                            @if(Route::has('products.show'))
                                <a href="{{ route('products.show', $product->slug) }}"
                                   class="bg-purple-600 dark:bg-purple-500 text-white px-4 py-2 rounded-lg hover:bg-purple-700 dark:hover:bg-purple-600 transition text-sm font-semibold shadow-md">
                                    View Details
                                </a>
                            @else
                                <button class="bg-purple-600 dark:bg-purple-500 text-white px-4 py-2 rounded-lg hover:bg-purple-700 dark:hover:bg-purple-600 transition text-sm font-semibold shadow-md">
                                    View Details
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
