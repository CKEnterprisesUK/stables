<x-gallery-layout :branding="$branding">
    @section('title', 'Our Stables')

    <div class="mb-8">
        <h2 class="text-3xl font-bold text-stable-900">Our Stables</h2>
        <p class="mt-2 text-stable-600">Meet our horses and see their latest updates. Sponsor a horse to unlock full access to their news and photos.</p>
    </div>

    @if($horses->isEmpty())
        <div class="text-center py-16">
            <svg class="mx-auto h-16 w-16 text-stable-300" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3.75 21h16.5a2.25 2.25 0 002.25-2.25V6.75a2.25 2.25 0 00-2.25-2.25H3.75A2.25 2.25 0 001.5 6.75v12A2.25 2.25 0 003.75 21z" />
            </svg>
            <p class="mt-4 text-stable-500 text-lg">No horses available yet. Check back soon!</p>
        </div>
    @else
        <div class="space-y-10">
            @foreach($horses as $horse)
                @php
                    $isSponsored = in_array($horse->id, $sponsoredHorseIds);
                @endphp

                <div class="rounded-xl shadow-sm overflow-hidden {{ $isSponsored ? 'ring-2 ring-brand-500 border border-brand-300 bg-white' : 'bg-white border border-stable-200' }}">
                    {{-- "My Horse" Banner for sponsored horses --}}
                    @if($isSponsored)
                        <div class="bg-brand-500 px-4 py-1.5 flex items-center gap-2">
                            <svg class="h-4 w-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-sm font-semibold text-white">My Horse</span>
                        </div>
                    @endif

                    {{-- Horse Header --}}
                    <div class="p-6 flex flex-col sm:flex-row gap-6 items-start">
                        <a href="{{ route('gallery.show', $horse) }}" class="flex-shrink-0">
                            @if($horse->photos->isNotEmpty())
                                <img
                                    src="{{ asset('storage/' . $horse->photos->first()->path) }}"
                                    alt="{{ $horse->name }}"
                                    class="w-28 h-28 object-cover rounded-lg {{ $isSponsored ? 'ring-2 ring-brand-300' : '' }}"
                                >
                            @else
                                <div class="w-28 h-28 bg-stable-100 rounded-lg flex items-center justify-center">
                                    <svg class="h-10 w-10 text-stable-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3.75 21h16.5a2.25 2.25 0 002.25-2.25V6.75a2.25 2.25 0 00-2.25-2.25H3.75A2.25 2.25 0 001.5 6.75v12A2.25 2.25 0 003.75 21z" />
                                    </svg>
                                </div>
                            @endif
                        </a>

                        <div class="flex-1">
                            <div class="flex items-start justify-between">
                                <div>
                                    <a href="{{ route('gallery.show', $horse) }}" class="text-xl font-semibold text-stable-900 hover:text-brand-700 transition-colors">
                                        {{ $horse->name }}
                                    </a>
                                    @if($horse->facts)
                                        <p class="mt-1 text-sm text-stable-600">{{ Str::limit($horse->facts, 150) }}</p>
                                    @endif
                                </div>

                                @if($isSponsored)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <svg class="mr-1 h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                        Sponsored
                                    </span>
                                @else
                                    <a href="{{ route('signup.create', $horse) }}" class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-white bg-brand-600 rounded-lg hover:bg-brand-700 shadow-sm transition-colors">
                                        Sponsor
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Updates Section --}}
                    <div class="border-t border-stable-200">
                        @if($horse->updates->isNotEmpty())
                            @if($isSponsored)
                                {{-- Full updates visible for sponsors --}}
                                <div class="bg-stable-50 p-6">
                                    <div class="flex items-center justify-between mb-4">
                                        <h4 class="text-sm font-semibold text-stable-700 uppercase tracking-wide">
                                            Latest Updates
                                        </h4>
                                        <a href="{{ route('sponsor.horse.updates', $horse) }}" class="text-sm text-brand-600 hover:text-brand-700 font-medium">
                                            View All Updates &rarr;
                                        </a>
                                    </div>

                                    <div class="space-y-4">
                                        @foreach($horse->updates->take(3) as $update)
                                            <div class="bg-white rounded-lg p-4 shadow-sm">
                                                <div class="flex items-start justify-between">
                                                    <h5 class="font-medium text-stable-900">{{ $update->title }}</h5>
                                                    <span class="text-xs text-stable-500 ml-3 flex-shrink-0">{{ $update->created_at->format('j M Y') }}</span>
                                                </div>
                                                @if($update->body)
                                                    <p class="mt-2 text-sm text-stable-600">{{ Str::limit($update->body, 150) }}</p>
                                                @endif
                                                @if($update->photos->isNotEmpty())
                                                    <div class="mt-3 flex gap-2">
                                                        @foreach($update->photos->take(3) as $photo)
                                                            <img src="{{ asset('storage/' . $photo->path) }}" alt="Update photo" class="w-16 h-16 object-cover rounded">
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                {{-- Greyed out updates for non-sponsors --}}
                                <div class="bg-stable-50 p-6 relative">
                                    <div class="mb-4">
                                        <h4 class="text-sm font-semibold text-stable-700 uppercase tracking-wide">
                                            Latest Updates
                                        </h4>
                                    </div>

                                    <div class="space-y-4 relative">
                                        {{-- Greyed out overlay --}}
                                        <div class="absolute inset-0 z-10 flex flex-col items-center justify-center bg-white/70 backdrop-blur-sm rounded-lg">
                                            <svg class="h-10 w-10 text-stable-400 mb-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/>
                                            </svg>
                                            <p class="text-sm font-medium text-stable-700 text-center">Sponsor {{ $horse->name }} to see their updates</p>
                                            <a href="{{ route('signup.create', $horse) }}" class="mt-3 inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-brand-600 rounded-lg hover:bg-brand-700 shadow-sm transition-colors">
                                                Become a Sponsor
                                            </a>
                                        </div>

                                        {{-- Blurred preview content --}}
                                        @foreach($horse->updates->take(2) as $update)
                                            <div class="bg-white rounded-lg p-4 shadow-sm opacity-40 select-none pointer-events-none" aria-hidden="true">
                                                <div class="flex items-start justify-between">
                                                    <div class="h-4 w-48 bg-stable-200 rounded"></div>
                                                    <div class="h-3 w-16 bg-stable-200 rounded"></div>
                                                </div>
                                                <div class="mt-3 space-y-2">
                                                    <div class="h-3 w-full bg-stable-100 rounded"></div>
                                                    <div class="h-3 w-3/4 bg-stable-100 rounded"></div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        @else
                            <div class="bg-stable-50 p-6 text-center">
                                <p class="text-sm text-stable-500">No updates yet for {{ $horse->name }}.</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</x-gallery-layout>
