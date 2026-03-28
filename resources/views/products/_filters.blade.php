{{-- Filter Sidebar Component --}}
<div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 sticky top-20 transition-colors duration-200">
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Filters</h3>
        @if(request()->hasAny(['min_price', 'max_price', 'category', 'in_stock', 'brand', 'sort']))
            <button onclick="clearFilters()" class="text-sm text-purple-600 dark:text-purple-400 hover:text-purple-700 dark:hover:text-purple-500 transition">
                Clear all
            </button>
        @endif
    </div>

    <form id="filter-form" method="GET" action="{{ route('products.index') }}" class="space-y-6">
        {{-- Preserve search query --}}
        @if(request('search'))
            <input type="hidden" name="search" value="{{ request('search') }}">
        @endif

        {{-- Price Range --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                Price Range
            </label>
            <div class="space-y-3">
                <div class="flex items-center gap-2">
                    <input
                        type="number"
                        name="min_price"
                        id="min_price"
                        value="{{ request('min_price', $priceRange['min']) }}"
                        min="{{ $priceRange['min'] }}"
                        max="{{ $priceRange['max'] }}"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 dark:bg-gray-700 dark:text-white transition-colors duration-200"
                        placeholder="Min"
                    >
                    <span class="text-gray-500 dark:text-gray-400">-</span>
                    <input
                        type="number"
                        name="max_price"
                        id="max_price"
                        value="{{ request('max_price', $priceRange['max']) }}"
                        min="{{ $priceRange['min'] }}"
                        max="{{ $priceRange['max'] }}"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 dark:bg-gray-700 dark:text-white transition-colors duration-200"
                        placeholder="Max"
                    >
                </div>
                <div class="text-sm text-gray-500 dark:text-gray-400">
                    £{{ number_format($priceRange['min']) }} - £{{ number_format($priceRange['max']) }}
                </div>
            </div>
        </div>

        {{-- Category Filter --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                Category
            </label>
            <select
                name="category"
                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 dark:bg-gray-700 dark:text-white transition-colors duration-200"
                onchange="this.form.submit()"
            >
                <option value="">All Categories</option>
                @foreach($categories as $category)
                    <option value="{{ $category->slug }}" {{ request('category') == $category->slug ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Brand Filter --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                Brand
            </label>
            <select
                name="brand"
                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 dark:bg-gray-700 dark:text-white transition-colors duration-200"
                onchange="this.form.submit()"
            >
                <option value="">All Brands</option>
                @foreach($brands as $brand)
                    <option value="{{ $brand }}" {{ request('brand') == $brand ? 'selected' : '' }}>
                        {{ $brand }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Stock Status --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                Availability
            </label>
            <div class="space-y-2">
                <label class="flex items-center cursor-pointer">
                    <input
                        type="radio"
                        name="in_stock"
                        value=""
                        {{ !request()->has('in_stock') ? 'checked' : '' }}
                        onchange="this.form.submit()"
                        class="mr-2 text-purple-600 focus:ring-purple-500"
                    >
                    <span class="text-sm text-gray-700 dark:text-gray-300">All Products</span>
                </label>
                <label class="flex items-center cursor-pointer">
                    <input
                        type="radio"
                        name="in_stock"
                        value="1"
                        {{ request('in_stock') == '1' ? 'checked' : '' }}
                        onchange="this.form.submit()"
                        class="mr-2 text-purple-600 focus:ring-purple-500"
                    >
                    <span class="text-sm text-gray-700 dark:text-gray-300">In Stock Only</span>
                </label>
                <label class="flex items-center cursor-pointer">
                    <input
                        type="radio"
                        name="in_stock"
                        value="0"
                        {{ request('in_stock') == '0' ? 'checked' : '' }}
                        onchange="this.form.submit()"
                        class="mr-2 text-purple-600 focus:ring-purple-500"
                    >
                    <span class="text-sm text-gray-700 dark:text-gray-300">Out of Stock</span>
                </label>
            </div>
        </div>

        {{-- Sort Options --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                Sort By
            </label>
            <select
                name="sort"
                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 dark:bg-gray-700 dark:text-white transition-colors duration-200"
                onchange="this.form.submit()"
            >
                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest First</option>
                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name: A-Z</option>
                <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Name: Z-A</option>
            </select>
        </div>

        {{-- Apply Filters Button --}}
        <button
            type="submit"
            class="w-full bg-purple-600 dark:bg-purple-500 text-white font-medium py-2 px-4 rounded-lg hover:bg-purple-700 dark:hover:bg-purple-600 transition-colors duration-200"
        >
            Apply Filters
        </button>
    </form>
</div>
