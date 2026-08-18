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
                <div class="mt-4 prose prose-sm max-w-none text-stable-700">
                    <p class="whitespace-pre-line">{{ $horse->facts }}</p>
                </div>
            @endif

            <!-- Sponsorship CTA -->
            <div class="mt-8 p-6 bg-brand-50 border border-brand-100 rounded-xl">
                <h3 class="text-lg font-semibold text-stable-900">Sponsor {{ $horse->name }}</h3>
                <p class="mt-1 text-sm text-stable-600">
                    Support {{ $horse->name }} with a monthly sponsorship and receive updates and a certificate.
                </p>
                <a
                    href="{{ route('signup.create', $horse) }}"
                    class="mt-4 inline-flex items-center px-5 py-2.5 bg-brand-600 text-white font-medium text-sm rounded-lg hover:bg-brand-700 shadow-sm transition-colors"
                >
                    Sponsor This Horse
                </a>
            </div>
        </div>
    </div>
</x-gallery-layout>
