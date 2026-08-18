@extends('layouts.admin')

@section('content')
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <a href="{{ route('admin.horses.index') }}" class="inline-flex items-center gap-1 text-sm text-stable-500 hover:text-stable-700 transition-colors mb-2">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                </svg>
                Back to Horses
            </a>
            <h1 class="text-2xl font-bold text-stable-900">{{ $horse->name }}</h1>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.horses.updates.create', $horse) }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-saddle-600 text-white text-sm font-medium rounded-lg hover:bg-saddle-700 shadow-sm transition-colors">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Post Update
            </a>
            <a href="{{ route('admin.horses.edit', $horse) }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-stable-100 text-stable-700 text-sm font-medium rounded-lg hover:bg-stable-200 border border-stable-300 shadow-sm transition-colors">
                Edit
            </a>
        </div>
    </div>

    <!-- Updates section -->
    @if($horse->updates->count() > 0)
    <div class="bg-white shadow-sm border border-stable-200 rounded-xl p-6 mb-6">
        <h2 class="text-lg font-semibold text-stable-900 mb-4">Updates</h2>
        <div class="divide-y divide-stable-100">
            @foreach($horse->updates as $update)
                <div class="py-4 first:pt-0 last:pb-0 flex items-center justify-between gap-4">
                    <div class="min-w-0">
                        <p class="text-sm font-medium text-stable-900 truncate">{{ $update->title }}</p>
                        <p class="text-xs text-stable-500">Posted {{ $update->created_at->format('j M Y, g:ia') }}</p>
                    </div>
                    <form action="{{ route('admin.horses.updates.notify', [$horse, $update]) }}" method="POST" class="shrink-0">
                        @csrf
                        <button type="submit"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-brand-700 bg-brand-50 rounded-lg hover:bg-brand-100 border border-brand-200 transition-colors"
                                onclick="return confirm('Send this update to all active sponsors of {{ $horse->name }}?')">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" />
                            </svg>
                            Send to Sponsors
                        </button>
                    </form>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Horse details -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white shadow-sm border border-stable-200 rounded-xl p-6">
                <h2 class="text-lg font-semibold text-stable-900 mb-4">Facts</h2>
                @if ($horse->facts)
                    @php
                        $factLines = array_filter(array_map('trim', explode("\n", $horse->facts)));
                    @endphp
                    <ul class="space-y-2">
                        @foreach ($factLines as $fact)
                            <li class="flex items-start gap-2 text-sm text-stable-700">
                                <svg class="h-4 w-4 mt-0.5 shrink-0 text-brand-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ $fact }}
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-stable-500 italic text-sm">No facts added yet.</p>
                @endif
            </div>

            <!-- Photos - compact thumbnail gallery -->
            <div class="bg-white shadow-sm border border-stable-200 rounded-xl p-6">
                <h2 class="text-lg font-semibold text-stable-900 mb-4">Photos ({{ $horse->photos->count() }})</h2>
                @if ($horse->photos->count() > 0)
                    <div x-data="{ selected: 0 }">
                        <!-- Main selected image -->
                        <div class="mb-3 rounded-lg overflow-hidden ring-1 ring-stable-200">
                            @foreach ($horse->photos as $index => $photo)
                                <img x-show="selected === {{ $index }}"
                                     src="{{ asset('storage/' . $photo->path) }}"
                                     alt="{{ $horse->name }}"
                                     class="w-full aspect-[16/9] object-cover">
                            @endforeach
                        </div>
                        <!-- Thumbnail strip -->
                        @if($horse->photos->count() > 1)
                            <div class="flex gap-2 overflow-x-auto pb-1">
                                @foreach ($horse->photos as $index => $photo)
                                    <button @click="selected = {{ $index }}"
                                            :class="selected === {{ $index }} ? 'ring-2 ring-brand-500' : 'ring-1 ring-stable-200 opacity-70 hover:opacity-100'"
                                            class="shrink-0 w-16 h-16 rounded-lg overflow-hidden transition-all">
                                        <img src="{{ asset('storage/' . $photo->path) }}"
                                             alt="{{ $horse->name }}"
                                             class="w-full h-full object-cover">
                                    </button>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @else
                    <p class="text-stable-500 italic text-sm">No photos uploaded yet.</p>
                @endif
            </div>
        </div>

        <!-- Active sponsorships -->
        <div>
            <div class="bg-white shadow-sm border border-stable-200 rounded-xl p-6">
                <h2 class="text-lg font-semibold text-stable-900 mb-4">Active Sponsorships</h2>
                @php
                    $activeSponsorships = $horse->sponsorships->where('status', \App\Enums\SponsorshipStatus::Active);
                @endphp
                @if ($activeSponsorships->count() > 0)
                    <ul class="divide-y divide-stable-100">
                        @foreach ($activeSponsorships as $sponsorship)
                            <li class="py-3 first:pt-0 last:pb-0">
                                <p class="text-sm font-medium text-stable-900">{{ $sponsorship->user->name ?? 'Unknown' }}</p>
                                <p class="text-xs text-stable-500">{{ $sponsorship->user->email ?? '' }}</p>
                                <p class="text-xs text-stable-500 mt-1">
                                    &pound;{{ number_format($sponsorship->monthly_amount / 100, 2) }}/month
                                    @if ($sponsorship->child_name)
                                        &middot; For: {{ $sponsorship->child_name }}
                                    @endif
                                </p>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-stable-500 text-sm italic">No active sponsorships.</p>
                @endif
            </div>
        </div>
    </div>
@endsection
