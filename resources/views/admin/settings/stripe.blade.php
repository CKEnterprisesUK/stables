@extends('layouts.admin')

@section('content')
<div class="max-w-2xl">
    <h1 class="text-2xl font-semibold text-gray-900 mb-6">Stripe Settings</h1>

    @if (session('status'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-md">
            <p class="text-sm text-green-700">{{ session('status') }}</p>
        </div>
    @endif

    @if (session('error'))
        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-md">
            <p class="text-sm text-red-700">{{ session('error') }}</p>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.settings.stripe.update') }}" class="bg-white shadow rounded-md p-6 space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label for="stripe_key" class="block text-sm font-medium text-gray-700">Publishable Key</label>
            <input type="text" name="stripe_key" id="stripe_key"
                value="{{ old('stripe_key', $settings?->stripe_key) }}"
                placeholder="pk_test_..."
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                required>
            @error('stripe_key')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="stripe_secret" class="block text-sm font-medium text-gray-700">Secret Key</label>
            <input type="password" name="stripe_secret" id="stripe_secret"
                placeholder="{{ $settings ? '••••••••' : 'sk_test_...' }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                {{ $settings ? '' : 'required' }}>
            @if ($settings)
                <p class="mt-1 text-xs text-gray-500">Leave blank to keep the current secret key.</p>
            @endif
            @error('stripe_secret')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="webhook_secret" class="block text-sm font-medium text-gray-700">Webhook Secret</label>
            <input type="password" name="webhook_secret" id="webhook_secret"
                placeholder="{{ $settings ? '••••••••' : 'whsec_...' }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                {{ $settings ? '' : 'required' }}>
            @if ($settings)
                <p class="mt-1 text-xs text-gray-500">Leave blank to keep the current webhook secret.</p>
            @endif
            @error('webhook_secret')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        @if ($settings?->price_id)
            <div>
                <label class="block text-sm font-medium text-gray-700">Price ID</label>
                <p class="mt-1 text-sm text-gray-900 font-mono bg-gray-50 rounded-md px-3 py-2 border border-gray-200">
                    {{ $settings->price_id }}
                </p>
                <p class="mt-1 text-xs text-gray-500">This is the per-unit monthly price used for sponsorships.</p>
            </div>
        @endif

        <div class="pt-4">
            <button type="submit"
                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-wider hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                Save Settings
            </button>
        </div>
    </form>

    @if ($settings && !$settings->price_id)
    <div class="mt-6 bg-white shadow rounded-md p-6">
        <h2 class="text-lg font-medium text-gray-900 mb-4">Create Product & Price</h2>
        <p class="text-sm text-gray-600 mb-4">
            Create a Stripe Product ("Sponsorship Unit") and a recurring monthly Price
            (1 {{ strtoupper(config('cashier.currency', 'eur')) }}/month per unit).
            Sponsors choose how many units to purchase each month.
        </p>
        <form method="POST" action="{{ route('admin.settings.stripe.create-product') }}">
            @csrf
            <button type="submit"
                class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-wider hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                Create Product & Price
            </button>
        </form>
    </div>
    @endif
</div>
@endsection
