@extends('layouts.admin')

@section('content')
    <div class="mb-6">
        <a href="{{ route('admin.gift-cards.index') }}" class="inline-flex items-center gap-1 text-sm text-stable-500 hover:text-stable-700 transition-colors">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/>
            </svg>
            Back to Gift Cards
        </a>
    </div>

    <div class="bg-white shadow-sm border border-stable-200 rounded-xl overflow-hidden">
        <div class="p-6 md:p-8">
            <div class="flex items-start justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-stable-900">Gift Card Details</h1>
                    <p class="mt-1 font-mono text-lg text-brand-700">{{ $gift->code }}</p>
                </div>
                @if ($gift->status->value === 'redeemed')
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-medium bg-green-50 text-green-700 ring-1 ring-green-200">
                        <span class="h-2 w-2 rounded-full bg-green-500"></span>
                        Redeemed
                    </span>
                @elseif ($gift->status->value === 'purchased')
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-medium bg-amber-50 text-amber-700 ring-1 ring-amber-200">
                        <span class="h-2 w-2 rounded-full bg-amber-500"></span>
                        Unused
                    </span>
                @else
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-medium bg-stable-100 text-stable-600 ring-1 ring-stable-200">
                        <span class="h-2 w-2 rounded-full bg-stable-400"></span>
                        Expired
                    </span>
                @endif
            </div>

            <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Purchase Details -->
                <div>
                    <h3 class="text-sm font-semibold text-stable-500 uppercase tracking-wide">Purchase Details</h3>
                    <dl class="mt-3 space-y-3">
                        <div>
                            <dt class="text-xs text-stable-500">Purchaser</dt>
                            <dd class="text-sm font-medium text-stable-900">{{ $gift->purchaser_name }}</dd>
                            <dd class="text-xs text-stable-500">{{ $gift->purchaser_email }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs text-stable-500">Horse</dt>
                            <dd class="text-sm font-medium text-stable-900">{{ $gift->horse->name ?? 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs text-stable-500">Duration</dt>
                            <dd class="text-sm font-medium text-stable-900">{{ $gift->months }} month{{ $gift->months > 1 ? 's' : '' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs text-stable-500">Amount Paid</dt>
                            <dd class="text-sm font-medium text-stable-900">&pound;{{ number_format($gift->amount_paid / 100, 2) }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs text-stable-500">Purchased On</dt>
                            <dd class="text-sm font-medium text-stable-900">{{ $gift->created_at->format('j F Y \a\t H:i') }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs text-stable-500">Code Expires</dt>
                            <dd class="text-sm font-medium text-stable-900">{{ $gift->expires_at->format('j F Y') }}</dd>
                        </div>
                        @if($gift->stripe_payment_intent_id)
                            <div>
                                <dt class="text-xs text-stable-500">Stripe Payment Intent</dt>
                                <dd class="text-xs font-mono text-stable-600">{{ $gift->stripe_payment_intent_id }}</dd>
                            </div>
                        @endif
                    </dl>
                </div>

                <!-- Recipient & Redemption -->
                <div>
                    <h3 class="text-sm font-semibold text-stable-500 uppercase tracking-wide">Recipient & Redemption</h3>
                    <dl class="mt-3 space-y-3">
                        @if($gift->recipient_name)
                            <div>
                                <dt class="text-xs text-stable-500">Recipient Name</dt>
                                <dd class="text-sm font-medium text-stable-900">{{ $gift->recipient_name }}</dd>
                            </div>
                        @endif
                        @if($gift->recipient_message)
                            <div>
                                <dt class="text-xs text-stable-500">Gift Message</dt>
                                <dd class="text-sm text-stable-700 italic">"{{ $gift->recipient_message }}"</dd>
                            </div>
                        @endif

                        @if($gift->status->value === 'redeemed')
                            <div class="p-3 bg-green-50 rounded-lg border border-green-200">
                                <p class="text-xs font-medium text-green-700 uppercase tracking-wide">Redeemed</p>
                                @if($gift->redeemedBy)
                                    <p class="mt-1 text-sm font-medium text-green-900">{{ $gift->redeemedBy->name }}</p>
                                    <p class="text-xs text-green-700">{{ $gift->redeemedBy->email }}</p>
                                @endif
                                @if($gift->redeemed_at)
                                    <p class="mt-1 text-xs text-green-600">{{ $gift->redeemed_at->format('j F Y \a\t H:i') }}</p>
                                @endif
                            </div>
                        @else
                            <div class="p-3 bg-stable-50 rounded-lg border border-stable-200">
                                <p class="text-sm text-stable-600">Not yet redeemed.</p>
                            </div>
                        @endif
                    </dl>
                </div>
            </div>

            <!-- Actions -->
            <div class="mt-8 pt-6 border-t border-stable-200 flex flex-wrap gap-3">
                @if ($gift->status->value === 'purchased')
                    <form action="{{ route('admin.gift-cards.resend', $gift) }}" method="POST" onsubmit="return confirm('Resend the gift card email to {{ $gift->purchaser_email }}?')">
                        @csrf
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-brand-600 text-white font-medium text-sm rounded-lg hover:bg-brand-700 shadow-sm transition-colors">
                            <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                            </svg>
                            Resend Gift Card Email
                        </button>
                    </form>
                @endif
                <a href="{{ route('gift.download', $gift) }}" target="_blank" class="inline-flex items-center px-4 py-2 border border-stable-300 text-stable-700 font-medium text-sm rounded-lg hover:bg-stable-50 transition-colors">
                    <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                    </svg>
                    Download PDF
                </a>
            </div>
        </div>
    </div>
@endsection
