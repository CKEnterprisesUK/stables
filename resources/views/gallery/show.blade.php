<x-gallery-layout :branding="$branding">
    @section('title', $horse->name)

    <!-- Back link -->
    <div class="mb-6">
        <a href="{{ route('gallery') }}" class="inline-flex items-center gap-1 text-sm text-stable-500 hover:text-stable-700 transition-colors">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/>
            </svg>
            Back to Gallery
        </a>
    </div>

    <div class="bg-white rounded-xl border border-stable-200 shadow-sm overflow-hidden">
        <!-- Photo Gallery (consistent aspect ratios) -->
        @if($horse->photos->isNotEmpty())
            @if($horse->photos->count() === 1)
                <img
                    src="{{ asset('storage/' . $horse->photos->first()->path) }}"
                    alt="{{ $horse->name }}"
                    class="w-full aspect-[16/9] object-cover"
                >
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-1">
                    @foreach($horse->photos as $index => $photo)
                        <div class="{{ $index === 0 ? 'sm:col-span-2' : '' }}">
                            <img
                                src="{{ asset('storage/' . $photo->path) }}"
                                alt="{{ $horse->name }}"
                                class="w-full {{ $index === 0 ? 'aspect-[16/9]' : 'aspect-[4/3]' }} object-cover"
                            >
                        </div>
                    @endforeach
                </div>
            @endif
        @else
            <div class="w-full aspect-[16/9] bg-stable-100 flex items-center justify-center">
                <svg class="h-16 w-16 text-stable-300" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3.75 21h16.5a2.25 2.25 0 002.25-2.25V6.75a2.25 2.25 0 00-2.25-2.25H3.75A2.25 2.25 0 001.5 6.75v12A2.25 2.25 0 003.75 21z" />
                </svg>
            </div>
        @endif

        <!-- Horse Details -->
        <div class="p-6 md:p-8">
            <h2 class="text-3xl font-bold text-stable-900">{{ $horse->name }}</h2>

            @if($horse->facts)
                <p class="mt-2 text-stable-600">{{ $horse->facts }}</p>
            @endif

            {{-- Profile Info Grid --}}
            @if($horse->date_of_birth || $horse->breed || $horse->colour || $horse->gender || $horse->height_hands || $horse->arrival_date)
                <div class="mt-6 grid grid-cols-2 sm:grid-cols-3 gap-4">
                    @if($horse->date_of_birth)
                        <div class="bg-stable-50 rounded-lg p-3">
                            <p class="text-xs font-medium text-stable-500 uppercase tracking-wide">Age</p>
                            <p class="mt-1 text-sm font-semibold text-stable-900">{{ $horse->age }} years old</p>
                            <p class="text-xs text-stable-500">Born {{ $horse->date_of_birth->format('j M Y') }}</p>
                        </div>
                    @endif

                    @if($horse->breed)
                        <div class="bg-stable-50 rounded-lg p-3">
                            <p class="text-xs font-medium text-stable-500 uppercase tracking-wide">Breed</p>
                            <p class="mt-1 text-sm font-semibold text-stable-900">{{ $horse->breed }}</p>
                        </div>
                    @endif

                    @if($horse->colour)
                        <div class="bg-stable-50 rounded-lg p-3">
                            <p class="text-xs font-medium text-stable-500 uppercase tracking-wide">Colour</p>
                            <p class="mt-1 text-sm font-semibold text-stable-900">{{ $horse->colour }}</p>
                        </div>
                    @endif

                    @if($horse->gender)
                        <div class="bg-stable-50 rounded-lg p-3">
                            <p class="text-xs font-medium text-stable-500 uppercase tracking-wide">Gender</p>
                            <p class="mt-1 text-sm font-semibold text-stable-900">{{ $horse->gender }}</p>
                        </div>
                    @endif

                    @if($horse->height_hands)
                        <div class="bg-stable-50 rounded-lg p-3">
                            <p class="text-xs font-medium text-stable-500 uppercase tracking-wide">Height</p>
                            <p class="mt-1 text-sm font-semibold text-stable-900">{{ $horse->height_hands }}hh</p>
                        </div>
                    @endif

                    @if($horse->arrival_date)
                        <div class="bg-stable-50 rounded-lg p-3">
                            <p class="text-xs font-medium text-stable-500 uppercase tracking-wide">With Us Since</p>
                            <p class="mt-1 text-sm font-semibold text-stable-900">{{ $horse->arrival_date->format('F Y') }}</p>
                        </div>
                    @endif
                </div>
            @endif

            {{-- Favourite Treats --}}
            @if($horse->favourite_treats)
                <div class="mt-6">
                    <h3 class="text-sm font-semibold text-stable-700 uppercase tracking-wide flex items-center gap-2">
                        <svg class="h-4 w-4 text-brand-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                        </svg>
                        Favourite Treats
                    </h3>
                    <p class="mt-2 text-stable-700">{{ $horse->favourite_treats }}</p>
                </div>
            @endif

            {{-- Personality --}}
            @if($horse->personality)
                <div class="mt-6">
                    <h3 class="text-sm font-semibold text-stable-700 uppercase tracking-wide flex items-center gap-2">
                        <svg class="h-4 w-4 text-brand-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.182 15.182a4.5 4.5 0 01-6.364 0M21 12a9 9 0 11-18 0 9 9 0 0118 0zM9.75 9.75c0 .414-.168.75-.375.75S9 10.164 9 9.75 9.168 9 9.375 9s.375.336.375.75zm-.375 0h.008v.015h-.008V9.75zm5.625 0c0 .414-.168.75-.375.75s-.375-.336-.375-.75.168-.75.375-.75.375.336.375.75zm-.375 0h.008v.015h-.008V9.75z" />
                        </svg>
                        Personality
                    </h3>
                    <p class="mt-2 text-stable-700 leading-relaxed">{{ $horse->personality }}</p>
                </div>
            @endif

            {{-- Backstory --}}
            @if($horse->backstory)
                <div class="mt-6">
                    <h3 class="text-sm font-semibold text-stable-700 uppercase tracking-wide flex items-center gap-2">
                        <svg class="h-4 w-4 text-brand-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                        </svg>
                        Their Story
                    </h3>
                    <p class="mt-2 text-stable-700 leading-relaxed">{!! nl2br(e($horse->backstory)) !!}</p>
                </div>
            @endif

            <!-- Sponsorship CTA -->
            <div class="mt-8 p-6 bg-brand-50 border border-brand-100 rounded-xl">
                <h3 class="text-lg font-semibold text-stable-900">Sponsor {{ $horse->name }}</h3>
                <p class="mt-1 text-sm text-stable-600">
                    Support {{ $horse->name }} with a monthly sponsorship and receive updates and a certificate.
                </p>
                <div class="mt-4 flex flex-wrap gap-3">
                    <a
                        href="{{ route('signup.create', $horse) }}"
                        class="inline-flex items-center px-5 py-2.5 bg-brand-600 text-white font-medium text-sm rounded-lg hover:bg-brand-700 shadow-sm transition-colors"
                    >
                        Sponsor This Horse
                    </a>
                    <a
                        href="{{ route('gift.create', $horse) }}"
                        class="inline-flex items-center px-5 py-2.5 border border-brand-600 text-brand-700 font-medium text-sm rounded-lg hover:bg-brand-50 transition-colors"
                    >
                        <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 11.25v8.25a1.5 1.5 0 01-1.5 1.5H5.25a1.5 1.5 0 01-1.5-1.5v-8.25M12 4.875A2.625 2.625 0 109.375 7.5H12m0-2.625V7.5m0-2.625A2.625 2.625 0 1114.625 7.5H12m0 0V21m-8.625-9.75h18c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125h-18c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                        </svg>
                        Gift a Sponsorship
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-gallery-layout>
