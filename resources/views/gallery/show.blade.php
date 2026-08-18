<x-gallery-layout :branding="$branding">
    @section('title', $horse->name)

    <!-- Back link -->
    <div class="mb-6">
        <a href="{{ route('gallery') }}" class="text-sm text-indigo-600 hover:text-indigo-800 flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Gallery
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <!-- Photo Gallery -->
        @if($horse->photos->isNotEmpty())
            <div class="grid grid-cols-1 md:grid-cols-2 gap-1">
                @foreach($horse->photos as $index => $photo)
                    <div class="{{ $index === 0 && $horse->photos->count() > 1 ? 'md:col-span-2' : '' }}">
                        <img
                            src="{{ asset('storage/' . $photo->path) }}"
                            alt="{{ $horse->name }}"
                            class="w-full {{ $index === 0 ? 'h-80' : 'h-56' }} object-cover"
                        >
                    </div>
                @endforeach
            </div>
        @else
            <div class="w-full h-64 bg-gray-200 flex items-center justify-center">
                <span class="text-gray-400">No photos available</span>
            </div>
        @endif

        <!-- Horse Details -->
        <div class="p-6 md:p-8">
            <h2 class="text-3xl font-bold text-gray-900">{{ $horse->name }}</h2>

            @if($horse->facts)
                <div class="mt-4 prose prose-gray max-w-none">
                    <p class="text-gray-700 whitespace-pre-line">{{ $horse->facts }}</p>
                </div>
            @endif

            <!-- Sponsorship CTA -->
            <div class="mt-8 p-6 bg-indigo-50 rounded-lg">
                <h3 class="text-lg font-semibold text-gray-900">Sponsor {{ $horse->name }}</h3>
                <p class="mt-1 text-sm text-gray-600">
                    Support {{ $horse->name }} with a monthly sponsorship and receive updates and a certificate.
                </p>
                <a
                    href="{{ route('signup.create', $horse) }}"
                    class="mt-4 inline-flex items-center px-6 py-3 bg-indigo-600 text-white font-medium rounded-md hover:bg-indigo-700 transition-colors"
                >
                    Sponsor This Horse
                </a>
            </div>
        </div>
    </div>
</x-gallery-layout>
