<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Basket - TechVerse</title>
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
                    <a href="{{ route('products.index') }}" class="text-gray-700 hover:text-purple-600">Products</a>
                    <a href="{{ route('basket.index') }}" class="text-purple-600 font-semibold">Basket</a>
                    <a href="{{ route('orders.index') }}" class="text-gray-700 hover:text-purple-600">Orders</a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-gray-700 hover:text-purple-600">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-5xl mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-6">Shopping Basket</h1>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        @if($basketItems->isEmpty())
            <div class="bg-white rounded-lg shadow-md p-12 text-center">
                <div class="text-6xl mb-4">🛒</div>
                <h3 class="text-xl font-semibold text-gray-800 mb-2">Your basket is empty</h3>
                <p class="text-gray-600 mb-4">Start shopping to add items to your basket</p>
                <a href="{{ route('products.index') }}" class="inline-block bg-purple-600 text-white px-6 py-2 rounded-lg hover:bg-purple-700">
                    Browse Products
                </a>
            </div>
        @else
            <div class="bg-white rounded-lg shadow-md">
                @foreach($basketItems as $item)
                    <div class="flex items-center gap-4 p-6 border-b last:border-b-0">
                        <div class="w-24 h-24 bg-gray-200 rounded-lg flex items-center justify-center">
                            <span class="text-4xl">📱</span>
                        </div>

                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-800 mb-1">{{ $item->product->name }}</h3>
                            <p class="text-sm text-gray-600">{{ $item->product->category->name }}</p>
                            <p class="text-lg font-bold text-purple-600 mt-2">£{{ number_format($item->product->price, 2) }}</p>
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
                                       class="w-20 px-3 py-2 border border-gray-300 rounded-lg text-center">
                                <button type="submit" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 text-sm">
                                    Update
                                </button>
                            </form>

                            <form action="{{ route('basket.destroy', $item) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                        </div>

                        <div class="text-right min-w-[100px]">
                            <p class="text-lg font-bold text-gray-800">
                                £{{ number_format($item->product->price * $item->quantity, 2) }}
                            </p>
                        </div>
                    </div>
                @endforeach

                <div class="p-6 bg-gray-50">
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-xl font-semibold">Total:</span>
                        <span class="text-3xl font-bold text-purple-600">£{{ number_format($total, 2) }}</span>
                    </div>

                    <div class="flex gap-4">
                        <a href="{{ route('products.index') }}" class="flex-1 bg-gray-200 text-gray-700 py-3 rounded-lg hover:bg-gray-300 text-center font-semibold">
                            Continue Shopping
                        </a>
                        <a href="{{ route('checkout.index') }}" class="flex-1 bg-purple-600 text-white py-3 rounded-lg hover:bg-purple-700 text-center font-semibold">
                            Proceed to Checkout
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</body>
</html>
