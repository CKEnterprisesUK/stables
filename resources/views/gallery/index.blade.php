<x-gallery-layout :branding="$branding">
    @section('title', 'Our Horses')

    <div class="mb-8">
        <h2 class="text-3xl font-bold text-gray-900">Our Horses</h2>
        <p class="mt-2 text-gray-600">Meet the horses you can sponsor and support.</p>
    </div>

    @if($horses->isEmpty())
        <div class="text-center py-12">
            <p class="text-gray-500 text-lg">No horses available yet. Check back soon!</p>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($horses as $horse)
                <div class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow">
                    <!-- Horse Photo -->
                    <a href="{{ route('gallery.show', $horse) }}">
                        @if($horse->photos->isNotEmpty())
                            <img
                                src="{{ asset('storage/' . $horse->photos->first()->path) }}"
                                alt="{{ $horse->name }}"
                                class="w-full h-56 object-cover"
                            >
                        @else
                            <div class="w-full h-56 bg-gray-200 flex items-center justify-center">
                                <span class="text-gray-400 text-sm">No photo available</span>
                            </div>
                        @endif
                    </a>

                    <!-- Horse Info -->
                    <div class="p-4">
                        <a href="{{ route('gallery.show', $horse) }}" class="block">
                            <h3 class="text-lg font-semibold text-gray-900 hover:text-indigo-600">
                                {{ $horse->name }}
                            </h3>
                        </a>

                        @if($horse->facts)
                            <p class="mt-2 text-sm text-gray-600 line-clamp-3">
                                {{ Str::limit($horse->facts, 120) }}
                            </p>
                        @endif

                        <div class="mt-4">
                            <a
                                href="{{ route('signup.create', $horse) }}"
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 transition-colors"
                            >
                                Sponsor This Horse
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</x-gallery-layout>
