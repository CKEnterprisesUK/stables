<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StripeSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StripeSettingsController extends Controller
{
    /**
     * Show the Stripe Connect settings page.
     */
    public function edit(): View
    {
        $settings = StripeSetting::first();

        // If connected, fetch account details from Stripe for display
        $accountDetails = null;
        if ($settings && $settings->isConnected()) {
            try {
                $stripe = new \Stripe\StripeClient(config('cashier.secret'));
                $accountDetails = $stripe->v2->core->accounts->retrieve($settings->stripe_account_id, [
                    'include' => ['identity'],
                ]);
            } catch (\Exception $e) {
                // If we can't fetch details, just show the ID
            }
        }

        return view('admin.settings.stripe', compact('settings', 'accountDetails'));
    }

    /**
     * Start the Stripe Connect onboarding flow.
     *
     * Creates a Connect account via v2 API and redirects to Stripe's hosted onboarding.
     */
    public function connect(): RedirectResponse
    {
        $stripe = new \Stripe\StripeClient(config('cashier.secret'));

        $settings = StripeSetting::first();

        // Reuse existing account if we already have one pending (must be a v2 account)
        // Note: v1 account IDs (acct_*) are compatible with v2 APIs after a short delay
        if ($settings && $settings->stripe_account_id) {
            $accountId = $settings->stripe_account_id;
        } else {
            // Create a new Connect account using the Accounts v2 API
            // Full dashboard access, Stripe collects fees and handles losses (Standard equivalent)
            $account = $stripe->v2->core->accounts->create([
                'dashboard' => 'full',
                'identity' => [
                    'country' => 'gb',
                ],
                'defaults' => [
                    'responsibilities' => [
                        'fees_collector' => 'stripe',
                        'losses_collector' => 'stripe',
                    ],
                ],
                'configuration' => [
                    'merchant' => [
                        'capabilities' => [
                            'card_payments' => ['requested' => true],
                        ],
                    ],
                ],
            ]);
            $accountId = $account->id;

            StripeSetting::updateOrCreate(['id' => 1], [
                'stripe_account_id' => $accountId,
                'stripe_connect_status' => 'pending',
            ]);
        }

        // Create an Account Link for onboarding via v2 API
        $accountLink = $stripe->v2->core->accountLinks->create([
            'account' => $accountId,
            'use_case' => [
                'type' => 'account_onboarding',
                'account_onboarding' => [
                    'configurations' => ['merchant'],
                    'refresh_url' => route('admin.settings.stripe.connect'),
                    'return_url' => route('admin.settings.stripe.callback'),
                ],
            ],
        ]);

        return redirect($accountLink->url);
    }

    /**
     * Handle the return from Stripe Connect onboarding.
     *
     * Check if onboarding is complete and update status accordingly.
     */
    public function callback(): RedirectResponse
    {
        $settings = StripeSetting::first();

        if (!$settings || !$settings->stripe_account_id) {
            return redirect()->route('admin.settings.stripe')
                ->with('error', 'No Stripe account found. Please try connecting again.');
        }

        try {
            $stripe = new \Stripe\StripeClient(config('cashier.secret'));
            $account = $stripe->v2->core->accounts->retrieve($settings->stripe_account_id, [
                'include' => ['configuration.merchant', 'requirements'],
            ]);

            // Check if the merchant configuration has active card_payments capability
            $merchantConfig = $account->configuration->merchant ?? null;
            $cardPayments = $merchantConfig->capabilities->card_payments ?? null;
            $isActive = $cardPayments && ($cardPayments->status ?? null) === 'active';

            // Also check if requirements are all satisfied (no currently_due items)
            $requirements = $account->requirements ?? null;
            $hasNoCurrentRequirements = !$requirements
                || empty($requirements->currently_due ?? []);

            if ($isActive || $hasNoCurrentRequirements) {
                $settings->update([
                    'stripe_connect_status' => 'connected',
                ]);

                return redirect()->route('admin.settings.stripe')
                    ->with('status', 'Stripe account connected successfully! You can now accept payments.');
            }

            // Onboarding incomplete
            $settings->update([
                'stripe_connect_status' => 'pending',
            ]);

            return redirect()->route('admin.settings.stripe')
                ->with('error', 'Stripe onboarding is not yet complete. Please finish setting up your account.');

        } catch (\Exception $e) {
            return redirect()->route('admin.settings.stripe')
                ->with('error', 'Error verifying Stripe account: ' . $e->getMessage());
        }
    }

    /**
     * Disconnect the Stripe Connect account.
     */
    public function disconnect(Request $request): RedirectResponse
    {
        $settings = StripeSetting::first();

        if (!$settings || !$settings->stripe_account_id) {
            return redirect()->route('admin.settings.stripe')
                ->with('error', 'No Stripe account to disconnect.');
        }

        $settings->update([
            'stripe_account_id' => null,
            'stripe_connect_status' => 'not_connected',
        ]);

        return redirect()->route('admin.settings.stripe')
            ->with('status', 'Stripe account disconnected. You will need to connect a new account to accept payments.');
    }

    /**
     * Open the connected account's Stripe Dashboard.
     *
     * For accounts with full dashboard access, redirect to the Stripe login page.
     * Login links are only supported for Express dashboard accounts.
     */
    public function dashboard(): RedirectResponse
    {
        $settings = StripeSetting::first();

        if (!$settings || !$settings->isConnected()) {
            return back()->with('error', 'No connected Stripe account.');
        }

        // Full dashboard accounts can log in directly at dashboard.stripe.com
        return redirect('https://dashboard.stripe.com');
    }
}
