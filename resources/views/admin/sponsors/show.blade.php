@extends('layouts.admin')

@section('content')
    <div class="mb-6">
        <a href="{{ route('admin.sponsors.index') }}" class="inline-flex items-center text-sm text-stable-500 hover:text-stable-700 mb-3">
            <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
            Back to Sponsors
        </a>
        <h1 class="text-2xl font-bold text-stable-900">{{ $user->name }}</h1>
        <p class="mt-1 text-sm text-stable-500">{{ $user->email }}</p>
    </div>

    <div class="space-y-6">
        <!-- Sponsor Info Card -->
        <div class="bg-white shadow-sm border border-stable-200 rounded-xl p-6">
            <h2 class="text-lg font-semibold text-stable-900 mb-4">Sponsor Details</h2>
            <dl class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm">
                <div>
                    <dt class="text-stable-500">Member Since</dt>
                    <dd class="font-medium text-stable-900">{{ $user->created_at->format('j F Y') }}</dd>
                </div>
                <div>
                    <dt class="text-stable-500">Active Sponsorships</dt>
                    <dd class="font-medium text-stable-900">{{ $user->sponsorships->where('status.value', 'active')->count() }}</dd>
                </div>
                <div>
                    <dt class="text-stable-500">Total Invoices</dt>
                    <dd class="font-medium text-stable-900">{{ $user->invoices->count() }}</dd>
                </div>
            </dl>
        </div>

        <!-- Sponsorships -->
        <div class="bg-white shadow-sm border border-stable-200 rounded-xl p-6">
            <h2 class="text-lg font-semibold text-stable-900 mb-4">Sponsorships</h2>

            @if($user->sponsorships->isEmpty())
                <p class="text-sm text-stable-500 italic">No sponsorships found.</p>
            @else
                <div class="space-y-3">
                    @foreach($user->sponsorships as $sponsorship)
                        <div class="flex items-center justify-between p-4 rounded-lg border border-stable-100 {{ $sponsorship->status->value === 'active' ? 'bg-white' : 'bg-stable-50' }}">
                            <div class="flex items-center gap-4">
                                @if($sponsorship->horse && $sponsorship->horse->photos->isNotEmpty())
                                    <img src="{{ asset('storage/' . $sponsorship->horse->photos->first()->path) }}"
                                         alt="{{ $sponsorship->horse->name }}"
                                         class="h-10 w-10 rounded-full object-cover">
                                @else
                                    <div class="h-10 w-10 rounded-full bg-stable-200 flex items-center justify-center">
                                        <span class="text-stable-400 text-xs">N/A</span>
                                    </div>
                                @endif
                                <div>
                                    <p class="text-sm font-medium text-stable-900">{{ $sponsorship->horse->name ?? 'Unknown' }}</p>
                                    <p class="text-xs text-stable-500">
                                        &pound;{{ number_format($sponsorship->monthly_amount / 100, 2) }}/mo
                                        @if($sponsorship->child_name)
                                            &middot; For {{ $sponsorship->child_name }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                @if($sponsorship->status->value === 'active')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-brand-50 text-brand-700 ring-1 ring-brand-200">
                                        <span class="h-1.5 w-1.5 rounded-full bg-brand-500"></span>
                                        Active
                                    </span>
                                    <form action="{{ route('admin.sponsorship.cancel', $sponsorship) }}" method="POST" class="inline" onsubmit="return confirm('Cancel this sponsorship? The sponsor will be notified.')">
                                        @csrf
                                        <button type="submit" class="text-xs font-medium text-red-600 hover:text-red-800">Cancel</button>
                                    </form>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-stable-100 text-stable-600 ring-1 ring-stable-200">
                                        <span class="h-1.5 w-1.5 rounded-full bg-stable-400"></span>
                                        Cancelled
                                    </span>
                                    @if($sponsorship->ends_at)
                                        <span class="text-xs text-stable-500">Ended {{ $sponsorship->ends_at->format('j M Y') }}</span>
                                    @endif
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Invoice History -->
        <div class="bg-white shadow-sm border border-stable-200 rounded-xl p-6">
            <h2 class="text-lg font-semibold text-stable-900 mb-4">Invoice History</h2>

            @if($user->invoices->isEmpty())
                <p class="text-sm text-stable-500 italic">No invoices recorded yet.</p>
            @else
                <!-- Desktop table -->
                <div class="hidden md:block overflow-hidden rounded-lg border border-stable-100">
                    <table class="min-w-full divide-y divide-stable-200">
                        <thead class="bg-stable-50">
                            <tr>
                                <th class="px-4 py-2.5 text-left text-xs font-semibold text-stable-500 uppercase tracking-wider">Date</th>
                                <th class="px-4 py-2.5 text-left text-xs font-semibold text-stable-500 uppercase tracking-wider">Horse</th>
                                <th class="px-4 py-2.5 text-left text-xs font-semibold text-stable-500 uppercase tracking-wider">Amount</th>
                                <th class="px-4 py-2.5 text-left text-xs font-semibold text-stable-500 uppercase tracking-wider">Status</th>
                                <th class="px-4 py-2.5 text-right text-xs font-semibold text-stable-500 uppercase tracking-wider">Links</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stable-100">
                            @foreach($user->invoices as $invoice)
                                <tr class="hover:bg-stable-50">
                                    <td class="px-4 py-3 text-sm text-stable-900">{{ $invoice->invoice_date->format('j M Y') }}</td>
                                    <td class="px-4 py-3 text-sm text-stable-900">{{ $invoice->sponsorship?->horse?->name ?? '—' }}</td>
                                    <td class="px-4 py-3 text-sm font-medium text-stable-900">{{ $invoice->formatted_amount }}</td>
                                    <td class="px-4 py-3">
                                        @if($invoice->status === 'paid')
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-brand-50 text-brand-700 ring-1 ring-brand-200">
                                                Paid
                                            </span>
                                        @elseif($invoice->status === 'open')
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-saddle-50 text-saddle-700 ring-1 ring-saddle-200">
                                                Open
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-stable-100 text-stable-600">
                                                {{ ucfirst($invoice->status) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-right space-x-2">
                                        @if($invoice->hosted_invoice_url)
                                            <a href="{{ $invoice->hosted_invoice_url }}" target="_blank" rel="noopener"
                                               class="text-xs font-medium text-brand-600 hover:text-brand-800">View</a>
                                        @endif
                                        @if($invoice->pdf_url)
                                            <a href="{{ $invoice->pdf_url }}" target="_blank" rel="noopener"
                                               class="text-xs font-medium text-brand-600 hover:text-brand-800">PDF</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Mobile card view -->
                <div class="md:hidden space-y-3">
                    @foreach($user->invoices as $invoice)
                        <div class="p-3 rounded-lg border border-stable-100">
                            <div class="flex items-start justify-between">
                                <div>
                                    <p class="text-sm font-medium text-stable-900">{{ $invoice->formatted_amount }}</p>
                                    <p class="text-xs text-stable-500">{{ $invoice->invoice_date->format('j M Y') }} &middot; {{ $invoice->sponsorship?->horse?->name ?? '—' }}</p>
                                </div>
                                @if($invoice->status === 'paid')
                                    <span class="text-xs font-medium text-brand-700">Paid</span>
                                @else
                                    <span class="text-xs font-medium text-stable-500">{{ ucfirst($invoice->status) }}</span>
                                @endif
                            </div>
                            <div class="mt-2 flex gap-3">
                                @if($invoice->hosted_invoice_url)
                                    <a href="{{ $invoice->hosted_invoice_url }}" target="_blank" class="text-xs text-brand-600 hover:text-brand-800">View</a>
                                @endif
                                @if($invoice->pdf_url)
                                    <a href="{{ $invoice->pdf_url }}" target="_blank" class="text-xs text-brand-600 hover:text-brand-800">PDF</a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection
