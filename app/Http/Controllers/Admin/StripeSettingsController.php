<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StripeSettingsRequest;
use App\Models\StripeSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class StripeSettingsController extends Controller
{
    /**
     * Show the Stripe settings form.
     */
    public function edit(): View
    {
        $settings = StripeSetting::first();

        return view('admin.settings.stripe', compact('settings'));
    }

    /**
     * Update the Stripe settings.
     */
    public function update(StripeSettingsRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $attributes = [
            'stripe_key' => $data['stripe_key'],
        ];

        if (!empty($data['stripe_secret'])) {
            $attributes['stripe_secret_encrypted'] = encrypt($data['stripe_secret']);
        }

        if (!empty($data['webhook_secret'])) {
            $attributes['webhook_secret_encrypted'] = encrypt($data['webhook_secret']);
        }

        StripeSetting::updateOrCreate(['id' => 1], $attributes);

        return back()->with('status', 'Stripe settings saved.');
    }

    /**
     * Create a Stripe Product and Price using the admin-configured secret key.
     */
    public function createProduct(): RedirectResponse
    {
        $settings = StripeSetting::first();

        if (!$settings) {
            return back()->with('error', 'Please save your Stripe keys first.');
        }

        if ($settings->price_id) {
            return back()->with('error', 'A product and price already exist.');
        }

        try {
            $secretKey = decrypt($settings->stripe_secret_encrypted);
            $currency = config('cashier.currency', 'eur');

            \Stripe\Stripe::setApiKey($secretKey);

            $product = \Stripe\Product::create([
                'name' => 'Sponsorship Unit',
                'description' => 'Monthly horse sponsorship unit (1 ' . strtoupper($currency) . ')',
            ]);

            $price = \Stripe\Price::create([
                'product' => $product->id,
                'unit_amount' => 100, // 1 unit of currency (e.g. £1 or €1)
                'currency' => $currency,
                'recurring' => [
                    'interval' => 'month',
                ],
            ]);

            $settings->update(['price_id' => $price->id]);

            return back()->with('status', 'Product and price created successfully. Price ID: ' . $price->id);
        } catch (\Stripe\Exception\ApiErrorException $e) {
            return back()->with('error', 'Stripe API error: ' . $e->getMessage());
        } catch (\Exception $e) {
            return back()->with('error', 'Error creating product: ' . $e->getMessage());
        }
    }
}
