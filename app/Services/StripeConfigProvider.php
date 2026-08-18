<?php

namespace App\Services;

use App\Models\StripeSetting;
use Illuminate\Database\QueryException;

class StripeConfigProvider
{
    /**
     * Apply Stripe settings from the database to Laravel's config.
     * Falls back to .env defaults if no DB settings exist or if the
     * table has not been migrated yet.
     */
    public function apply(): void
    {
        try {
            $settings = StripeSetting::first();
        } catch (QueryException) {
            // Table doesn't exist yet (migrations not run)
            return;
        }

        if (!$settings) {
            return;
        }

        $secret = decrypt($settings->stripe_secret_encrypted);

        config([
            'services.stripe.key' => $settings->stripe_key,
            'services.stripe.secret' => $secret,
            'cashier.key' => $settings->stripe_key,
            'cashier.secret' => $secret,
        ]);

        if ($settings->price_id) {
            config([
                'services.stripe.price_id' => $settings->price_id,
            ]);
        }
    }
}
