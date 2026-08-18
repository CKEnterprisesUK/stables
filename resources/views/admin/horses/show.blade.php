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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Horse details -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white shadow-sm border border-stable-200 rounded-xl p-6">
                <h2 class="text-lg font-semibold text-stable-900 mb-4">Details</h2>
                @if ($horse->facts)
                    <div class="prose prose-sm max-w-none text-stable-700">
                        {!! nl2br(e($horse->facts)) !!}
                    </div>
                @else
                    <p class="text-stable-500 italic text-sm">No facts added yet.</p>
                @endif
            </div>

            <!-- Photos -->
            <div class="bg-white shadow-sm border border-stable-200 rounded-xl p-6">
                <h2 class="text-lg font-semibold text-stable-900 mb-4">Photos ({{ $horse->photos->count() }})</h2>
                @if ($horse->photos->count() > 0)
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                        @foreach ($horse->photos as $photo)
                            <img src="{{ asset('storage/' . $photo->path) }}"
                                 alt="{{ $horse->name }}"
                                 class="aspect-[4/3] w-full rounded-lg object-cover ring-1 ring-stable-200">
                        @endforeach
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
