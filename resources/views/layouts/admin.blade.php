<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $stableBranding->name ?? config('app.name', 'Stables') }} - Admin</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-100 font-sans antialiased">
    <nav class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center gap-4">
                    @if($stableBranding && $stableBranding->logo_path)
                        <img src="{{ asset('storage/' . $stableBranding->logo_path) }}" alt="{{ $stableBranding->name }}" class="h-8 w-auto">
                    @endif
                    <span class="text-lg font-semibold text-gray-900">
                        {{ $stableBranding->name ?? config('app.name', 'Stables') }} Admin
                    </span>
                </div>

                <!-- Admin Navigation Links -->
                <div class="hidden sm:flex items-center space-x-6">
                    <a href="{{ route('admin.horses.index') }}"
                       class="text-sm font-medium {{ request()->routeIs('admin.horses.*') ? 'text-indigo-600' : 'text-gray-600 hover:text-gray-900' }}">
                        Horses
                    </a>
                    <a href="{{ route('admin.sponsors.index') }}"
                       class="text-sm font-medium {{ request()->routeIs('admin.sponsors.*') ? 'text-indigo-600' : 'text-gray-600 hover:text-gray-900' }}">
                        Sponsors
                    </a>
                    <a href="{{ route('admin.branding.edit') }}"
                       class="text-sm font-medium {{ request()->routeIs('admin.branding.*') ? 'text-indigo-600' : 'text-gray-600 hover:text-gray-900' }}">
                        Branding
                    </a>
                    <a href="{{ route('admin.settings.smtp') }}"
                       class="text-sm font-medium {{ request()->routeIs('admin.settings.smtp*') ? 'text-indigo-600' : 'text-gray-600 hover:text-gray-900' }}">
                        SMTP Settings
                    </a>
                    <a href="{{ route('admin.settings.stripe') }}"
                       class="text-sm font-medium {{ request()->routeIs('admin.settings.stripe*') ? 'text-indigo-600' : 'text-gray-600 hover:text-gray-900' }}">
                        Stripe
                    </a>
                </div>

                <div class="flex items-center gap-4">
                    <a href="{{ route('gallery') }}" class="text-sm text-gray-500 hover:text-gray-700">View Gallery</a>
                    <span class="text-sm text-gray-600">{{ auth()->user()?->name ?? 'Admin' }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-sm text-gray-500 hover:text-gray-700">Logout</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Mobile Navigation -->
        <div class="sm:hidden border-t border-gray-200 py-2 px-4 space-y-1">
            <a href="{{ route('admin.horses.index') }}"
               class="block px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('admin.horses.*') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-600 hover:bg-gray-50' }}">
                Horses
            </a>
            <a href="{{ route('admin.sponsors.index') }}"
               class="block px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('admin.sponsors.*') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-600 hover:bg-gray-50' }}">
                Sponsors
            </a>
            <a href="{{ route('admin.branding.edit') }}"
               class="block px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('admin.branding.*') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-600 hover:bg-gray-50' }}">
                Branding
            </a>
            <a href="{{ route('admin.settings.smtp') }}"
               class="block px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('admin.settings.smtp*') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-600 hover:bg-gray-50' }}">
                SMTP Settings
            </a>
            <a href="{{ route('admin.settings.stripe') }}"
               class="block px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('admin.settings.stripe*') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-600 hover:bg-gray-50' }}">
                Stripe
            </a>
        </div>
    </nav>

    <main class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if(session('status'))
                <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-md text-green-700 text-sm">
                    {{ session('status') }}
                </div>
            @endif

            @yield('content')
        </div>
    </main>
</body>
</html>
