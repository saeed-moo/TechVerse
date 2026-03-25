<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Cancelled - TechVerse</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 dark:bg-gray-900">
    <div class="min-h-screen flex items-center justify-center px-4">
        <div class="max-w-md w-full text-center">
            {{-- Cancel Icon --}}
            <div class="inline-flex items-center justify-center w-24 h-24 bg-yellow-100 dark:bg-yellow-900 rounded-full mb-6">
                <svg class="w-16 h-16 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>

            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">Payment Cancelled</h1>
            <p class="text-gray-600 dark:text-gray-400 mb-8">Your payment was cancelled and no charges were made.</p>

            {{-- Info Box --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 mb-8 text-left">
                <h2 class="font-semibold text-gray-900 dark:text-white mb-3">What happened?</h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                    You cancelled the payment process. Your items are still in your basket and no payment was processed.
                </p>
                <div class="bg-blue-50 dark:bg-blue-900 border border-blue-200 dark:border-blue-700 rounded-lg p-3">
                    <p class="text-sm text-blue-800 dark:text-blue-200">
                        💡 <strong>Tip:</strong> Your basket items are saved. You can try checking out again whenever you're ready!
                    </p>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="space-y-3">
                <a href="{{ route('checkout.index') }}" class="block w-full bg-purple-600 dark:bg-purple-500 text-white py-3 rounded-lg hover:bg-purple-700 dark:hover:bg-purple-600 font-semibold transition">
                    Try Again
                </a>
                <a href="{{ route('basket.index') }}" class="block w-full bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white py-3 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 font-semibold transition">
                    View Basket
                </a>
                <a href="{{ route('home') }}" class="block w-full text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white font-medium transition">
                    Continue Shopping
                </a>
            </div>
        </div>
    </div>
</body>
</html>
