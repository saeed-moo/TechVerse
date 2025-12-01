<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechVerse - Home</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <div class="text-2xl font-bold">
                    <a href="{{ route('home') }}">
                        <span class="text-purple-600">Tech</span><span class="text-gray-800">Verse</span>
                    </a>
                </div>
                <div class="hidden md:flex items-center space-x-6">
                    <a href="{{ route('home') }}" class="text-gray-700 hover:text-purple-600 font-medium transition">Home</a>
                    <a href="{{ route('products.index') }}" class="text-gray-700 hover:text-purple-600 font-medium transition">Products</a>
                    <a href="{{ route('about') }}" class="text-gray-700 hover:text-purple-600 font-medium transition">About</a>
                    <a href="{{ route('contact') }}" class="text-gray-700 hover:text-purple-600 font-medium transition">Contact</a>

                    @auth
                        <!-- Shopping Cart with Badge -->
                        <a href="{{ route('basket.index') }}" class="relative text-gray-700 hover:text-purple-600 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" stroke-width="2">
                                <path d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            @if(session('basket') && count(session('basket')) > 0)
                                <span class="absolute -top-2 -right-2 bg-purple-600 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center">
                                    {{ count(session('basket')) }}
                                </span>
                            @endif
                        </a>

                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" class="text-gray-700 hover:text-purple-600 font-medium transition">Admin</a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-700 hover:text-purple-600 font-medium transition">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-purple-600 font-medium transition">Login</a>
                        <a href="{{ route('register') }}" class="bg-purple-600 text-white px-6 py-2 rounded-lg hover:bg-purple-700 transition shadow-md">Sign Up</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-purple-600 via-purple-700 to-purple-800 text-white py-20">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <h1 class="text-5xl font-bold mb-4">Your Universe of Technology</h1>
            <p class="text-xl mb-8 opacity-90">Premium technology products for students and professionals</p>
            <a href="{{ route('products.index') }}" class="bg-white text-purple-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 inline-block transition transform hover:scale-105 shadow-lg">
                Shop Now
            </a>
        </div>
    </div>

    <!-- Categories with Gradient Icons -->
    <div class="bg-white py-16 border-b">
        <div class="max-w-7xl mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12 text-gray-800">Shop by Category</h2>

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
                           class="flex-shrink-0 w-48 bg-white rounded-2xl p-6 shadow-lg cursor-pointer transition-all duration-300 hover:-translate-y-3 hover:shadow-2xl group border border-gray-100">
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
                                <h3 class="font-bold text-gray-800 text-center text-sm leading-tight">
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
    <div class="bg-gray-50 py-16">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center mb-12">
                <h2 class="text-3xl font-bold text-gray-800">Featured Products</h2>
                <a href="{{ route('products.index') }}" class="text-purple-600 font-semibold hover:text-purple-700 transition">
                    View All →
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($featuredProducts as $product)
                    <div class="bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 hover:-translate-y-3 overflow-hidden group border border-gray-100">
                        <!-- Product Image -->
                        <div class="relative h-64 bg-gray-100 flex items-center justify-center overflow-hidden">
                            @if($product->image && file_exists(public_path('images/' . $product->image)))
                                <img src="{{ asset('images/' . $product->image) }}"
                                     alt="{{ $product->name }}"
                                     class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110">
                            @else
                                <!-- Placeholder icon -->
                                <svg class="w-24 h-24 text-purple-300" fill="currentColor" viewBox="0 0 24 24">
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
                            <p class="text-xs text-purple-600 font-bold mb-2 uppercase tracking-wide">{{ $product->category->name }}</p>
                            <h3 class="font-bold text-lg text-gray-800 mb-2 line-clamp-2">{{ $product->name }}</h3>
                            <p class="text-sm text-gray-600 mb-4 line-clamp-2">{{ Str::limit($product->description, 80) }}</p>

                            <div class="flex items-center justify-between">
                                <span class="text-2xl font-bold text-purple-600">£{{ number_format($product->price, 2) }}</span>
                                @if(Route::has('products.show'))
                                    <a href="{{ route('products.show', $product->slug) }}"
                                       class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition text-sm font-semibold shadow-md">
                                        View Details
                                    </a>
                                @else
                                    <button class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition text-sm font-semibold shadow-md">
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

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
                <div>
                    <h3 class="text-xl font-bold mb-4">
                        <span class="text-purple-400">Tech</span><span>Verse</span>
                    </h3>
                    <p class="text-gray-400">Your universe of technology products</p>
                </div>
                <div>
                    <h4 class="font-bold mb-4">Quick Links</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="{{ route('products.index') }}" class="hover:text-purple-400 transition">Products</a></li>
                        <li><a href="{{ route('about') }}" class="hover:text-purple-400 transition">About Us</a></li>
                        <li><a href="{{ route('contact') }}" class="hover:text-purple-400 transition">Contact</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold mb-4">Contact</h4>
                    <p class="text-gray-400">CS2TP 2025-26 Project</p>
                    <p class="text-gray-400">Aston University</p>
                </div>
            </div>
            <div class="border-t border-gray-800 pt-8 text-center text-gray-400">
                <p>&copy; 2025 TechVerse. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html>
