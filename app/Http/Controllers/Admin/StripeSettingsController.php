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
                $accountDetails = $stripe->accounts->retrieve($settings->stripe_account_id);
            } catch (\Exception $e) {
                // If we can't fetch details, just show the ID
            }
        }

        return view('admin.settings.stripe', compact('settings', 'accountDetails'));
    }

    /**
     * Start the Stripe Connect onboarding flow.
     *
     * Creates a Connect account and redirects to Stripe's hosted onboarding.
     */
    public function connect(): RedirectResponse
    {
        $stripe = new \Stripe\StripeClient(config('cashier.secret'));

        $settings = StripeSetting::first();

        // Reuse existing account if we already have one pending
        if ($settings && $settings->stripe_account_id) {
            $accountId = $settings->stripe_account_id;
        } else {
            // Create a new Connect account (Standard type — they manage their own dashboard)
            $account = $stripe->accounts->create([
                'type' => 'standard',
            ]);
            $accountId = $account->id;

            StripeSetting::updateOrCreate(['id' => 1], [
                'stripe_account_id' => $accountId,
                'stripe_connect_status' => 'pending',
            ]);
        }

        // Create an Account Link for onboarding
        $accountLink = $stripe->accountLinks->create([
            'account' => $accountId,
            'refresh_url' => route('admin.settings.stripe.connect'),
            'return_url' => route('admin.settings.stripe.callback'),
            'type' => 'account_onboarding',
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
            $account = $stripe->accounts->retrieve($settings->stripe_account_id);

            if ($account->charges_enabled && $account->details_submitted) {
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

        } catch (\Stripe\Exception\ApiErrorException $e) {
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
     * Create a Stripe Product and Price on the connected account.
     */
    public function createProduct(): RedirectResponse
    {
        $settings = StripeSetting::first();

        if (!$settings || !$settings->isConnected()) {
            return back()->with('error', 'Please connect your Stripe account first.');
        }

        if ($settings->price_id) {
            return back()->with('error', 'A product and price already exist.');
        }

        try {
            $stripe = new \Stripe\StripeClient(config('cashier.secret'));
            $currency = config('cashier.currency', 'eur');

            // Create product and price on the platform account
            // (destination charges will route to the connected account)
            $product = $stripe->products->create([
                'name' => 'Sponsorship Unit',
                'description' => 'Monthly horse sponsorship unit (1 ' . strtoupper($currency) . ')',
            ]);

            $price = $stripe->prices->create([
                'product' => $product->id,
                'unit_amount' => 100, // 1 unit of currency
                'currency' => $currency,
                'recurring' => [
                    'interval' => 'month',
                ],
            ]);

            $settings->update(['price_id' => $price->id]);

            return back()->with('status', 'Sponsorship product and price created successfully.');
        } catch (\Stripe\Exception\ApiErrorException $e) {
            return back()->with('error', 'Stripe API error: ' . $e->getMessage());
        }
    }

    /**
     * Open the connected account's Stripe Express Dashboard.
     */
    public function dashboard(): RedirectResponse
    {
        $settings = StripeSetting::first();

        if (!$settings || !$settings->isConnected()) {
            return back()->with('error', 'No connected Stripe account.');
        }

        try {
            $stripe = new \Stripe\StripeClient(config('cashier.secret'));
            $loginLink = $stripe->accounts->createLoginLink($settings->stripe_account_id);

            return redirect($loginLink->url);
        } catch (\Stripe\Exception\ApiErrorException $e) {
            return back()->with('error', 'Could not generate dashboard link: ' . $e->getMessage());
        }
    }
}
