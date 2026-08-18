@extends('layouts.admin')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">{{ $horse->name }}</h1>
        <div class="flex gap-2">
            <a href="{{ route('admin.horses.edit', $horse) }}"
               class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700">
                Edit
            </a>
            <a href="{{ route('admin.horses.index') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-300">
                Back to List
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Horse details --}}
        <div class="lg:col-span-2">
            <div class="bg-white shadow rounded-lg p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Details</h2>
                @if ($horse->facts)
                    <div class="prose max-w-none text-gray-700">
                        {!! nl2br(e($horse->facts)) !!}
                    </div>
                @else
                    <p class="text-gray-500 italic">No facts added yet.</p>
                @endif
            </div>

            {{-- Photos --}}
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Photos ({{ $horse->photos->count() }})</h2>
                @if ($horse->photos->count() > 0)
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                        @foreach ($horse->photos as $photo)
                            <img src="{{ asset('storage/' . $photo->path) }}"
                                 alt="{{ $horse->name }}"
                                 class="h-40 w-full rounded-md object-cover">
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 italic">No photos uploaded yet.</p>
                @endif
            </div>
        </div>

        {{-- Active sponsorships --}}
        <div>
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Active Sponsorships</h2>
                @php
                    $activeSponsorships = $horse->sponsorships->where('status', \App\Enums\SponsorshipStatus::Active);
                @endphp
                @if ($activeSponsorships->count() > 0)
                    <ul class="divide-y divide-gray-200">
                        @foreach ($activeSponsorships as $sponsorship)
                            <li class="py-3">
                                <p class="text-sm font-medium text-gray-900">{{ $sponsorship->user->name ?? 'Unknown' }}</p>
                                <p class="text-xs text-gray-500">{{ $sponsorship->user->email ?? '' }}</p>
                                <p class="text-xs text-gray-500 mt-1">
                                    &pound;{{ number_format($sponsorship->monthly_amount / 100, 2) }}/month
                                    @if ($sponsorship->child_name)
                                        &middot; For: {{ $sponsorship->child_name }}
                                    @endif
                                </p>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-gray-500 text-sm italic">No active sponsorships.</p>
                @endif
            </div>
        </div>
    </div>
@endsection
