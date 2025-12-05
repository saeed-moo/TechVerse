<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'TechVerse - Your Universe of Technology')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 dark:bg-gray-900 transition-colors duration-200">
    
    <!-- Navigation Bar -->
    <nav class="bg-white dark:bg-gray-800 shadow-md transition-colors duration-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="text-2xl font-bold">
                    <span class="text-purple-600 dark:text-purple-400">Tech</span><span class="text-gray-800 dark:text-white">Verse</span>
                </a>
                
                <!-- Navigation Links + Dark Mode Toggle -->
                <div class="flex items-center space-x-6">
                    <a href="{{ route('home') }}" 
                       class="text-gray-700 dark:text-gray-300 hover:text-purple-600 dark:hover:text-purple-400 transition {{ request()->routeIs('home') ? 'text-purple-600 dark:text-purple-400 font-semibold' : '' }}">
                        Home
                    </a>
                    <a href="{{ route('products.index') }}" 
                       class="text-gray-700 dark:text-gray-300 hover:text-purple-600 dark:hover:text-purple-400 transition {{ request()->routeIs('products.*') ? 'text-purple-600 dark:text-purple-400 font-semibold' : '' }}">
                        Products
                    </a>
                    <a href="{{ route('about') }}" 
                       class="text-gray-700 dark:text-gray-300 hover:text-purple-600 dark:hover:text-purple-400 transition {{ request()->routeIs('about') ? 'text-purple-600 dark:text-purple-400 font-semibold' : '' }}">
                        About
                    </a>
                    <a href="{{ route('contact') }}" 
                       class="text-gray-700 dark:text-gray-300 hover:text-purple-600 dark:hover:text-purple-400 transition {{ request()->routeIs('contact') ? 'text-purple-600 dark:text-purple-400 font-semibold' : '' }}">
                        Contact
                    </a>
                    
                    @auth
                        <a href="{{ route('basket.index') }}" 
                           class="text-gray-700 dark:text-gray-300 hover:text-purple-600 dark:hover:text-purple-400 transition {{ request()->routeIs('basket.*') ? 'text-purple-600 dark:text-purple-400 font-semibold' : '' }}">
                            Basket
                            @if(isset($basketCount) && $basketCount > 0)
                                <span class="bg-purple-600 text-white text-xs px-2 py-1 rounded-full ml-1">{{ $basketCount }}</span>
                            @endif
                        </a>
                        
                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" 
                               class="text-gray-700 dark:text-gray-300 hover:text-purple-600 dark:hover:text-purple-400 transition">
                                Admin
                            </a>
                        @endif
                        
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-700 dark:text-gray-300 hover:text-purple-600 dark:hover:text-purple-400 transition">
                                Logout
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" 
                           class="text-gray-700 dark:text-gray-300 hover:text-purple-600 dark:hover:text-purple-400 transition">
                            Login
                        </a>
                        <a href="{{ route('register') }}" 
                           class="bg-purple-600 dark:bg-purple-500 text-white px-4 py-2 rounded-lg hover:bg-purple-700 dark:hover:bg-purple-600 transition font-semibold">
                            Sign Up
                        </a>
                    @endauth
                    
                    <!-- Professional Dark Mode Toggle -->
                    <button 
                        id="theme-toggle" 
                        type="button" 
                        class="p-2 rounded-lg bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition"
                        title="Toggle dark mode"
                        aria-label="Toggle dark mode"
                    >
                        <!-- Moon Icon (Light Mode) -->
                        <svg id="theme-toggle-light-icon" class="w-5 h-5 text-gray-700 dark:text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                        </svg>
                        <!-- Sun Icon (Dark Mode) -->
                        <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5 text-gray-700 dark:text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" fill-rule="evenodd" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 mt-4">
            <div class="bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-600 text-green-700 dark:text-green-200 px-4 py-3 rounded-lg shadow-md">
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="max-w-7xl mx-auto px-4 mt-4">
            <div class="bg-red-100 dark:bg-red-900 border border-red-400 dark:border-red-600 text-red-700 dark:text-red-200 px-4 py-3 rounded-lg shadow-md">
                {{ session('error') }}
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="max-w-7xl mx-auto px-4 mt-4">
            <div class="bg-red-100 dark:bg-red-900 border border-red-400 dark:border-red-600 text-red-700 dark:text-red-200 px-4 py-3 rounded-lg shadow-md">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <!-- Page Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 dark:bg-gray-950 text-white mt-16 transition-colors duration-200">
        <div class="max-w-7xl mx-auto px-4 py-12">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- About Section -->
                <div>
                    <h3 class="text-xl font-bold mb-4">
                        <span class="text-purple-400">Tech</span>Verse
                    </h3>
                    <p class="text-gray-400">Your universe of technology products</p>
                </div>
                
                <!-- Quick Links -->
                <div>
                    <h4 class="font-semibold mb-4">Quick Links</h4>
                    <ul class="space-y-2">
                        <li><a href="{{ route('products.index') }}" class="text-gray-400 hover:text-purple-400 transition">Products</a></li>
                        <li><a href="{{ route('about') }}" class="text-gray-400 hover:text-purple-400 transition">About Us</a></li>
                        <li><a href="{{ route('contact') }}" class="text-gray-400 hover:text-purple-400 transition">Contact</a></li>
                    </ul>
                </div>
                
                <!-- Contact -->
                <div>
                    <h4 class="font-semibold mb-4">Contact</h4>
                    <p class="text-gray-400">CS2TP 2025-26 Project</p>
                    <p class="text-gray-400">Aston University</p>
                </div>
            </div>
            
            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; 2025 TechVerse. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Dark Mode Toggle Script -->
    <script>
        const themeToggleBtn = document.getElementById('theme-toggle');
        const themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
        const themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');

        // Check for saved theme preference or default to light mode
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
            themeToggleLightIcon.classList.add('hidden');
            themeToggleDarkIcon.classList.remove('hidden');
        } else {
            themeToggleLightIcon.classList.remove('hidden');
            themeToggleDarkIcon.classList.add('hidden');
        }

        // Toggle dark mode on button click
        themeToggleBtn.addEventListener('click', function() {
            themeToggleDarkIcon.classList.toggle('hidden');
            themeToggleLightIcon.classList.toggle('hidden');

            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('color-theme', 'light');
            } else {
                document.documentElement.classList.add('dark');
                localStorage.setItem('color-theme', 'dark');
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
