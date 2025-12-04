@extends('layouts.app')

@section('title', 'About Us - TechVerse')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-16">
    <h1 class="text-4xl font-bold text-center mb-8 text-gray-900 dark:text-white">About TechVerse</h1>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-8 mb-8 transition-colors duration-200">
        <h2 class="text-2xl font-bold mb-4 text-gray-900 dark:text-white">Our Vision</h2>
        <p class="text-gray-700 dark:text-gray-300 leading-relaxed">
            TechVerse is your gateway to cutting-edge technology. We provide students, professionals,
            and tech enthusiasts with access to premium technology products at competitive prices.
        </p>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-8 transition-colors duration-200">
        <h2 class="text-2xl font-bold mb-4 text-gray-900 dark:text-white">What We Offer</h2>
        <ul class="space-y-3 text-gray-700 dark:text-gray-300">
            <li class="flex items-start">
                <span class="text-purple-600 dark:text-purple-400 mr-2">✓</span>
                <span>Latest laptops and computers</span>
            </li>
            <li class="flex items-start">
                <span class="text-purple-600 dark:text-purple-400 mr-2">✓</span>
                <span>Cutting-edge smartphones and tablets</span>
            </li>
            <li class="flex items-start">
                <span class="text-purple-600 dark:text-purple-400 mr-2">✓</span>
                <span>Premium audio equipment</span>
            </li>
            <li class="flex items-start">
                <span class="text-purple-600 dark:text-purple-400 mr-2">✓</span>
                <span>Gaming consoles and accessories</span>
            </li>
            <li class="flex items-start">
                <span class="text-purple-600 dark:text-purple-400 mr-2">✓</span>
                <span>Smart home devices and wearables</span>
            </li>
            <li class="flex items-start">
                <span class="text-purple-600 dark:text-purple-400 mr-2">✓</span>
                <span>Computer accessories and peripherals</span>
            </li>
        </ul>
    </div>
</div>
@endsection
