<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products - TechVerse</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <nav class="bg-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <a href="{{ route('home') }}" class="text-2xl font-bold">
                    <span class="text-purple-600">Tech</span><span class="text-gray-800">Verse</span>
                </a>
                <div class="space-x-6">
                    <a href="{{ route('home') }}" class="text-gray-700 hover:text-purple-600">Home</a>
                    <a href="{{ route('products.index') }}" class="text-purple-600 font-semibold">Products</a>
                    <a href="{{ route('about') }}" class="text-gray-700 hover:text-purple-600">About</a>
                    <a href="{{ route('contact') }}" class="text-gray-700 hover:text-purple-600">Contact</a>
                    @auth
                        <a href="{{ route('basket.index') }}" class="text-gray-700 hover:text-purple-600">Basket</a>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-purple-600">Login</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 py-8">
        {{-- SUCCESS MESSAGE --}}
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        {{-- ERROR MESSAGES --}}
        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <h1 class="text-3xl font-bold mb-8">Our Products</h1>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($products as $product)
                <div class="bg-white rounded-lg shadow-md hover:shadow-xl transition overflow-hidden">
                    <div class="h-48 bg-gray-200 flex items-center justify-center">
                        <span class="text-6xl">📱</span>
                    </div>
                    <div class="p-4">
                        <p class="text-xs text-gray-500 mb-1">{{ $product->category->name }}</p>
                        <h3 class="font-semibold text-gray-800 mb-2">{{ $product->name }}</h3>
                        <p class="text-sm text-gray-600 mb-3">{{ Str::limit($product->description, 80) }}</p>
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-2xl font-bold text-purple-600">£{{ number_format($product->price, 2) }}</span>
                            @if($product->stock_status === 'in_stock')
                                <span class="text-xs text-green-600 font-medium">In Stock</span>
                            @else
                                <span class="text-xs text-red-600 font-medium">Out of Stock</span>
                            @endif
                        </div>
                        @auth
                            @if($product->stock_quantity > 0)
                                <form action="{{ route('basket.add', $product) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="w-full bg-purple-600 text-white py-2 rounded-lg hover:bg-purple-700 transition">
                                        Add to Basket
                                    </button>
                                </form>
                            @else
                                <button disabled class="w-full bg-gray-400 text-white py-2 rounded-lg cursor-not-allowed">
                                    Out of Stock
                                </button>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="block w-full bg-purple-600 text-white py-2 rounded-lg hover:bg-purple-700 text-center">
                                Login to Purchase
                            </a>
                        @endauth
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $products->links() }}
        </div>
    </div>
</body>
</html>
