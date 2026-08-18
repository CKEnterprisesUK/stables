<x-gallery-layout :branding="$branding">
    @section('title', 'Gift a Sponsorship')

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
            <div class="text-center">
                <h2 class="text-3xl font-bold text-stable-900">Gift a Sponsorship</h2>
                <p class="mt-2 text-stable-600 max-w-2xl mx-auto">
                    Give the gift of a horse sponsorship to someone special. Perfect for birthdays, Christmas,
                    or just because — a unique and meaningful present that supports our horses.
                </p>
            </div>

            <!-- How it Works -->
            <div class="mt-10">
                <h3 class="text-lg font-semibold text-stable-900 text-center">How It Works</h3>
                <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="text-center p-4">
                        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-brand-100">
                            <span class="text-lg font-bold text-brand-700">1</span>
                        </div>
                        <h4 class="mt-3 font-semibold text-stable-900">Choose a Horse & Duration</h4>
                        <p class="mt-1 text-sm text-stable-600">
                            Pick a horse from our gallery and select 3, 6, or 12 months of sponsorship.
                        </p>
                    </div>
                    <div class="text-center p-4">
                        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-brand-100">
                            <span class="text-lg font-bold text-brand-700">2</span>
                        </div>
                        <h4 class="mt-3 font-semibold text-stable-900">Pay Once & Get a Gift Card</h4>
                        <p class="mt-1 text-sm text-stable-600">
                            Make a one-time payment. You'll receive a beautiful PDF gift card with a unique redemption code.
                        </p>
                    </div>
                    <div class="text-center p-4">
                        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-brand-100">
                            <span class="text-lg font-bold text-brand-700">3</span>
                        </div>
                        <h4 class="mt-3 font-semibold text-stable-900">Recipient Redeems</h4>
                        <p class="mt-1 text-sm text-stable-600">
                            The recipient registers with their gift code — no credit card needed. They'll get updates, photos, and a certificate.
                        </p>
                    </div>
                </div>
            </div>

            <!-- What's Included -->
            <div class="mt-10 p-6 bg-stable-50 rounded-xl">
                <h3 class="text-lg font-semibold text-stable-900">What's Included</h3>
                <ul class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <li class="flex items-start gap-2">
                        <svg class="h-5 w-5 text-green-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                        </svg>
                        <span class="text-sm text-stable-700">Downloadable gift card PDF to print or email</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="h-5 w-5 text-green-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                        </svg>
                        <span class="text-sm text-stable-700">Personalised certificate for the recipient</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="h-5 w-5 text-green-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                        </svg>
                        <span class="text-sm text-stable-700">Regular horse updates with photos</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="h-5 w-5 text-green-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                        </svg>
                        <span class="text-sm text-stable-700">No credit card required for the recipient</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="h-5 w-5 text-green-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                        </svg>
                        <span class="text-sm text-stable-700">Optional personal message on the gift card</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="h-5 w-5 text-green-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                        </svg>
                        <span class="text-sm text-stable-700">Gift code valid for 1 year from purchase</span>
                    </li>
                </ul>
            </div>

            <!-- Pricing note -->
            <div class="mt-10 text-center">
                <h3 class="text-lg font-semibold text-stable-900">Pricing</h3>
                <p class="mt-2 text-sm text-stable-600 max-w-lg mx-auto">
                    Gift sponsorships start at &pound;{{ number_format($monthlyAmount * 3, 2) }} for 3 months.
                    Choose 3, 6, or 12 months when you select a horse below. It's a one-time payment — no recurring charges.
                </p>
            </div>

            <!-- Choose a Horse -->
            <div class="mt-10">
                <h3 class="text-lg font-semibold text-stable-900 text-center">Choose a Horse to Gift</h3>
                <p class="mt-1 text-sm text-stable-600 text-center">Select a horse and you'll choose the duration on the next page.</p>

                @if($horses->isEmpty())
                    <div class="mt-6 text-center py-8">
                        <p class="text-stable-500">No horses available yet. Check back soon!</p>
                    </div>
                @else
                    <div class="mt-6 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                        @foreach($horses as $horse)
                            <a href="{{ route('gift.create', $horse) }}"
                               class="group block rounded-xl border border-stable-200 overflow-hidden hover:border-brand-400 hover:shadow-md transition-all">
                                @if($horse->photos->isNotEmpty())
                                    <img src="{{ asset('storage/' . $horse->photos->first()->path) }}"
                                         alt="{{ $horse->name }}"
                                         class="w-full aspect-square object-cover group-hover:scale-105 transition-transform duration-200">
                                @else
                                    <div class="w-full aspect-square bg-stable-100 flex items-center justify-center">
                                        <svg class="h-10 w-10 text-stable-300" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3.75 21h16.5a2.25 2.25 0 002.25-2.25V6.75a2.25 2.25 0 00-2.25-2.25H3.75A2.25 2.25 0 001.5 6.75v12A2.25 2.25 0 003.75 21z"/>
                                        </svg>
                                    </div>
                                @endif
                                <div class="p-3 text-center">
                                    <p class="font-semibold text-stable-900 group-hover:text-brand-700 transition-colors">{{ $horse->name }}</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Already have a code? -->
            <div class="mt-8 text-center border-t border-stable-200 pt-6">
                <p class="text-sm text-stable-600">Already have a gift code?</p>
                <a
                    href="{{ route('gift.redeem.create') }}"
                    class="mt-2 inline-flex items-center px-4 py-2 border border-brand-600 text-brand-700 font-medium text-sm rounded-lg hover:bg-brand-50 transition-colors"
                >
                    Redeem Your Gift
                </a>
            </div>
        </div>
    </div>
</x-gallery-layout>
