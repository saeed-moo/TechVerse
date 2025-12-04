@extends('layouts.app')

@section('title', 'Products - TechVerse')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6 text-gray-900 dark:text-white">Our Products</h1>

    {{-- SEARCH BAR --}}
    <div class="mb-8 bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md transition-colors duration-200">
        <form method="GET" action="{{ route('products.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                {{-- Search Input --}}
                <div class="md:col-span-2">
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Search products by name..."
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400"
                    >
                </div>

                {{-- Search Button --}}
                <div class="flex gap-2">
                    <button
                        type="submit"
                        class="flex-1 bg-purple-600 dark:bg-purple-500 text-white px-6 py-2 rounded-lg hover:bg-purple-700 dark:hover:bg-purple-600 transition font-semibold"
                    >
                        🔍 Search
                    </button>
                    @if(request('search'))
                        <a
                            href="{{ route('products.index') }}"
                            class="bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-200 px-4 py-2 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-500 transition font-semibold"
                        >
                            Clear
                        </a>
                    @endif
                </div>
            </div>

            {{-- Category Filter --}}
            <div class="flex gap-2 flex-wrap items-center">
                <span class="text-gray-700 dark:text-gray-300 font-semibold">Filter by Category:</span>
                <a href="{{ route('products.index') }}"
                   class="px-3 py-1 rounded-lg text-sm transition {{ !request('category') ? 'bg-purple-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-gray-600' }}">
                    All
                </a>
                @foreach($categories as $category)
                    <a href="{{ route('products.index', ['category' => $category->slug]) }}"
                       class="px-3 py-1 rounded-lg text-sm transition {{ request('category') == $category->slug ? 'bg-purple-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-gray-600' }}">
                        {{ $category->name }}
                    </a>
                @endforeach
            </div>
        </form>

        @if(request('search'))
            <p class="text-gray-600 dark:text-gray-400 mt-4">
                Showing results for: <strong>"{{ request('search') }}"</strong>
                ({{ $products->total() }} products found)
            </p>
        @endif
    </div>

    {{-- PRODUCTS GRID --}}
    @if($products->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($products as $product)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md hover:shadow-xl transition-all duration-200 overflow-hidden">
                    <div class="h-48 bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                        @if($product->image && file_exists(public_path('images/products/' . $product->image)))
                            <img src="{{ asset('images/products/' . $product->image) }}"
                                 alt="{{ $product->name }}"
                                 class="w-full h-full object-cover">
                        @else
                            <span class="text-6xl">📱</span>
                        @endif
                    </div>

                    <div class="p-4">
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1 uppercase">{{ $product->category->name }}</p>
                        <h3 class="font-semibold text-gray-800 dark:text-white mb-2">{{ $product->name }}</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-300 mb-3">{{ Str::limit($product->description, 80) }}</p>
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-2xl font-bold text-purple-600 dark:text-purple-400">£{{ number_format($product->price, 2) }}</span>
                            @if($product->stock_status === 'in_stock')
                                <span class="text-xs text-green-600 dark:text-green-400 font-medium px-2 py-1 bg-green-100 dark:bg-green-900 rounded">In Stock</span>
                            @else
                                <span class="text-xs text-red-600 dark:text-red-400 font-medium px-2 py-1 bg-red-100 dark:bg-red-900 rounded">Out of Stock</span>
                            @endif
                        </div>

                        <!-- View Details Button -->
                        <a href="{{ route('products.show', $product->slug) }}" class="block w-full bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-white py-2 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 text-center font-semibold mb-2 transition">
                            View Details
                        </a>

                        <!-- Add to Basket / Login Button -->
                        @auth
                            @if($product->stock_quantity > 0)
                                <form action="{{ route('basket.add', $product) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="w-full bg-purple-600 dark:bg-purple-500 text-white py-2 rounded-lg hover:bg-purple-700 dark:hover:bg-purple-600 transition font-semibold">
                                        Add to Basket
                                    </button>
                                </form>
                            @else
                                <button disabled class="w-full bg-gray-400 dark:bg-gray-600 text-white py-2 rounded-lg cursor-not-allowed">
                                    Out of Stock
                                </button>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="block w-full bg-purple-600 dark:bg-purple-500 text-white py-2 rounded-lg hover:bg-purple-700 dark:hover:bg-purple-600 text-center font-semibold">
                                Login to Purchase
                            </a>
                        @endauth
                    </div>
                </div>
            @endforeach
        </div>

        {{-- PAGINATION --}}
        <div class="mt-8">
            {{ $products->links() }}
        </div>
    @else
        <div class="text-center py-12 bg-white dark:bg-gray-800 rounded-lg shadow-md">
            <p class="text-xl text-gray-600 dark:text-gray-300 mb-4">No products found.</p>
            @if(request('search'))
                <a href="{{ route('products.index') }}" class="text-purple-600 dark:text-purple-400 hover:text-purple-700 font-semibold inline-block">
                    View all products →
                </a>
            @endif
        </div>
    @endif
</div>
@endsection
