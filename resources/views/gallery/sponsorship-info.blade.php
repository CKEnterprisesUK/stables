<x-gallery-layout :branding="$branding">
    @section('title', 'What Your Sponsorship Goes To')

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
        <div class="p-6 md:p-8">
            <h2 class="text-3xl font-bold text-stable-900">What Your Sponsorship Goes To</h2>

            @if($branding && $branding->sponsorship_info)
                <div class="mt-6 prose prose-stable max-w-none text-stable-700">
                    {!! nl2br(e($branding->sponsorship_info)) !!}
                </div>
            @else
                <div class="mt-6 text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-stable-300" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                    </svg>
                    <p class="mt-4 text-stable-500">This information is coming soon.</p>
                </div>
            @endif

            <!-- Sponsorship CTA -->
            <div class="mt-8 p-6 bg-brand-50 border border-brand-100 rounded-xl">
                <h3 class="text-lg font-semibold text-stable-900">Ready to make a difference?</h3>
                <p class="mt-1 text-sm text-stable-600">
                    Browse our horses and choose one to sponsor today.
                </p>
                <a
                    href="{{ route('gallery') }}"
                    class="mt-4 inline-flex items-center px-5 py-2.5 bg-brand-600 text-white font-medium text-sm rounded-lg hover:bg-brand-700 shadow-sm transition-colors"
                >
                    View Our Horses
                </a>
            </div>
        </div>
    </div>
</x-gallery-layout>
