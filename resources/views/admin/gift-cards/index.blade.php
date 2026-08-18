@extends('layouts.admin')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-stable-900">Gift Cards</h1>
        <p class="mt-1 text-sm text-stable-500">View all gift sponsorship purchases and their redemption status.</p>
    </div>

    <!-- Desktop table view -->
    <div class="hidden md:block bg-white shadow-sm border border-stable-200 rounded-xl overflow-hidden">
        <table class="min-w-full divide-y divide-stable-200">
            <thead class="bg-stable-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-stable-500 uppercase tracking-wider">Code</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-stable-500 uppercase tracking-wider">Horse</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-stable-500 uppercase tracking-wider">Purchaser</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-stable-500 uppercase tracking-wider">Duration</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-stable-500 uppercase tracking-wider">Amount</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-stable-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-stable-500 uppercase tracking-wider">Purchased</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-stable-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-stable-100">
                @forelse ($gifts as $gift)
                    <tr class="hover:bg-stable-50 transition-colors">
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.gift-cards.show', $gift) }}" class="font-mono text-sm text-brand-700 hover:underline">
                                {{ $gift->code }}
                            </a>
                        </td>
                        <td class="px-6 py-4 text-sm text-stable-900">
                            {{ $gift->horse->name ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm font-medium text-stable-900">{{ $gift->purchaser_name }}</p>
                            <p class="text-xs text-stable-500">{{ $gift->purchaser_email }}</p>
                        </td>
                        <td class="px-6 py-4 text-sm text-stable-900">
                            {{ $gift->months }} month{{ $gift->months > 1 ? 's' : '' }}
                        </td>
                        <td class="px-6 py-4 text-sm font-medium text-stable-900">
                            &pound;{{ number_format($gift->amount_paid / 100, 2) }}
                        </td>
                        <td class="px-6 py-4">
                            @if ($gift->status->value === 'redeemed')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-green-50 text-green-700 ring-1 ring-green-200">
                                    <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span>
                                    Redeemed
                                </span>
                            @elseif ($gift->status->value === 'purchased')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-amber-50 text-amber-700 ring-1 ring-amber-200">
                                    <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                                    Unused
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-stable-100 text-stable-600 ring-1 ring-stable-200">
                                    <span class="h-1.5 w-1.5 rounded-full bg-stable-400"></span>
                                    Expired
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-stable-500">
                            {{ $gift->created_at->format('j M Y') }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-3">
                                <a href="{{ route('admin.gift-cards.show', $gift) }}" class="text-sm font-medium text-brand-600 hover:text-brand-800 transition-colors">
                                    View
                                </a>
                                @if ($gift->status->value === 'purchased')
                                    <form action="{{ route('admin.gift-cards.resend', $gift) }}" method="POST" class="inline" onsubmit="return confirm('Resend the gift card email to {{ $gift->purchaser_email }}?')">
                                        @csrf
                                        <button type="submit" class="text-sm font-medium text-indigo-600 hover:text-indigo-800 transition-colors">
                                            Resend
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center">
                            <svg class="mx-auto h-12 w-12 text-stable-300" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 11.25v8.25a1.5 1.5 0 01-1.5 1.5H5.25a1.5 1.5 0 01-1.5-1.5v-8.25M12 4.875A2.625 2.625 0 109.375 7.5H12m0-2.625V7.5m0-2.625A2.625 2.625 0 1114.625 7.5H12m0 0V21m-8.625-9.75h18c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125h-18c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                            </svg>
                            <p class="mt-4 text-sm text-stable-500">No gift cards purchased yet.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Mobile card view -->
    <div class="md:hidden space-y-3">
        @forelse ($gifts as $gift)
            <div class="bg-white border border-stable-200 rounded-xl p-4 shadow-sm">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <a href="{{ route('admin.gift-cards.show', $gift) }}" class="font-mono text-sm font-semibold text-brand-700 hover:underline">
                            {{ $gift->code }}
                        </a>
                        <p class="mt-1 text-sm text-stable-900">{{ $gift->horse->name ?? 'N/A' }}</p>
                    </div>
                    @if ($gift->status->value === 'redeemed')
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-green-50 text-green-700 ring-1 ring-green-200">
                            <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span>
                            Redeemed
                        </span>
                    @elseif ($gift->status->value === 'purchased')
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-amber-50 text-amber-700 ring-1 ring-amber-200">
                            <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                            Unused
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-stable-100 text-stable-600">
                            Expired
                        </span>
                    @endif
                </div>
                <div class="mt-3 pt-3 border-t border-stable-100">
                    <div class="flex items-center justify-between text-sm">
                        <div>
                            <span class="text-stable-500">{{ $gift->purchaser_name }}</span>
                            <span class="mx-1 text-stable-300">&middot;</span>
                            <span class="font-medium text-stable-900">&pound;{{ number_format($gift->amount_paid / 100, 2) }}</span>
                            <span class="mx-1 text-stable-300">&middot;</span>
                            <span class="text-stable-500">{{ $gift->months }}mo</span>
                        </div>
                        <div class="flex items-center gap-2">
                            @if ($gift->status->value === 'purchased')
                                <form action="{{ route('admin.gift-cards.resend', $gift) }}" method="POST" class="inline" onsubmit="return confirm('Resend?')">
                                    @csrf
                                    <button type="submit" class="text-xs font-medium text-indigo-600 hover:text-indigo-800">Resend</button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white border border-stable-200 rounded-xl p-8 text-center shadow-sm">
                <p class="text-sm text-stable-500">No gift cards purchased yet.</p>
            </div>
        @endforelse
    </div>
@endsection
