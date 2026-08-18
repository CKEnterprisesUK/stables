<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $stableBranding->name ?? config('app.name', 'Stables') }} - Admin</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-stable-50 font-sans antialiased" x-data="{ sidebarOpen: false }">

    <!-- Mobile sidebar overlay -->
    <div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-40 bg-stable-900/50 lg:hidden" @click="sidebarOpen = false">
    </div>

    <!-- Sidebar (fixed on all sizes) -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
           class="fixed inset-y-0 left-0 z-50 w-64 flex flex-col bg-white border-r border-stable-200 shadow-sm transition-transform duration-300 ease-in-out lg:translate-x-0">
        <!-- Logo / Brand -->
        <div class="flex items-center gap-3 px-6 py-5 border-b border-stable-100">
            @if($stableBranding && $stableBranding->logo_path)
                <img src="{{ asset('storage/' . $stableBranding->logo_path) }}" alt="{{ $stableBranding->name }}" class="h-10 w-auto max-w-[160px] object-contain">
            @else
                <div class="min-w-0">
                    <p class="text-base font-semibold text-stable-900 truncate">{{ $stableBranding->name ?? config('app.name', 'Stables') }}</p>
                    <p class="text-xs text-stable-500">Admin Panel</p>
                </div>
            @endif
        </div>

        <!-- Navigation Links -->
        <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-1">
            @if(auth()->user()?->role === \App\Enums\UserRole::SuperAdmin)
            <a href="{{ route('admin.setup') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
                      {{ request()->routeIs('admin.setup') ? 'bg-brand-50 text-brand-700' : 'text-stable-600 hover:bg-stable-100 hover:text-stable-900' }}">
                <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Setup Checklist
            </a>
            @endif

            <a href="{{ route('admin.horses.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
                      {{ request()->routeIs('admin.horses.*') ? 'bg-brand-50 text-brand-700' : 'text-stable-600 hover:bg-stable-100 hover:text-stable-900' }}">
                <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
                </svg>
                Horses
            </a>

            <a href="{{ route('admin.sponsors.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
                      {{ request()->routeIs('admin.sponsors.*') ? 'bg-brand-50 text-brand-700' : 'text-stable-600 hover:bg-stable-100 hover:text-stable-900' }}"
               @if(!in_array(auth()->user()?->role?->value, ['super_admin', 'sponsorship_admin'])) style="display:none" @endif>
                <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                </svg>
                Sponsors
            </a>

            @if(auth()->user()?->role === \App\Enums\UserRole::SuperAdmin)
            <div class="pt-4 pb-2 px-3">
                <p class="text-xs font-semibold text-stable-400 uppercase tracking-wider">Settings</p>
            </div>

            <a href="{{ route('admin.settings.general') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
                      {{ request()->routeIs('admin.settings.general*') ? 'bg-brand-50 text-brand-700' : 'text-stable-600 hover:bg-stable-100 hover:text-stable-900' }}">
                <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Pricing
            </a>

            <a href="{{ route('admin.branding.edit') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
                      {{ request()->routeIs('admin.branding.*') ? 'bg-brand-50 text-brand-700' : 'text-stable-600 hover:bg-stable-100 hover:text-stable-900' }}">
                <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.53 16.122a3 3 0 00-5.78 1.128 2.25 2.25 0 01-2.4 2.245 4.5 4.5 0 008.4-2.245c0-.399-.078-.78-.22-1.128zm0 0a15.998 15.998 0 003.388-1.62m-5.043-.025a15.994 15.994 0 011.622-3.395m3.42 3.42a15.995 15.995 0 004.764-4.648l3.876-5.814a1.151 1.151 0 00-1.597-1.597L14.146 6.32a15.996 15.996 0 00-4.649 4.763m3.42 3.42a6.776 6.776 0 00-3.42-3.42" />
                </svg>
                Branding
            </a>

            <a href="{{ route('admin.settings.smtp') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
                      {{ request()->routeIs('admin.settings.smtp*') ? 'bg-brand-50 text-brand-700' : 'text-stable-600 hover:bg-stable-100 hover:text-stable-900' }}">
                <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                </svg>
                Email (SMTP)
            </a>

            <a href="{{ route('admin.settings.stripe') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
                      {{ request()->routeIs('admin.settings.stripe*') ? 'bg-brand-50 text-brand-700' : 'text-stable-600 hover:bg-stable-100 hover:text-stable-900' }}">
                <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" />
                </svg>
                Payments
            </a>

            <a href="{{ route('admin.admins.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
                      {{ request()->routeIs('admin.admins.*') ? 'bg-brand-50 text-brand-700' : 'text-stable-600 hover:bg-stable-100 hover:text-stable-900' }}">
                <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                </svg>
                Admin Users
            </a>
            @endif
        </nav>

        <!-- Bottom section -->
        <div class="border-t border-stable-100 px-3 py-4 space-y-1">
            <a href="{{ route('gallery') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-stable-600 hover:bg-stable-100 hover:text-stable-900 transition-colors">
                <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                </svg>
                View Gallery
            </a>

            <div class="flex items-center gap-3 px-3 py-2.5">
                <div class="h-8 w-8 rounded-full bg-brand-100 flex items-center justify-center">
                    <span class="text-sm font-semibold text-brand-700">{{ substr(auth()->user()?->name ?? 'A', 0, 1) }}</span>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-medium text-stable-900 truncate">{{ auth()->user()?->name ?? 'Admin' }}</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-stable-400 hover:text-stable-600 transition-colors" title="Logout">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main content area (offset by sidebar width on desktop) -->
    <div class="lg:ml-64 min-h-screen">
        <!-- Top bar (mobile only) -->
        <header class="sticky top-0 z-30 bg-white border-b border-stable-200 lg:hidden">
            <div class="flex items-center justify-between px-4 py-3">
                <button @click="sidebarOpen = true" class="text-stable-600 hover:text-stable-900 focus:outline-none" aria-label="Open menu">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>
                @if($stableBranding && $stableBranding->logo_path)
                    <img src="{{ asset('storage/' . $stableBranding->logo_path) }}" alt="{{ $stableBranding->name }}" class="h-8 w-auto max-w-[120px] object-contain">
                @else
                    <span class="text-sm font-semibold text-stable-900">{{ $stableBranding->name ?? config('app.name', 'Stables') }}</span>
                @endif
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-stable-400 hover:text-stable-600" title="Logout">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                        </svg>
                    </button>
                </form>
            </div>
        </header>

        <!-- Page content -->
        <main class="py-6 lg:py-8">
            <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
                @if(session('status'))
                    <div class="mb-6 flex items-center gap-3 p-4 bg-brand-50 border border-brand-200 rounded-lg text-brand-800 text-sm">
                        <svg class="h-5 w-5 shrink-0 text-brand-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ session('status') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 flex items-center gap-3 p-4 bg-red-50 border border-red-200 rounded-lg text-red-800 text-sm">
                        <svg class="h-5 w-5 shrink-0 text-red-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                        </svg>
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

</body>
</html>
