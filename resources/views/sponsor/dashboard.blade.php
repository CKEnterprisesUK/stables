<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Portal') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
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

            {{-- ===== MANAGE SPONSORSHIPS SECTION (Top) ===== --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">My Sponsorships</h3>
                        <div class="flex gap-2">
                            <a href="{{ route('sponsor.finance') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium text-indigo-700 bg-indigo-50 rounded-md hover:bg-indigo-100 transition-colors">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                </svg>
                                Payments
                            </a>
                            <a href="{{ route('sponsor.sponsorship.create') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700 transition-colors">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                </svg>
                                Sponsor Another
                            </a>
                        </div>
                    </div>

                    @if($sponsorships->isEmpty())
                        <div class="text-center py-6 text-gray-500">
                            <p>You don't have any sponsorships yet.</p>
                            <a href="{{ route('stables') }}" class="mt-3 inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700">
                                Browse Horses
                            </a>
                        </div>
                    @else
                        <div class="divide-y divide-gray-100">
                            @foreach($sponsorships as $sponsorship)
                                <div class="py-4 first:pt-0 last:pb-0">
                                    <div class="flex items-center gap-4">
                                        {{-- Horse Photo --}}
                                        <div class="flex-shrink-0">
                                            @if($sponsorship->horse->photos->isNotEmpty())
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
                                                <h4 class="font-semibold text-gray-900 truncate">{{ $sponsorship->horse->name }}</h4>
                                                @if($sponsorship->status->value === 'active')
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Active</span>
                                                @else
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">Cancelled</span>
                                                @endif
                                            </div>
                                            <p class="text-sm text-gray-500">
                                                &pound;{{ number_format($sponsorship->monthly_amount / 100, 2) }}/month
                                                @if($sponsorship->child_name)
                                                    &middot; For {{ $sponsorship->child_name }}
                                                @endif
                                            </p>
                                        </div>

                                        {{-- Actions --}}
                                        <div class="flex items-center gap-2 flex-shrink-0">
                                            @if($sponsorship->status->value === 'active')
                                                @if(Route::has('sponsor.certificate'))
                                                    <a href="{{ route('sponsor.certificate', $sponsorship) }}" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium" title="View Certificate">
                                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                                        </svg>
                                                    </a>
                                                @endif
                                                <a href="{{ route('sponsor.sponsorship.cancel.confirm', $sponsorship) }}" class="text-sm text-red-600 hover:text-red-800 font-medium" title="Cancel">
                                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                </a>
                                            @endif
                                            @if($sponsorship->ends_at)
                                                <span class="text-xs text-gray-400">Ends {{ $sponsorship->ends_at->format('j M Y') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            {{-- ===== HORSE UPDATES FEED (Facebook-style) ===== --}}
            @if($sponsorships->where('status.value', 'active')->isNotEmpty())
                <div class="space-y-6">
                    <h3 class="text-lg font-semibold text-gray-900 px-1">Latest Updates</h3>

                    @if($feed instanceof \Illuminate\Pagination\LengthAwarePaginator && $feed->isEmpty())
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-8 text-center text-gray-500">
                                <svg class="mx-auto h-12 w-12 text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 01-2.25 2.25M16.5 7.5V18a2.25 2.25 0 002.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 002.25 2.25h13.5M6 7.5h3v3H6V7.5z"/>
                                </svg>
                                <p class="text-lg">No updates yet.</p>
                                <p class="mt-1 text-sm">Check back soon for news from your horse!</p>
                            </div>
                        </div>
                    @elseif($feed instanceof \Illuminate\Pagination\LengthAwarePaginator)
                        @foreach($feed as $update)
                            <article class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                                {{-- Post Header (like Facebook) --}}
                                <div class="px-6 pt-5 pb-3 flex items-center gap-3">
                                    @if($update->horse->photos->isNotEmpty())
                                        <img
                                            src="{{ asset('storage/' . $update->horse->photos->first()->path) }}"
                                            alt="{{ $update->horse->name }}"
                                            class="w-10 h-10 object-cover rounded-full"
                                        >
                                    @else
                                        <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center">
                                            <span class="text-gray-400 text-[10px]">🐴</span>
                                        </div>
                                    @endif
                                    <div class="flex-1 min-w-0">
                                        <h4 class="font-semibold text-gray-900 text-sm">{{ $update->horse->name }}</h4>
                                        <time class="text-xs text-gray-500">{{ $update->created_at->diffForHumans() }}</time>
                                    </div>
                                </div>

                                {{-- Post Content --}}
                                <div class="px-6 pb-4">
                                    <h5 class="font-semibold text-gray-900 mb-2">{{ $update->title }}</h5>
                                    @if($update->body)
                                        <div class="text-gray-700 text-sm leading-relaxed">
                                            {!! nl2br(e($update->body)) !!}
                                        </div>
                                    @endif
                                </div>

                                {{-- Post Photos --}}
                                @if($update->photos->isNotEmpty())
                                    <div class="@if($update->photos->count() === 1) px-0 @else px-6 pb-4 @endif">
                                        @if($update->photos->count() === 1)
                                            <img
                                                src="{{ asset('storage/' . $update->photos->first()->path) }}"
                                                alt="Update photo"
                                                class="w-full aspect-[16/9] object-cover"
                                            >
                                        @elseif($update->photos->count() === 2)
                                            <div class="grid grid-cols-2 gap-1 rounded-lg overflow-hidden">
                                                @foreach($update->photos as $photo)
                                                    <img
                                                        src="{{ asset('storage/' . $photo->path) }}"
                                                        alt="Update photo"
                                                        class="w-full aspect-square object-cover"
                                                    >
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="grid grid-cols-3 gap-1 rounded-lg overflow-hidden">
                                                @foreach($update->photos as $photo)
                                                    <img
                                                        src="{{ asset('storage/' . $photo->path) }}"
                                                        alt="Update photo"
                                                        class="w-full aspect-square object-cover"
                                                    >
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                @endif

                                {{-- Post Footer (date in full) --}}
                                <div class="px-6 py-3 border-t border-gray-100">
                                    <span class="text-xs text-gray-400">{{ $update->created_at->format('l, j F Y \a\t g:ia') }}</span>
                                </div>
                            </article>
                        @endforeach

                        {{-- Pagination --}}
                        <div class="mt-4">
                            {{ $feed->links() }}
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
