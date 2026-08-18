@extends('layouts.admin')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-stable-900">Payments</h1>
        <p class="mt-1 text-sm text-stable-500">Connect your Stripe account to accept sponsorship payments.</p>
    </div>

    <div class="max-w-2xl space-y-6">
        <!-- Connection Status Card -->
        <div class="bg-white shadow-sm border border-stable-200 rounded-xl p-6">
            <div class="flex items-start gap-4">
                <!-- Stripe logo -->
                <div class="shrink-0 h-12 w-12 rounded-xl bg-purple-50 flex items-center justify-center">
                    <svg class="h-7 w-7 text-purple-600" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M13.976 9.15c-2.172-.806-3.356-1.426-3.356-2.409 0-.831.683-1.305 1.901-1.305 2.227 0 4.515.858 6.09 1.631l.89-5.494C18.252.975 15.697 0 12.165 0 9.667 0 7.589.654 6.104 1.872 4.56 3.147 3.757 4.992 3.757 7.218c0 4.039 2.467 5.76 6.476 7.219 2.585.92 3.445 1.574 3.445 2.583 0 .98-.84 1.545-2.354 1.545-1.875 0-4.965-.921-6.99-2.109l-.9 5.555C5.175 22.99 8.385 24 11.714 24c2.641 0 4.843-.624 6.328-1.813 1.664-1.305 2.525-3.236 2.525-5.732 0-4.128-2.524-5.851-6.591-7.305z"/>
                    </svg>
                </div>

                <div class="flex-1 min-w-0">
                    <h2 class="text-lg font-semibold text-stable-900">Stripe Connect</h2>

                    @if($settings && $settings->isConnected())
                        <div class="mt-2 flex items-center gap-2">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-brand-50 text-brand-700 ring-1 ring-brand-200">
                                <span class="h-1.5 w-1.5 rounded-full bg-brand-500"></span>
                                Connected
                            </span>
                        </div>
                        @if($accountDetails)
                            <div class="mt-3 p-3 bg-stable-50 rounded-lg border border-stable-100">
                                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-2 text-sm">
                                    @if($accountDetails->display_name ?? null)
                                        <div>
                                            <dt class="text-stable-500">Business Name</dt>
                                            <dd class="font-medium text-stable-900">{{ $accountDetails->display_name }}</dd>
                                        </div>
                                    @endif
                                    @if($accountDetails->contact_email ?? null)
                                        <div>
                                            <dt class="text-stable-500">Email</dt>
                                            <dd class="font-medium text-stable-900">{{ $accountDetails->contact_email }}</dd>
                                        </div>
                                    @endif
                                    <div>
                                        <dt class="text-stable-500">Account ID</dt>
                                        <dd class="font-mono text-xs text-stable-700">{{ $settings->stripe_account_id }}</dd>
                                    </div>
                                    @if($accountDetails->identity?->country ?? null)
                                        <div>
                                            <dt class="text-stable-500">Country</dt>
                                            <dd class="font-medium text-stable-900">{{ strtoupper($accountDetails->identity->country) }}</dd>
                                        </div>
                                    @endif
                                </dl>
                            </div>
                        @endif

                    @elseif($settings && $settings->isPending())
                        <div class="mt-2 flex items-center gap-2">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-saddle-50 text-saddle-700 ring-1 ring-saddle-200">
                                <span class="h-1.5 w-1.5 rounded-full bg-saddle-500"></span>
                                Onboarding Incomplete
                            </span>
                        </div>
                        <p class="mt-2 text-sm text-stable-500">
                            Your Stripe account setup isn't finished. Please complete the onboarding to start accepting payments.
                        </p>

                    @else
                        <p class="mt-2 text-sm text-stable-500">
                            Connect your Stripe account to start accepting sponsor payments. You'll be redirected to Stripe to set up or link your account.
                        </p>
                    @endif
                </div>
            </div>

            <!-- Actions -->
            <div class="mt-6 flex flex-wrap items-center gap-3 pt-4 border-t border-stable-100">
                @if($settings && $settings->isConnected())
                    <a href="{{ route('admin.settings.stripe.dashboard') }}"
                       class="inline-flex items-center gap-2 px-4 py-2.5 bg-purple-600 text-white text-sm font-medium rounded-lg hover:bg-purple-700 shadow-sm transition-colors">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                        </svg>
                        Open Stripe Dashboard
                    </a>
                    <form method="POST" action="{{ route('admin.settings.stripe.disconnect') }}" onsubmit="return confirm('Are you sure you want to disconnect your Stripe account? Existing subscriptions will continue but you won\'t be able to accept new payments.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="inline-flex items-center gap-2 px-4 py-2.5 text-red-600 text-sm font-medium rounded-lg hover:bg-red-50 border border-red-200 transition-colors">
                            Disconnect Account
                        </button>
                    </form>

                @elseif($settings && $settings->isPending())
                    <a href="{{ route('admin.settings.stripe.connect') }}"
                       class="inline-flex items-center gap-2 px-4 py-2.5 bg-purple-600 text-white text-sm font-medium rounded-lg hover:bg-purple-700 shadow-sm transition-colors">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                        </svg>
                        Continue Setup
                    </a>
                    <form method="POST" action="{{ route('admin.settings.stripe.disconnect') }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="inline-flex items-center px-4 py-2.5 text-stable-600 text-sm font-medium rounded-lg hover:bg-stable-100 border border-stable-300 transition-colors">
                            Start Over
                        </button>
                    </form>

                @else
                    <a href="{{ route('admin.settings.stripe.connect') }}"
                       class="inline-flex items-center gap-2 px-5 py-2.5 bg-purple-600 text-white text-sm font-medium rounded-lg hover:bg-purple-700 shadow-sm transition-colors">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m9.182-5.282a4.5 4.5 0 00-1.242-7.244l-4.5-4.5a4.5 4.5 0 00-6.364 6.364L4.343 8.07" />
                        </svg>
                        Connect with Stripe
                    </a>
                @endif
            </div>
        </div>

        <!-- Sponsorship Product Status (auto-managed) -->
        @if($settings && $settings->isConnected())
            <div class="bg-white shadow-sm border border-stable-200 rounded-xl p-6">
                <h2 class="text-lg font-semibold text-stable-900 mb-2">Sponsorship Product</h2>

                @if($settings->price_id)
                    <div class="flex items-center gap-2 mb-3">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-brand-50 text-brand-700 ring-1 ring-brand-200">
                            <span class="h-1.5 w-1.5 rounded-full bg-brand-500"></span>
                            Active
                        </span>
                    </div>
                    <p class="text-sm text-stable-500">Your sponsorship product is configured and ready to accept payments. The price is managed from <a href="{{ route('admin.settings.general') }}" class="text-brand-600 hover:text-brand-700 underline">Pricing</a>.</p>
                @else
                    <p class="text-sm text-stable-500">
                        The sponsorship product will be created automatically when you set a price in <a href="{{ route('admin.settings.general') }}" class="text-brand-600 hover:text-brand-700 underline">Pricing</a>.
                    </p>
                @endif
            </div>
        @endif

        <!-- Help / Info -->
        <div class="bg-stable-50 border border-stable-200 rounded-xl p-5">
            <div class="flex gap-3">
                <svg class="h-5 w-5 text-stable-400 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                </svg>
                <div class="text-sm text-stable-600">
                    <p class="font-medium text-stable-700 mb-1">How Stripe Connect works</p>
                    <p>Stripe Connect lets you accept payments directly into your own Stripe account. You'll set up or link your Stripe account through their secure onboarding process — no need to manually copy API keys.</p>
                    <p class="mt-2">Payments from sponsors flow directly to your connected account, and you manage payouts, refunds, and disputes from your own Stripe Dashboard.</p>
                </div>
            </div>
        </div>
    </div>
@endsection
