<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechVerse - Your Universe of Technology</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center">
        <div class="text-center">
            <h1 class="text-6xl font-bold text-blue-600 mb-4">TechVerse</h1>
            <p class="text-2xl text-gray-600 mb-8">Your Universe of Technology</p>

            <div class="space-x-4">
                <a href="{{ route('products.index') }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 inline-block">
                    Browse Products
                </a>
                <a href="{{ route('login') }}" class="bg-gray-200 text-gray-800 px-6 py-3 rounded-lg hover:bg-gray-300 inline-block">
                    Login
                </a>
            </div>

            <div class="mt-12 text-gray-500">
                <p>✅ Database: Connected</p>
                <p>✅ Products: {{ \App\Models\Product::count() }} items</p>
                <p>✅ Categories: {{ \App\Models\Category::count() }} types</p>
            </div>
        </div>
    </div>
</body>
</html>
