<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - TechVerse</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 dark:bg-gray-900">
    <nav class="bg-white dark:bg-gray-800 shadow-md">
        <div class="max-w-7xl mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <a href="{{ route('home') }}" class="text-2xl font-bold">
                    <span class="text-purple-600 dark:text-purple-400">Tech</span><span class="text-gray-800 dark:text-white">Verse</span>
                </a>
            </div>
        </div>
    </nav>

    <div class="max-w-4xl mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-8 text-gray-900 dark:text-white">Checkout</h1>

        @if(session('error'))
            <div class="bg-red-100 dark:bg-red-900 border border-red-400 dark:border-red-600 text-red-700 dark:text-red-200 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Checkout Form -->
            <div class="lg:col-span-2">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-bold mb-4 text-gray-900 dark:text-white">Shipping Information</h2>

                    <form method="POST" action="{{ route('payment.checkout') }}">
                        @csrf

                        <div class="mb-4">
                            <label class="block text-gray-700 dark:text-gray-300 font-bold mb-2">Address</label>
                            <input type="text"
                                   name="shipping_address"
                                   value="{{ auth()->user()->address }}"
                                   required
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600 dark:bg-gray-700 dark:text-white">
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-gray-700 dark:text-gray-300 font-bold mb-2">City</label>
                                <input type="text"
                                       name="shipping_city"
                                       value="{{ auth()->user()->city }}"
                                       required
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600 dark:bg-gray-700 dark:text-white">
                            </div>
                            <div>
                                <label class="block text-gray-700 dark:text-gray-300 font-bold mb-2">Postcode</label>
                                <input type="text"
                                       name="shipping_postcode"
                                       value="{{ auth()->user()->postcode }}"
                                       required
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600 dark:bg-gray-700 dark:text-white">
                            </div>
                        </div>

                        <div class="mb-6">
                            <label class="block text-gray-700 dark:text-gray-300 font-bold mb-2">Contact Phone</label>
                            <input type="tel"
                                   name="contact_phone"
                                   value="{{ auth()->user()->phone }}"
                                   required
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600 dark:bg-gray-700 dark:text-white">
                        </div>

                        <div class="bg-blue-50 dark:bg-blue-900 border border-blue-200 dark:border-blue-700 rounded-lg p-4 mb-6">
                            <p class="text-sm text-blue-800 dark:text-blue-200">
                                <strong>💳 Secure Payment:</strong> You'll be redirected to Stripe for secure payment processing.
                            </p>
                            <p class="text-xs text-blue-600 dark:text-blue-400 mt-1">
                                <strong>Test mode:</strong> Use card 4242 4242 4242 4242, any future expiry, any CVC
                            </p>
                        </div>

                        <button type="submit" class="w-full bg-purple-600 dark:bg-purple-500 text-white py-3 rounded-lg hover:bg-purple-700 dark:hover:bg-purple-600 font-semibold transition">
                            Proceed to Payment
                        </button>
                    </form>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-bold mb-4 text-gray-900 dark:text-white">Order Summary</h2>

                    <div class="space-y-3 mb-4">
                        @foreach($basketItems as $item)
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600 dark:text-gray-400">{{ $item->product->name }} x{{ $item->quantity }}</span>
                                <span class="font-semibold text-gray-900 dark:text-white">£{{ number_format($item->product->price * $item->quantity, 2) }}</span>
                            </div>
                        @endforeach
                    </div>

                    <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                        <div class="flex justify-between items-center">
                            <span class="text-xl font-bold text-gray-900 dark:text-white">Total:</span>
                            <span class="text-2xl font-bold text-purple-600 dark:text-purple-400">£{{ number_format($total, 2) }}</span>
                        </div>
                    </div>

                    <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                            <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                            Secure checkout powered by Stripe
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
