<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $branding->name ?? config('app.name', 'Horse Sponsorship') }} - @yield('title', 'Gallery')</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-50">
        <!-- Header with stable branding -->
        <header class="bg-white shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <div class="flex items-center justify-between">
                    <a href="{{ route('gallery') }}" class="flex items-center gap-3">
                        @if($branding && $branding->logo_path)
                            <img src="{{ asset('storage/' . $branding->logo_path) }}" alt="{{ $branding->name }}" class="h-10 w-auto">
                        @endif
                        <h1 class="text-xl font-bold text-gray-900">
                            {{ $branding->name ?? config('app.name', 'Horse Sponsorship') }}
                        </h1>
                    </a>
                    <nav class="flex items-center gap-4">
                        @auth
                            <a href="{{ route('dashboard') }}" class="text-sm text-gray-600 hover:text-gray-900">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-gray-900">Login</a>
                        @endauth
                    </nav>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            {{ $slot }}
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t mt-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 text-center text-sm text-gray-500">
                &copy; {{ date('Y') }} {{ $branding->name ?? config('app.name', 'Horse Sponsorship') }}. All rights reserved.
            </div>
        </footer>
    </body>
</html>
