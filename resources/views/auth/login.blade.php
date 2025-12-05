@extends('layouts.app')

@section('title', 'Login - TechVerse')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12">
    <div class="max-w-md w-full bg-white dark:bg-gray-800 rounded-lg shadow-md p-8 transition-colors duration-200">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold">
                <span class="text-purple-600 dark:text-purple-400">Tech</span><span class="text-gray-800 dark:text-white">Verse</span>
            </h1>
            <p class="text-gray-600 dark:text-gray-400 mt-2">Sign in to your account</p>
        </div>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-4">
                <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Email</label>
                <input type="email" 
                       name="email" 
                       value="{{ old('email') }}"
                       required
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600 dark:bg-gray-700 dark:text-white">
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Password</label>
                <input type="password" 
                       name="password" 
                       required
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600 dark:bg-gray-700 dark:text-white">
            </div>

            <div class="mb-6">
                <label class="flex items-center">
                    <input type="checkbox" name="remember" class="mr-2">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Remember me</span>
                </label>
            </div>

            <button type="submit" class="w-full bg-purple-600 dark:bg-purple-500 text-white py-2 rounded-lg hover:bg-purple-700 dark:hover:bg-purple-600 font-semibold transition">
                Sign In
            </button>
        </form>

        <div class="mt-6 text-center">
            <p class="text-gray-600 dark:text-gray-400 text-sm">
                Don't have an account? 
                <a href="{{ route('register') }}" class="text-purple-600 dark:text-purple-400 hover:text-purple-800 dark:hover:text-purple-300 font-semibold">Sign up</a>
            </p>
        </div>

        <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-900 dark:bg-opacity-30 rounded-lg">
            <p class="text-sm text-gray-700 dark:text-gray-300 font-semibold mb-2">Test Accounts:</p>
            <p class="text-xs text-gray-600 dark:text-gray-400">Admin: admin@techverse.com / Admin123!</p>
            <p class="text-xs text-gray-600 dark:text-gray-400">Customer: customer@test.com / Customer123!</p>
        </div>
    </div>
</div>
@endsection
