<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Your Sponsorships') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Your current and past horse sponsorships.') }}
        </p>
    </header>

    <div class="mt-6 space-y-4">
        @forelse($sponsorships as $sponsorship)
            <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-lg">
                {{-- Horse Photo --}}
                <div class="flex-shrink-0">
                    @if($sponsorship->horse && $sponsorship->horse->photos->isNotEmpty())
                        <img
                            src="{{ asset('storage/' . $sponsorship->horse->photos->first()->path) }}"
                            alt="{{ $sponsorship->horse->name }}"
                            class="w-14 h-14 object-cover rounded-full"
                        >
                    @else
                        <div class="w-14 h-14 bg-gray-200 rounded-full flex items-center justify-center">
                            <span class="text-gray-400 text-xs">No photo</span>
                        </div>
                    @endif
                </div>

                {{-- Info --}}
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2">
                        <h4 class="font-semibold text-gray-900 truncate">{{ $sponsorship->horse->name ?? 'Unknown Horse' }}</h4>
                        @if($sponsorship->status === \App\Enums\SponsorshipStatus::Active)
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Active</span>
                        @elseif($sponsorship->status === \App\Enums\SponsorshipStatus::Gift)
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">Gift</span>
                        @elseif($sponsorship->status === \App\Enums\SponsorshipStatus::Cancelled)
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">Cancelled</span>
                        @elseif($sponsorship->status === \App\Enums\SponsorshipStatus::Expired)
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">Expired</span>
                        @endif
                    </div>
                    <p class="text-sm text-gray-500">
                        &pound;{{ number_format($sponsorship->monthly_amount / 100, 2) }}/month
                        @if($sponsorship->child_name)
                            &middot; For {{ $sponsorship->child_name }}
                        @endif
                    </p>
                    @if($sponsorship->ends_at)
                        <p class="text-xs text-gray-400 mt-1">
                            {{ $sponsorship->status === \App\Enums\SponsorshipStatus::Cancelled ? 'Ended' : 'Ends' }}
                            {{ $sponsorship->ends_at->format('j M Y') }}
                        </p>
                    @endif
                </div>
            </div>
        @empty
            <p class="text-sm text-gray-500">{{ __('You don\'t have any sponsorships yet.') }}</p>
        @endforelse
    </div>
</section>
