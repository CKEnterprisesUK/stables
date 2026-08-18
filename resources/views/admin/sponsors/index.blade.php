@extends('layouts.admin')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-stable-900">Sponsors</h1>
        <p class="mt-1 text-sm text-stable-500">View and manage all sponsor subscriptions.</p>
    </div>

    <!-- Desktop table view -->
    <div class="hidden md:block bg-white shadow-sm border border-stable-200 rounded-xl overflow-hidden">
        <table class="min-w-full divide-y divide-stable-200">
            <thead class="bg-stable-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-stable-500 uppercase tracking-wider">Sponsor</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-stable-500 uppercase tracking-wider">Horse</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-stable-500 uppercase tracking-wider">Amount</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-stable-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-stable-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-stable-100">
                @forelse ($sponsors as $sponsor)
                    @foreach ($sponsor->sponsorships as $sponsorship)
                        <tr class="hover:bg-stable-50 transition-colors">
                            <td class="px-6 py-4">
                                <p class="text-sm font-medium text-stable-900">{{ $sponsor->name }}</p>
                                <p class="text-xs text-stable-500">{{ $sponsor->email }}</p>
                            </td>
                            <td class="px-6 py-4 text-sm text-stable-900">
                                {{ $sponsorship->horse->name ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 text-sm font-medium text-stable-900">
                                &pound;{{ number_format($sponsorship->monthly_amount / 100, 2) }}/mo
                            </td>
                            <td class="px-6 py-4">
                                @if ($sponsorship->status->value === 'active')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-brand-50 text-brand-700 ring-1 ring-brand-200">
                                        <span class="h-1.5 w-1.5 rounded-full bg-brand-500"></span>
                                        Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-stable-100 text-stable-600 ring-1 ring-stable-200">
                                        <span class="h-1.5 w-1.5 rounded-full bg-stable-400"></span>
                                        Cancelled
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                @if ($sponsorship->status->value === 'active')
                                    <form action="{{ route('admin.sponsorship.cancel', $sponsorship) }}" method="POST" class="inline" onsubmit="return confirm('Cancel this sponsorship? The sponsor will be notified.')">
                                        @csrf
                                        <button type="submit" class="text-sm font-medium text-red-600 hover:text-red-800 transition-colors">Cancel</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    @if ($sponsor->sponsorships->isEmpty())
                        <tr class="hover:bg-stable-50 transition-colors">
                            <td class="px-6 py-4">
                                <p class="text-sm font-medium text-stable-900">{{ $sponsor->name }}</p>
                                <p class="text-xs text-stable-500">{{ $sponsor->email }}</p>
                            </td>
                            <td colspan="4" class="px-6 py-4 text-sm text-stable-400 italic">
                                No sponsorships
                            </td>
                        </tr>
                    @endif
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <svg class="mx-auto h-12 w-12 text-stable-300" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                            </svg>
                            <p class="mt-4 text-sm text-stable-500">No sponsors found yet.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Mobile card view -->
    <div class="md:hidden space-y-3">
        @forelse ($sponsors as $sponsor)
            @foreach ($sponsor->sponsorships as $sponsorship)
                <div class="bg-white border border-stable-200 rounded-xl p-4 shadow-sm">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="text-sm font-semibold text-stable-900">{{ $sponsor->name }}</p>
                            <p class="text-xs text-stable-500">{{ $sponsor->email }}</p>
                        </div>
                        @if ($sponsorship->status->value === 'active')
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-brand-50 text-brand-700 ring-1 ring-brand-200">
                                <span class="h-1.5 w-1.5 rounded-full bg-brand-500"></span>
                                Active
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-stable-100 text-stable-600">
                                Cancelled
                            </span>
                        @endif
                    </div>
                    <div class="mt-3 flex items-center justify-between pt-3 border-t border-stable-100">
                        <div class="text-sm">
                            <span class="text-stable-500">{{ $sponsorship->horse->name ?? 'N/A' }}</span>
                            <span class="mx-2 text-stable-300">&middot;</span>
                            <span class="font-medium text-stable-900">&pound;{{ number_format($sponsorship->monthly_amount / 100, 2) }}/mo</span>
                        </div>
                        @if ($sponsorship->status->value === 'active')
                            <form action="{{ route('admin.sponsorship.cancel', $sponsorship) }}" method="POST" class="inline" onsubmit="return confirm('Cancel this sponsorship?')">
                                @csrf
                                <button type="submit" class="text-xs font-medium text-red-600 hover:text-red-800">Cancel</button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        @empty
            <div class="bg-white border border-stable-200 rounded-xl p-8 text-center shadow-sm">
                <p class="text-sm text-stable-500">No sponsors found yet.</p>
            </div>
        @endforelse
    </div>
@endsection
