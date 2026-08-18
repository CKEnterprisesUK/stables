<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Sponsorships') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Flash messages --}}
            @if(session('status'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-md">
                    {{ session('status') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-md">
                    {{ session('error') }}
                </div>
            @endif

            @if($sponsorships->isEmpty())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center text-gray-500">
                        <p class="text-lg">You don't have any sponsorships yet.</p>
                        <a href="{{ route('gallery') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700">
                            Browse Horses
                        </a>
                    </div>
                </div>
            @else
                <div class="space-y-8">
                    @foreach($sponsorships as $sponsorship)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            {{-- Sponsorship Card --}}
                            <div class="p-6">
                                <div class="flex flex-col sm:flex-row gap-6">
                                    {{-- Horse Photo --}}
                                    <div class="flex-shrink-0">
                                        @if($sponsorship->horse->photos->isNotEmpty())
                                            <img
                                                src="{{ asset('storage/' . $sponsorship->horse->photos->first()->path) }}"
                                                alt="{{ $sponsorship->horse->name }}"
                                                class="w-32 h-32 object-cover rounded-lg"
                                            >
                                        @else
                                            <div class="w-32 h-32 bg-gray-200 rounded-lg flex items-center justify-center">
                                                <span class="text-gray-400 text-xs">No photo</span>
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Sponsorship Info --}}
                                    <div class="flex-1">
                                        <div class="flex items-start justify-between">
                                            <div>
                                                <h3 class="text-lg font-semibold text-gray-900">
                                                    {{ $sponsorship->horse->name }}
                                                </h3>
                                                @if($sponsorship->child_name)
                                                    <p class="text-sm text-gray-600">
                                                        Sponsored for {{ $sponsorship->child_name }}
                                                    </p>
                                                @endif
                                            </div>

                                            {{-- Status Badge --}}
                                            @if($sponsorship->status->value === 'active')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    Active
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                    Cancelled
                                                </span>
                                            @endif
                                        </div>

                                        <div class="mt-2 text-sm text-gray-600 space-y-1">
                                            <p><span class="font-medium">Monthly amount:</span> &pound;{{ number_format($sponsorship->monthly_amount / 100, 2) }}</p>
                                            <p><span class="font-medium">Started:</span> {{ $sponsorship->created_at->format('j F Y') }}</p>
                                            @if($sponsorship->ends_at)
                                                <p><span class="font-medium">Ends:</span> {{ $sponsorship->ends_at->format('j F Y') }}</p>
                                            @endif
                                        </div>

                                        {{-- Actions for active sponsorships --}}
                                        @if($sponsorship->status->value === 'active')
                                            <div class="mt-4 flex flex-wrap gap-3">
                                                @if(Route::has('sponsor.certificate'))
                                                    <a href="{{ route('sponsor.certificate', $sponsorship) }}" class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-indigo-700 bg-indigo-50 rounded-md hover:bg-indigo-100">
                                                        View Certificate
                                                    </a>
                                                @endif
                                                <form method="POST" action="{{ route('sponsor.sponsorship.cancel', $sponsorship) }}" onsubmit="return confirm('Are you sure you want to cancel this sponsorship?');">
                                                    @csrf
                                                    <button type="submit" class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-red-700 bg-red-50 rounded-md hover:bg-red-100">
                                                        Cancel Sponsorship
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- Horse Updates (only for active sponsorships) --}}
                            @if($sponsorship->status->value === 'active' && isset($updatesByHorse[$sponsorship->horse_id]) && $updatesByHorse[$sponsorship->horse_id]->isNotEmpty())
                                <div class="border-t border-gray-200 bg-gray-50 p-6">
                                    <h4 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-4">
                                        Recent Updates
                                    </h4>

                                    <div class="space-y-4">
                                        @foreach($updatesByHorse[$sponsorship->horse_id] as $update)
                                            <div class="bg-white rounded-md p-4 shadow-sm">
                                                <div class="flex items-start justify-between">
                                                    <h5 class="font-medium text-gray-900">{{ $update->title }}</h5>
                                                    <span class="text-xs text-gray-500">{{ $update->created_at->format('j M Y') }}</span>
                                                </div>

                                                @if($update->body)
                                                    <p class="mt-2 text-sm text-gray-600">{{ $update->body }}</p>
                                                @endif

                                                @if($update->photos->isNotEmpty())
                                                    <div class="mt-3 flex flex-wrap gap-2">
                                                        @foreach($update->photos as $photo)
                                                            <img
                                                                src="{{ asset('storage/' . $photo->path) }}"
                                                                alt="Update photo"
                                                                class="w-20 h-20 object-cover rounded"
                                                            >
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
