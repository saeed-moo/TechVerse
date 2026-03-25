<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - TechVerse</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <nav class="bg-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <a href="{{ route('home') }}" class="text-2xl font-bold">
                    <span class="text-purple-600">Tech</span><span class="text-gray-800">Verse</span>
                </a>
            </div>
        </div>
    </nav>

    <div class="max-w-4xl mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-8">Checkout</h1>

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Checkout Form -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-bold mb-4">Shipping Information</h2>

                    <form method="POST" action="{{ route('checkout.process') }}">
                        @csrf

                        <div class="mb-4">
                            <label class="block text-gray-700 font-bold mb-2">Address</label>
                            <input type="text"
                                   name="shipping_address"
                                   value="{{ auth()->user()->address }}"
                                   required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600">
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-gray-700 font-bold mb-2">City</label>
                                <input type="text"
                                       name="shipping_city"
                                       value="{{ auth()->user()->city }}"
                                       required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600">
                            </div>
                            <div>
                                <label class="block text-gray-700 font-bold mb-2">Postcode</label>
                                <input type="text"
                                       name="shipping_postcode"
                                       value="{{ auth()->user()->postcode }}"
                                       required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600">
                            </div>
                        </div>

                        <div class="mb-6">
                            <label class="block text-gray-700 font-bold mb-2">Contact Phone</label>
                            <input type="tel"
                                   name="contact_phone"
                                   value="{{ auth()->user()->phone }}"
                                   required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600">
                        </div>

                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                            <p class="text-sm text-blue-800">
                                <strong>Note:</strong> This is a dummy checkout. No payment will be processed.
                            </p>
                        </div>

                        <button type="submit" class="w-full bg-purple-600 text-white py-3 rounded-lg hover:bg-purple-700 font-semibold">
                            Place Order
                        </button>
                    </form>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-bold mb-4">Order Summary</h2>

                    <div class="space-y-3 mb-4">
                        @foreach($basketItems as $item)
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">{{ $item->product->name }} x{{ $item->quantity }}</span>
                                <span class="font-semibold">£{{ number_format($item->product->price * $item->quantity, 2) }}</span>
                            </div>
                        @endforeach
                    </div>

                    <div class="border-t pt-4">
                        <div class="flex justify-between items-center">
                            <span class="text-xl font-bold">Total:</span>
                            <span class="text-2xl font-bold text-purple-600">£{{ number_format($total, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
