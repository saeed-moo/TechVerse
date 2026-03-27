@extends('layouts.app')

@section('title', 'Products - TechVerse')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    {{-- Page Header --}}
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
            @if(request('search'))
                Search Results for "{{ request('search') }}"
            @elseif(request('category'))
                {{ $categories->where('slug', request('category'))->first()->name ?? 'Products' }}
            @else
                All Products
            @endif
        </h1>
        <p class="text-gray-600 dark:text-gray-400">
            Showing {{ $products->total() }} {{ Str::plural('product', $products->total()) }}
        </p>
    </div>

    {{-- Top Bar: Search + Filters Button --}}
    <div class="mb-6 bg-white dark:bg-gray-800 p-4 rounded-lg shadow-md transition-colors duration-200">
        <div class="flex flex-col md:flex-row gap-4 items-center">
            {{-- Search Form --}}
            <form method="GET" action="{{ route('products.index') }}" class="flex-1 w-full md:w-auto">
                {{-- Preserve all filters --}}
                @foreach(['category', 'min_price', 'max_price', 'brand', 'in_stock', 'rating', 'sort'] as $param)
                    @if(request($param))
                        <input type="hidden" name="{{ $param }}" value="{{ request($param) }}">
                    @endif
                @endforeach

                <div class="flex gap-2">
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Search products..."
                        class="flex-1 px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 dark:bg-gray-700 dark:text-white transition-colors duration-200"
                    >
                    <button
                        type="submit"
                        class="bg-purple-600 dark:bg-purple-500 text-white px-6 py-2.5 rounded-lg hover:bg-purple-700 dark:hover:bg-purple-600 transition-colors duration-200 font-semibold"
                    >
                        🔍
                    </button>
                </div>
            </form>

            {{-- Filter Button with Professional Animation --}}
            <button onclick="openFilterDrawer()"
                    class="flex items-center gap-2 px-6 py-2.5 bg-purple-600 dark:bg-purple-500 text-white rounded-lg hover:bg-purple-700 dark:hover:bg-purple-600 transition-all duration-200 shadow-md hover:shadow-lg group w-full md:w-auto justify-center">
                <!-- Professional Sliders Icon with Subtle Fade Animation -->
                <svg class="w-5 h-5 transition-all duration-300 group-hover:scale-110 group-hover:opacity-80"
                     fill="none"
                     stroke="currentColor"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                </svg>
                <span class="font-semibold">Filters</span>

                <!-- Active filter count badge -->
                @if(request()->hasAny(['min_price', 'max_price', 'category', 'in_stock', 'brand', 'rating']))
                    <span class="ml-1 bg-white text-purple-600 text-xs font-bold px-2 py-1 rounded-full">
                        {{ count(array_filter([request('min_price'), request('max_price'), request('category'), request('brand'), request('in_stock'), request('rating')])) }}
                    </span>
                @endif
            </button>
        </div>
    </div>

    {{-- Active Filters Tags --}}
    @if(request()->hasAny(['min_price', 'max_price', 'category', 'in_stock', 'brand', 'rating']))
        <div class="mb-6 flex flex-wrap gap-2 items-center">
            <span class="text-sm text-gray-600 dark:text-gray-400 font-medium">Active filters:</span>

            @if(request('min_price') || request('max_price'))
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-200">
                    Price: £{{ request('min_price', $priceRange['min']) }} - £{{ request('max_price', $priceRange['max']) }}
                    <button onclick="clearFilter('min_price', 'max_price')" class="ml-2 hover:text-purple-600 dark:hover:text-purple-400 text-lg font-bold">&times;</button>
                </span>
            @endif

            @if(request('category'))
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-200">
                    {{ $categories->where('slug', request('category'))->first()->name }}
                    <button onclick="clearFilter('category')" class="ml-2 hover:text-purple-600 dark:hover:text-purple-400 text-lg font-bold">&times;</button>
                </span>
            @endif

            @if(request('brand'))
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-200">
                    Brand: {{ request('brand') }}
                    <button onclick="clearFilter('brand')" class="ml-2 hover:text-purple-600 dark:hover:text-purple-400 text-lg font-bold">&times;</button>
                </span>
            @endif

            @if(request('in_stock'))
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-200">
                    {{ request('in_stock') == '1' ? 'In Stock' : 'Out of Stock' }}
                    <button onclick="clearFilter('in_stock')" class="ml-2 hover:text-purple-600 dark:hover:text-purple-400 text-lg font-bold">&times;</button>
                </span>
            @endif

            @if(request('rating'))
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-200">
                    {{ request('rating') }}⭐ & up
                    <button onclick="clearFilter('rating')" class="ml-2 hover:text-purple-600 dark:hover:text-purple-400 text-lg font-bold">&times;</button>
                </span>
            @endif

            <button onclick="clearAllFilters()" class="text-sm text-purple-600 dark:text-purple-400 hover:text-purple-700 dark:hover:text-purple-500 font-medium">
                Clear all
            </button>
        </div>
    @endif

    {{-- Products Grid (Full Width - 4 Columns!) --}}
    @if($products->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($products as $product)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md hover:shadow-xl transition-all duration-200 overflow-hidden relative">
                    {{-- Wishlist Heart Button --}}
                    @auth
                        @if($product->isInWishlist(auth()->id()))
                            {{-- Already in wishlist - filled heart --}}
                            <form action="{{ route('wishlist.remove', $product) }}" method="POST" class="absolute top-2 right-2 z-10">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-white dark:bg-gray-700 p-2 rounded-full shadow-lg hover:bg-red-50 dark:hover:bg-red-900 transition group" title="Remove from wishlist">
                                    <svg class="w-6 h-6 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path>
                                    </svg>
                                </button>
                            </form>
                        @else
                            {{-- Not in wishlist - outline heart --}}
                            <form action="{{ route('wishlist.add', $product) }}" method="POST" class="absolute top-2 right-2 z-10">
                                @csrf
                                <button type="submit" class="bg-white dark:bg-gray-700 p-2 rounded-full shadow-lg hover:bg-purple-50 dark:hover:bg-purple-900 transition group" title="Add to wishlist">
                                    <svg class="w-6 h-6 text-gray-400 group-hover:text-red-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                    </svg>
                                </button>
                            </form>
                        @endif
                    @endauth

                    {{-- Product Image --}}
                    <div class="h-48 bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                        @if($product->image && file_exists(public_path('images/products/' . $product->image)))
                            <img src="{{ asset('images/products/' . $product->image) }}"
                                 alt="{{ $product->name }}"
                                 class="w-full h-full object-cover">
                        @else
                            <span class="text-6xl">📱</span>
                        @endif
                    </div>

                    {{-- Product Info --}}
                    <div class="p-4">
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1 uppercase">{{ $product->category->name }}</p>
                        <h3 class="font-semibold text-gray-800 dark:text-white mb-2">{{ $product->name }}</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-300 mb-3">{{ Str::limit($product->description, 80) }}</p>
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-2xl font-bold text-purple-600 dark:text-purple-400">£{{ number_format($product->price, 2) }}</span>
                            @if($product->stock_quantity > 0)
                                <span class="text-xs text-green-600 dark:text-green-400 font-medium px-2 py-1 bg-green-100 dark:bg-green-900 rounded">In Stock</span>
                            @else
                                <span class="text-xs text-red-600 dark:text-red-400 font-medium px-2 py-1 bg-red-100 dark:bg-red-900 rounded">Out of Stock</span>
                            @endif
                        </div>

                        <a href="{{ route('products.show', $product->slug) }}" class="block w-full bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-white py-2 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 text-center font-semibold mb-2 transition-colors duration-200">
                            View Details
                        </a>

                        @auth
                            @if($product->stock_quantity > 0)
                                <form action="{{ route('basket.add', $product) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="w-full bg-purple-600 dark:bg-purple-500 text-white py-2 rounded-lg hover:bg-purple-700 dark:hover:bg-purple-600 transition-colors duration-200 font-semibold">
                                        Add to Basket
                                    </button>
                                </form>
                            @else
                                <button disabled class="w-full bg-gray-400 dark:bg-gray-600 text-white py-2 rounded-lg cursor-not-allowed">
                                    Out of Stock
                                </button>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="block w-full bg-purple-600 dark:bg-purple-500 text-white py-2 rounded-lg hover:bg-purple-700 dark:hover:bg-purple-600 text-center font-semibold transition-colors duration-200">
                                Login to Purchase
                            </a>
                        @endauth
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-8">
            {{ $products->withQueryString()->links() }}
        </div>
    @else
        <div class="text-center py-16 bg-white dark:bg-gray-800 rounded-lg shadow-md">
            <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <p class="text-xl text-gray-600 dark:text-gray-300 mb-4">No products found matching your filters.</p>
            <button onclick="clearAllFilters()" class="text-purple-600 dark:text-purple-400 hover:text-purple-700 dark:hover:text-purple-500 font-semibold">
                Clear all filters →
            </button>
        </div>
    @endif

    {{-- FILTER DRAWER MODAL --}}
    <div id="filter-drawer" class="fixed inset-0 z-50 hidden">
        {{-- Overlay --}}
        <div class="absolute inset-0 transition-opacity backdrop-blur-sm" onclick="closeFilterDrawer()"></div>

        {{-- Drawer Panel --}}
        <div id="drawer-panel" class="absolute right-0 top-0 h-full w-full sm:w-96 bg-white dark:bg-gray-800 shadow-2xl transform translate-x-full transition-transform duration-300 ease-out overflow-y-auto">
            {{-- Drawer Header --}}
            <div class="sticky top-0 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 p-6 flex items-center justify-between z-10">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Filters</h2>
                <button onclick="closeFilterDrawer()" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            {{-- Drawer Content --}}
            <form id="filter-form" method="GET" action="{{ route('products.index') }}" class="p-6 space-y-6">
                {{-- Preserve search query --}}
                @if(request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                @endif

                {{-- Price Range --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-3">
                        💰 Price Range
                    </label>
                    <div class="space-y-3">
                        <div class="flex items-center gap-3">
                            <input
                                type="number"
                                name="min_price"
                                id="min_price"
                                value="{{ request('min_price', $priceRange['min']) }}"
                                min="{{ $priceRange['min'] }}"
                                max="{{ $priceRange['max'] }}"
                                class="flex-1 px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 dark:bg-gray-700 dark:text-white transition-colors duration-200"
                                placeholder="Min"
                            >
                            <span class="text-gray-500 dark:text-gray-400 font-medium">to</span>
                            <input
                                type="number"
                                name="max_price"
                                id="max_price"
                                value="{{ request('max_price', $priceRange['max']) }}"
                                min="{{ $priceRange['min'] }}"
                                max="{{ $priceRange['max'] }}"
                                class="flex-1 px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 dark:bg-gray-700 dark:text-white transition-colors duration-200"
                                placeholder="Max"
                            >
                        </div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">
                            Range: £{{ number_format($priceRange['min']) }} - £{{ number_format($priceRange['max']) }}
                        </div>
                    </div>
                </div>

                <hr class="border-gray-200 dark:border-gray-700">

                {{-- Category Filter --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-3">
                        📁 Category
                    </label>
                    <select
                        name="category"
                        class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 dark:bg-gray-700 dark:text-white transition-colors duration-200"
                    >
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->slug }}" {{ request('category') == $category->slug ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <hr class="border-gray-200 dark:border-gray-700">

                {{-- Brand Filter --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-3">
                        🏷️ Brand
                    </label>
                    <select
                        name="brand"
                        class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 dark:bg-gray-700 dark:text-white transition-colors duration-200"
                    >
                        <option value="">All Brands</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand }}" {{ request('brand') == $brand ? 'selected' : '' }}>
                                {{ $brand }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <hr class="border-gray-200 dark:border-gray-700">

                {{-- Customer Rating --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-3">
                        ⭐ Customer Rating
                    </label>
                    <div class="space-y-2">
                        <label class="flex items-center cursor-pointer group">
                            <input
                                type="radio"
                                name="rating"
                                value=""
                                {{ !request('rating') ? 'checked' : '' }}
                                class="mr-3 text-purple-600 focus:ring-purple-500"
                            >
                            <span class="text-sm text-gray-700 dark:text-gray-300 group-hover:text-purple-600 dark:group-hover:text-purple-400 transition">All Ratings</span>
                        </label>
                        <label class="flex items-center cursor-pointer group">
                            <input
                                type="radio"
                                name="rating"
                                value="4"
                                {{ request('rating') == '4' ? 'checked' : '' }}
                                class="mr-3 text-purple-600 focus:ring-purple-500"
                            >
                            <span class="text-sm text-gray-700 dark:text-gray-300 group-hover:text-purple-600 dark:group-hover:text-purple-400 transition flex items-center gap-1">
                                <span>⭐⭐⭐⭐</span>
                                <span class="text-gray-500 dark:text-gray-400">& up</span>
                            </span>
                        </label>
                        <label class="flex items-center cursor-pointer group">
                            <input
                                type="radio"
                                name="rating"
                                value="3"
                                {{ request('rating') == '3' ? 'checked' : '' }}
                                class="mr-3 text-purple-600 focus:ring-purple-500"
                            >
                            <span class="text-sm text-gray-700 dark:text-gray-300 group-hover:text-purple-600 dark:group-hover:text-purple-400 transition flex items-center gap-1">
                                <span>⭐⭐⭐</span>
                                <span class="text-gray-500 dark:text-gray-400">& up</span>
                            </span>
                        </label>
                        <label class="flex items-center cursor-pointer group">
                            <input
                                type="radio"
                                name="rating"
                                value="2"
                                {{ request('rating') == '2' ? 'checked' : '' }}
                                class="mr-3 text-purple-600 focus:ring-purple-500"
                            >
                            <span class="text-sm text-gray-700 dark:text-gray-300 group-hover:text-purple-600 dark:group-hover:text-purple-400 transition flex items-center gap-1">
                                <span>⭐⭐</span>
                                <span class="text-gray-500 dark:text-gray-400">& up</span>
                            </span>
                        </label>
                        <label class="flex items-center cursor-pointer group">
                            <input
                                type="radio"
                                name="rating"
                                value="1"
                                {{ request('rating') == '1' ? 'checked' : '' }}
                                class="mr-3 text-purple-600 focus:ring-purple-500"
                            >
                            <span class="text-sm text-gray-700 dark:text-gray-300 group-hover:text-purple-600 dark:group-hover:text-purple-400 transition flex items-center gap-1">
                                <span>⭐</span>
                                <span class="text-gray-500 dark:text-gray-400">& up</span>
                            </span>
                        </label>
                    </div>
                </div>

                <hr class="border-gray-200 dark:border-gray-700">

                {{-- Stock Status --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-3">
                        ✅ Availability
                    </label>
                    <div class="space-y-2">
                        <label class="flex items-center cursor-pointer group">
                            <input
                                type="radio"
                                name="in_stock"
                                value=""
                                {{ !request()->has('in_stock') ? 'checked' : '' }}
                                class="mr-3 text-purple-600 focus:ring-purple-500"
                            >
                            <span class="text-sm text-gray-700 dark:text-gray-300 group-hover:text-purple-600 dark:group-hover:text-purple-400 transition">All Products</span>
                        </label>
                        <label class="flex items-center cursor-pointer group">
                            <input
                                type="radio"
                                name="in_stock"
                                value="1"
                                {{ request('in_stock') == '1' ? 'checked' : '' }}
                                class="mr-3 text-purple-600 focus:ring-purple-500"
                            >
                            <span class="text-sm text-gray-700 dark:text-gray-300 group-hover:text-purple-600 dark:group-hover:text-purple-400 transition">In Stock Only</span>
                        </label>
                        <label class="flex items-center cursor-pointer group">
                            <input
                                type="radio"
                                name="in_stock"
                                value="0"
                                {{ request('in_stock') == '0' ? 'checked' : '' }}
                                class="mr-3 text-purple-600 focus:ring-purple-500"
                            >
                            <span class="text-sm text-gray-700 dark:text-gray-300 group-hover:text-purple-600 dark:group-hover:text-purple-400 transition">Out of Stock</span>
                        </label>
                    </div>
                </div>

                <hr class="border-gray-200 dark:border-gray-700">

                {{-- Sort Order --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-3">
                        🔀 Sort By
                    </label>
                    <select
                        name="sort"
                        class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 dark:bg-gray-700 dark:text-white transition-colors duration-200"
                    >
                        <option value="newest" {{ request('sort', 'newest') == 'newest' ? 'selected' : '' }}>Newest First</option>
                        <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                        <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name: A-Z</option>
                        <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Name: Z-A</option>
                    </select>
                </div>
            </form>

            {{-- Drawer Footer (Sticky) --}}
            <div class="sticky bottom-0 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 p-6 space-y-3">
                <button
                    type="submit"
                    form="filter-form"
                    class="w-full bg-purple-600 dark:bg-purple-500 text-white font-semibold py-3 px-6 rounded-lg hover:bg-purple-700 dark:hover:bg-purple-600 transition-colors duration-200 shadow-lg"
                >
                    Apply Filters
                </button>
                @if(request()->hasAny(['min_price', 'max_price', 'category', 'in_stock', 'brand', 'rating']))
                    <button
                        type="button"
                        onclick="clearAllFilters()"
                        class="w-full bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-white font-semibold py-3 px-6 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors duration-200"
                    >
                        Clear All Filters
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Open filter drawer
function openFilterDrawer() {
    const drawer = document.getElementById('filter-drawer');
    const panel = document.getElementById('drawer-panel');

    drawer.classList.remove('hidden');

    // Trigger animation after a brief delay
    setTimeout(() => {
        panel.classList.remove('translate-x-full');
        panel.classList.add('translate-x-0');
    }, 10);

    // Prevent body scroll
    document.body.style.overflow = 'hidden';
}

// Close filter drawer
function closeFilterDrawer() {
    const drawer = document.getElementById('filter-drawer');
    const panel = document.getElementById('drawer-panel');

    panel.classList.remove('translate-x-0');
    panel.classList.add('translate-x-full');

    // Hide drawer after animation
    setTimeout(() => {
        drawer.classList.add('hidden');
        document.body.style.overflow = '';
    }, 300);
}

// Clear all filters
function clearAllFilters() {
    const searchParam = new URLSearchParams(window.location.search).get('search');
    const baseUrl = window.location.pathname;

    if (searchParam) {
        window.location.href = `${baseUrl}?search=${searchParam}`;
    } else {
        window.location.href = baseUrl;
    }
}

// Clear specific filter
function clearFilter(...filterNames) {
    const url = new URL(window.location);
    filterNames.forEach(name => url.searchParams.delete(name));
    window.location.href = url.toString();
}

// Close drawer on ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeFilterDrawer();
    }
});
</script>
@endpush
@endsection
