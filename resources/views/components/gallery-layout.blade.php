@props(['branding' => null])

@php
    // Use explicitly passed branding or fall back to the shared $stableBranding from view composer
    $activeBranding = $branding ?? ($stableBranding ?? null);
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $activeBranding->name ?? config('app.name', 'Horse Sponsorship') }} - @yield('title', 'Stables')</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-stable-50">
        <!-- Header with stable branding -->
        <header class="bg-white shadow-sm border-b border-stable-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <div class="flex items-center justify-between">
                    <a href="{{ route('stables') }}" class="flex items-center">
                        @if($activeBranding && $activeBranding->logo_path)
                            <img src="{{ asset('storage/' . $activeBranding->logo_path) }}" alt="{{ $activeBranding->name }}" class="h-10 w-auto max-w-[180px] object-contain">
                        @else
                            <h1 class="text-xl font-bold text-stable-900">
                                {{ $activeBranding->name ?? config('app.name', 'Horse Sponsorship') }}
                            </h1>
                        @endif
                    </a>
                    <nav class="flex items-center gap-4">
                        <a href="{{ route('gift.info') }}" class="text-sm text-stable-600 hover:text-stable-900 transition-colors">Gift a Sponsorship</a>
                        @auth
                            @if(auth()->user()->role->isAdmin())
                                <a href="{{ route('admin.horses.index') }}" class="text-sm text-stable-600 hover:text-stable-900 transition-colors">Admin</a>
                            @elseif(auth()->user()->role === \App\Enums\UserRole::Sponsor)
                                <a href="{{ route('sponsor.dashboard') }}" class="text-sm text-stable-600 hover:text-stable-900 transition-colors">My Sponsorships</a>
                            @endif
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="text-sm text-stable-600 hover:text-stable-900 transition-colors">Logout</button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="text-sm text-stable-600 hover:text-stable-900 transition-colors">Login</a>
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
        <footer class="bg-white border-t border-stable-200 mt-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-3 text-sm text-stable-500">
                    <p>&copy; {{ date('Y') }} {{ $activeBranding->name ?? config('app.name', 'Horse Sponsorship') }}. All rights reserved.</p>
                    <div class="flex items-center gap-4">
                        <a href="{{ route('legal.privacy') }}" class="hover:text-stable-700 transition-colors">Privacy</a>
                        <a href="{{ route('legal.terms') }}" class="hover:text-stable-700 transition-colors">Terms</a>
                        <span>&middot;</span>
                        <p>Kindly provided by <a href="https://ckenterprises.co.uk" target="_blank" rel="noopener" class="text-brand-600 hover:text-brand-700 font-medium transition-colors">CK Enterprises UK</a></p>
                    </div>
                </div>
            </div>
        </footer>
    </body>
</html>
