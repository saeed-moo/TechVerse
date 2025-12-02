<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - TechVerse</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center py-12">
        <div class="max-w-md w-full bg-white rounded-lg shadow-md p-8">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold">
                    <span class="text-purple-600">Tech</span><span class="text-gray-800">Verse</span>
                </h1>
                <p class="text-gray-600 mt-2">Create your account</p>
            </div>

            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Name</label>
                    <input type="text"
                           name="name"
                           value="{{ old('name') }}"
                           required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                    <input type="email"
                           name="email"
                           value="{{ old('email') }}"
                           required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                    <input type="password"
                           name="password"
                           required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600">
                    <p class="text-xs text-gray-500 mt-1">Minimum 8 characters</p>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Confirm Password</label>
                    <input type="password"
                           name="password_confirmation"
                           required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600">
                </div>

                <button type="submit" class="w-full bg-purple-600 text-white py-2 rounded-lg hover:bg-purple-700 font-semibold">
                    Sign Up
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-gray-600 text-sm">
                    Already have an account?
                    <a href="{{ route('login') }}" class="text-purple-600 hover:text-purple-800 font-semibold">Sign in</a>
                </p>
                <a href="{{ route('home') }}" class="text-gray-600 hover:text-purple-600 text-sm mt-2 inline-block">
                    ← Back to home
                </a>
            </div>
        </div>
    </div>
</body>
</html>
