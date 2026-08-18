<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('sponsor.dashboard') }}" class="text-gray-500 hover:text-gray-700">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/>
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Cancel Sponsorship') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8">
                    {{-- Warning Icon --}}
                    <div class="flex justify-center mb-6">
                        <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center">
                            <svg class="h-8 w-8 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                            </svg>
                        </div>
                    </div>

                    {{-- Heading --}}
                    <h3 class="text-xl font-bold text-gray-900 text-center mb-2">
                        Are you sure you want to cancel?
                    </h3>
                    <p class="text-gray-600 text-center mb-8">
                        You're about to cancel your sponsorship of <span class="font-semibold">{{ $sponsorship->horse->name }}</span>.
                    </p>

                    {{-- Horse Card --}}
                    <div class="bg-gray-50 rounded-lg p-5 mb-8 flex items-center gap-4">
                        @if($sponsorship->horse->photos->isNotEmpty())
                            <img
                                src="{{ asset('storage/' . $sponsorship->horse->photos->first()->path) }}"
                                alt="{{ $sponsorship->horse->name }}"
                                class="w-16 h-16 object-cover rounded-full"
                            >
                        @else
                            <div class="w-16 h-16 bg-gray-200 rounded-full flex items-center justify-center">
                                <span class="text-gray-400 text-xs">No photo</span>
                            </div>
                        @endif
                        <div>
                            <p class="font-semibold text-gray-900">{{ $sponsorship->horse->name }}</p>
                            <p class="text-sm text-gray-500">
                                &pound;{{ number_format($sponsorship->monthly_amount / 100, 2) }}/month
                                @if($sponsorship->child_name)
                                    &middot; For {{ $sponsorship->child_name }}
                                @endif
                            </p>
                            <p class="text-sm text-gray-500">Sponsored since {{ $sponsorship->created_at->format('j F Y') }}</p>
                        </div>
                    </div>

                    {{-- What happens next --}}
                    <div class="mb-8">
                        <h4 class="font-semibold text-gray-900 mb-3">What happens if you cancel:</h4>
                        <ul class="space-y-2 text-sm text-gray-600">
                            <li class="flex items-start gap-2">
                                <svg class="h-5 w-5 text-gray-400 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Your sponsorship will continue until the end of your current billing period.
                            </li>
                            <li class="flex items-start gap-2">
                                <svg class="h-5 w-5 text-gray-400 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                </svg>
                                You'll lose access to {{ $sponsorship->horse->name }}'s updates after that date.
                            </li>
                            <li class="flex items-start gap-2">
                                <svg class="h-5 w-5 text-gray-400 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" />
                                </svg>
                                No further payments will be taken.
                            </li>
                            <li class="flex items-start gap-2">
                                <svg class="h-5 w-5 text-gray-400 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                                </svg>
                                You can always re-sponsor {{ $sponsorship->horse->name }} in the future.
                            </li>
                        </ul>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="flex flex-col sm:flex-row gap-3">
                        <a href="{{ route('sponsor.dashboard') }}" class="flex-1 inline-flex justify-center items-center px-5 py-3 bg-gray-100 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-200 transition-colors">
                            Keep My Sponsorship
                        </a>
                        <form method="POST" action="{{ route('sponsor.sponsorship.cancel', $sponsorship) }}" class="flex-1">
                            @csrf
                            <button type="submit" class="w-full inline-flex justify-center items-center px-5 py-3 bg-red-600 text-white text-sm font-semibold rounded-lg hover:bg-red-700 transition-colors">
                                Yes, Cancel Sponsorship
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
