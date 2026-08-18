@extends('layouts.admin')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-stable-900">General Settings</h1>
        <p class="mt-1 text-sm text-stable-500">Configure sponsorship pricing and general options.</p>
    </div>

    <div class="max-w-2xl space-y-6">
        <!-- Sponsorship Pricing Card -->
        <div class="bg-white shadow-sm border border-stable-200 rounded-xl p-6">
            <div class="flex items-start gap-4">
                <div class="shrink-0 h-12 w-12 rounded-xl bg-brand-50 flex items-center justify-center">
                    <svg class="h-7 w-7 text-brand-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>

                <div class="flex-1 min-w-0">
                    <h2 class="text-lg font-semibold text-stable-900">Sponsorship Pricing</h2>
                    <p class="mt-1 text-sm text-stable-500">
                        Set the monthly amount that sponsors will be charged. All new sponsorships will use this price.
                    </p>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.settings.general.update') }}" class="mt-6 pt-4 border-t border-stable-100">
                @csrf
                @method('PUT')

                <div>
                    <label for="sponsorship_amount" class="block text-sm font-medium text-stable-700">
                        Monthly Sponsorship Price (&pound;)
                    </label>
                    <div class="mt-2 relative">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <span class="text-stable-500 sm:text-sm">&pound;</span>
                        </div>
                        <input type="number"
                               name="sponsorship_amount"
                               id="sponsorship_amount"
                               min="1"
                               step="0.01"
                               value="{{ old('sponsorship_amount', $settings?->sponsorship_amount ?? '') }}"
                               placeholder="e.g. 15.00"
                               class="block w-full rounded-lg border border-stable-300 pl-7 pr-12 py-2.5 text-stable-900 placeholder:text-stable-400 focus:ring-2 focus:ring-brand-500 focus:border-brand-500 sm:text-sm"
                               required>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                            <span class="text-stable-500 sm:text-sm">/month</span>
                        </div>
                    </div>
                    @error('sponsorship_amount')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-2 text-xs text-stable-500">This price will be shown to sponsors during signup. All sponsors pay the same monthly amount.</p>
                </div>

                <div class="mt-6 flex items-center gap-3">
                    <button type="submit"
                            class="inline-flex items-center gap-2 px-4 py-2.5 bg-brand-600 text-white text-sm font-medium rounded-lg hover:bg-brand-700 shadow-sm transition-colors">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                        </svg>
                        Save Pricing
                    </button>

                    @if($settings?->sponsorship_amount_cents)
                        <span class="text-sm text-stable-500">
                            Current: <strong>&pound;{{ number_format($settings->sponsorship_amount, 2) }}</strong>/month
                        </span>
                    @endif
                </div>
            </form>
        </div>

        <!-- Info box -->
        <div class="bg-stable-50 border border-stable-200 rounded-xl p-5">
            <div class="flex gap-3">
                <svg class="h-5 w-5 text-stable-400 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                </svg>
                <div class="text-sm text-stable-600">
                    <p class="font-medium text-stable-700 mb-1">About sponsorship pricing</p>
                    <p>The price you set here applies to all new sponsorships. Existing active sponsorships will continue at their original price until cancelled or updated via Stripe.</p>
                </div>
            </div>
        </div>
    </div>
@endsection
