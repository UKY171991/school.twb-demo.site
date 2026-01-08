<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'School Management System') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen">
        <!-- Navigation -->
        <nav class="bg-white shadow-sm border-b">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <a href="/" class="text-xl font-bold text-gray-800">
                            {{ config('app.name', 'School Management System') }}
                        </a>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('about') }}" class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">About</a>
                        <a href="{{ route('contact') }}" class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">Contact</a>
                        @auth
                            <a href="{{ url('/dashboard') }}" class="bg-blue-500 hover:bg-blue-700 text-white px-3 py-2 rounded-md text-sm font-medium">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">Login</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="bg-blue-500 hover:bg-blue-700 text-white px-3 py-2 rounded-md text-sm font-medium">Register</a>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Heading -->
        @isset($header)
            <header class="bg-white shadow-sm">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t mt-12">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <p class="text-center text-sm text-gray-500">
                    &copy; {{ date('Y') }} {{ config('app.name', 'School Management System') }}. All rights reserved.
                </p>
            </div>
        </footer>
    </div>
</body>
</html>