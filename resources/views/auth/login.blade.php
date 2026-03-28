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

        {{-- Error Messages (Rate Limiting & Login Errors) --}}
        @if ($errors->any())
            <div class="mb-6 rounded-lg bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 dark:border-red-700 p-4">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-500 dark:text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3 flex-1">
                        <p class="text-sm font-semibold text-red-800 dark:text-red-300">
                            {{ $errors->first('email') }}
                        </p>
                        @if ($errors->has('password'))
                            <p class="text-sm text-red-700 dark:text-red-400 mt-1">
                                {{ $errors->first('password') }}
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        {{-- Success Messages --}}
        @if (session('status'))
            <div class="mb-6 rounded-lg bg-green-50 dark:bg-green-900/20 border-l-4 border-green-500 dark:border-green-700 p-4">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-500 dark:text-green-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-semibold text-green-800 dark:text-green-300">
                            {{ session('status') }}
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            {{-- Email Field --}}
            <div class="mb-4">
                <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Email</label>
                <input type="email"
                       name="email"
                       value="{{ old('email') }}"
                       required
                       class="w-full px-3 py-2 border @error('email') border-red-500 @else border-gray-300 dark:border-gray-600 @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600 dark:bg-gray-700 dark:text-white transition-colors duration-200">
            </div>

            {{-- Password Field --}}
            <div class="mb-6">
                <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Password</label>
                <input type="password"
                       name="password"
                       required
                       class="w-full px-3 py-2 border @error('password') border-red-500 @else border-gray-300 dark:border-gray-600 @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600 dark:bg-gray-700 dark:text-white transition-colors duration-200">
            </div>

            {{-- Remember Me --}}
            <div class="mb-6">
                <label class="flex items-center">
                    <input type="checkbox"
                           name="remember"
                           class="mr-2 h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 dark:border-gray-600 rounded dark:bg-gray-700">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Remember me</span>
                </label>
            </div>

            {{-- Submit Button --}}
            <button type="submit"
                    class="w-full bg-purple-600 dark:bg-purple-500 text-white py-2 rounded-lg hover:bg-purple-700 dark:hover:bg-purple-600 font-semibold transition-colors duration-200">
                Sign In
            </button>

            {{-- Security Notice --}}
            <div class="mt-4 text-center">
                <p class="text-xs text-gray-500 dark:text-gray-400 flex items-center justify-center gap-1">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    Protected: 5 attempts per 30 minutes
                </p>
            </div>
        </form>

        {{-- Sign Up Link --}}
        <div class="mt-6 text-center">
            <p class="text-gray-600 dark:text-gray-400 text-sm">
                Don't have an account?
                <a href="{{ route('register') }}" class="text-purple-600 dark:text-purple-400 hover:text-purple-800 dark:hover:text-purple-300 font-semibold transition-colors duration-200">Sign up</a>
            </p>
        </div>

        {{-- Test Accounts --}}
        <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-900 dark:bg-opacity-30 rounded-lg border border-blue-200 dark:border-blue-800">
            <p class="text-sm text-gray-700 dark:text-gray-300 font-semibold mb-2 flex items-center gap-2">
                <svg class="h-4 w-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Test Accounts:
            </p>
            <div class="space-y-1">
                <p class="text-xs text-gray-600 dark:text-gray-400">
                    <span class="font-semibold">Admin:</span> admin@techverse.com / Admin123!
                </p>
                <p class="text-xs text-gray-600 dark:text-gray-400">
                    <span class="font-semibold">Customer:</span> customer@test.com / Customer123!
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
