<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StripeSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GeneralSettingsController extends Controller
{
    /**
     * Show the general settings page (sponsorship pricing).
     */
    public function edit(): View
    {
        $settings = StripeSetting::first();

        return view('admin.settings.general', compact('settings'));
    }

    /**
     * Update the general settings (sponsorship pricing).
     *
     * When Stripe is connected, this also ensures the per-unit Stripe
     * product/price exists (creates it automatically if missing).
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'sponsorship_amount' => ['required', 'numeric', 'min:1'],
        ]);

        $amountInCents = (int) round($validated['sponsorship_amount'] * 100);

        $settings = StripeSetting::updateOrCreate(
            ['id' => 1],
            ['sponsorship_amount_cents' => $amountInCents]
        );

        // Auto-create the Stripe product/price if connected but not yet created
        if ($settings->isConnected() && empty($settings->price_id)) {
            try {
                $this->createStripeProduct($settings);
            } catch (\Exception $e) {
                return redirect()->route('admin.settings.general')
                    ->with('status', 'Sponsorship pricing saved.')
                    ->with('error', 'Could not create Stripe product automatically: ' . $e->getMessage());
            }
        }

        return redirect()->route('admin.settings.general')
            ->with('status', 'Sponsorship pricing updated successfully.');
    }

    /**
     * Create the per-unit Stripe product and price (£1/month per unit).
     *
     * The actual charge amount is determined by quantity at subscription time,
     * which is derived from sponsorship_amount_cents.
     */
    protected function createStripeProduct(StripeSetting $settings): void
    {
        $stripe = new \Stripe\StripeClient(config('cashier.secret'));
        $currency = config('cashier.currency', 'eur');

        $product = $stripe->products->create([
            'name' => 'Sponsorship Unit',
            'description' => 'Monthly horse sponsorship unit (1 ' . strtoupper($currency) . ')',
        ]);

        $price = $stripe->prices->create([
            'product' => $product->id,
            'unit_amount' => 100, // 1 unit of currency (per-unit pricing)
            'currency' => $currency,
            'recurring' => [
                'interval' => 'month',
            ],
        ]);

        $settings->update(['price_id' => $price->id]);
    }
}
