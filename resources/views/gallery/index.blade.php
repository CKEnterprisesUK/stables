<x-gallery-layout :branding="$branding">
    @section('title', 'Our Horses')

    <div class="mb-8">
        <h2 class="text-3xl font-bold text-stable-900">Our Horses</h2>
        <p class="mt-2 text-stable-600">Meet the horses you can sponsor and support.</p>
    </div>

    @if($horses->isEmpty())
        <div class="text-center py-16">
            <svg class="mx-auto h-16 w-16 text-stable-300" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3.75 21h16.5a2.25 2.25 0 002.25-2.25V6.75a2.25 2.25 0 00-2.25-2.25H3.75A2.25 2.25 0 001.5 6.75v12A2.25 2.25 0 003.75 21z" />
            </svg>
            <p class="mt-4 text-stable-500 text-lg">No horses available yet. Check back soon!</p>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($horses as $horse)
                <div class="bg-white rounded-xl border border-stable-200 shadow-sm overflow-hidden hover:shadow-md transition-shadow group">
                    <!-- Horse Photo (consistent aspect ratio) -->
                    <a href="{{ route('gallery.show', $horse) }}" class="block overflow-hidden">
                        @if($horse->photos->isNotEmpty())
                            <img
                                src="{{ asset('storage/' . $horse->photos->first()->path) }}"
                                alt="{{ $horse->name }}"
                                class="w-full aspect-[4/3] object-cover group-hover:scale-105 transition-transform duration-300"
                            >
                        @else
                            <div class="w-full aspect-[4/3] bg-stable-100 flex items-center justify-center">
                                <svg class="h-12 w-12 text-stable-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3.75 21h16.5a2.25 2.25 0 002.25-2.25V6.75a2.25 2.25 0 00-2.25-2.25H3.75A2.25 2.25 0 001.5 6.75v12A2.25 2.25 0 003.75 21z" />
                                </svg>
                            </div>
                        @endif
                    </a>

                    <!-- Horse Info -->
                    <div class="p-5">
                        <a href="{{ route('gallery.show', $horse) }}" class="block">
                            <h3 class="text-lg font-semibold text-stable-900 group-hover:text-brand-700 transition-colors">
                                {{ $horse->name }}
                            </h3>
                        </a>

                        @if($horse->facts)
                            <p class="mt-2 text-sm text-stable-600 line-clamp-3">
                                {{ Str::limit($horse->facts, 120) }}
                            </p>
                        @endif

                        <div class="mt-4">
                            <a
                                href="{{ route('signup.create', $horse) }}"
                                class="inline-flex items-center px-4 py-2 bg-brand-600 text-white text-sm font-medium rounded-lg hover:bg-brand-700 shadow-sm transition-colors"
                            >
                                Sponsor {{ $horse->name }}
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</x-gallery-layout>
