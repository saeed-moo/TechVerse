<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - TechVerse</title>
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
                    <a href="{{ route('about') }}" class="text-purple-600 font-semibold">About</a>
                    <a href="{{ route('contact') }}" class="text-gray-700 hover:text-purple-600">Contact</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-4xl mx-auto px-4 py-16">
        <h1 class="text-4xl font-bold text-center mb-8">About TechVerse</h1>

        <div class="bg-white rounded-lg shadow-md p-8 mb-8">
            <h2 class="text-2xl font-bold mb-4">Our Vision</h2>
            <p class="text-gray-700 leading-relaxed">
                TechVerse is your gateway to cutting-edge technology. We provide students, professionals,
                and tech enthusiasts with access to premium technology products at competitive prices.
            </p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-8">
            <h2 class="text-2xl font-bold mb-4">What We Offer</h2>
            <ul class="space-y-3 text-gray-700">
                <li>✓ Latest laptops and computers</li>
                <li>✓ Cutting-edge smartphones and tablets</li>
                <li>✓ Premium audio equipment</li>
                <li>✓ Gaming consoles and accessories</li>
                <li>✓ Smart home devices and wearables</li>
                <li>✓ Computer accessories and peripherals</li>
            </ul>
        </div>
    </div>
</body>
</html>
