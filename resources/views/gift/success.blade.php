<x-guest-layout>
    <div class="text-center">
        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100">
            <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
            </svg>
        </div>

        <h2 class="mt-4 text-2xl font-bold text-gray-900">Gift Purchased!</h2>
        <p class="mt-2 text-sm text-gray-600">
            Your {{ $gift->months }}-month sponsorship gift for <strong>{{ $gift->horse->name }}</strong> has been purchased successfully.
        </p>

        @if($gift->horse->photos->isNotEmpty())
            <img src="{{ asset('storage/' . $gift->horse->photos->first()->path) }}"
                 alt="{{ $gift->horse->name }}"
                 class="mt-4 mx-auto w-24 h-24 object-cover rounded-full">
        @endif

        <div class="mt-6 p-4 bg-amber-50 border border-amber-200 rounded-lg">
            <p class="text-xs text-amber-700 uppercase tracking-wider font-medium">Gift Code</p>
            <p class="mt-1 text-xl font-mono font-bold text-amber-900 tracking-wider">{{ $gift->code }}</p>
            <p class="mt-1 text-xs text-amber-600">Valid until {{ $gift->expires_at->format('F j, Y') }}</p>
        </div>

        <div class="mt-6 space-y-3">
            <a href="{{ URL::signedRoute('gift.download', ['gift' => $gift]) }}"
               class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-sm text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition">
                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                </svg>
                Download Gift Card (PDF)
            </a>

            <p class="text-xs text-gray-500">
                We've also emailed the gift card to <strong>{{ $gift->purchaser_email }}</strong>
            </p>
        </div>

        <div class="mt-6 p-3 bg-gray-50 border border-gray-200 rounded-md text-left">
            <p class="text-sm font-medium text-gray-700">How it works:</p>
            <ol class="mt-2 text-xs text-gray-600 space-y-1 list-decimal list-inside">
                <li>Give the gift card (PDF or code) to the recipient</li>
                <li>They visit the redemption link and register an account</li>
                <li>No credit card needed — the sponsorship is pre-paid</li>
                <li>They'll get updates and a certificate for {{ $gift->horse->name }}</li>
            </ol>
        </div>

        <div class="mt-6">
            <a href="{{ route('gallery') }}" class="text-sm text-indigo-600 hover:text-indigo-800">
                &larr; Back to Gallery
            </a>
        </div>
    </div>
</x-guest-layout>
