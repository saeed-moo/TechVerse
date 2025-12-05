@extends('layouts.app')

@section('title', 'Contact Us - TechVerse')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-16">
    <h1 class="text-4xl font-bold text-center mb-8 text-gray-900 dark:text-white">Contact Us</h1>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-8 transition-colors duration-200">
        <form method="POST" action="{{ route('contact.store') }}">
            @csrf

            <div class="mb-4">
                <label class="block text-gray-700 dark:text-gray-300 font-bold mb-2">Name</label>
                <input type="text"
                       name="name"
                       required
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600 dark:bg-gray-700 dark:text-white">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 dark:text-gray-300 font-bold mb-2">Email</label>
                <input type="email"
                       name="email"
                       required
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600 dark:bg-gray-700 dark:text-white">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 dark:text-gray-300 font-bold mb-2">Subject</label>
                <input type="text"
                       name="subject"
                       required
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600 dark:bg-gray-700 dark:text-white">
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 dark:text-gray-300 font-bold mb-2">Message</label>
                <textarea name="message"
                          rows="5"
                          required
                          class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600 dark:bg-gray-700 dark:text-white"></textarea>
            </div>

            <button type="submit" class="w-full bg-purple-600 dark:bg-purple-500 text-white py-2 rounded-lg hover:bg-purple-700 dark:hover:bg-purple-600 font-semibold transition">
                Send Message
            </button>
        </form>
    </div>
</div>
@endsection
