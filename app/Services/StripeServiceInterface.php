<?php

namespace App\Services;

use App\Models\Sponsorship;
use App\Models\User;
use Laravel\Cashier\Subscription;

interface StripeServiceInterface
{
    /**
     * Create a Stripe subscription for the given user with the specified monthly amount.
     *
     * Uses a pre-configured per-unit Stripe Price (e.g. £1/month) and sets
     * quantity equal to the amount in the base currency unit (e.g. pounds).
     *
     * @param  User    $user             The user to subscribe
     * @param  int     $amountInCents    Monthly amount in cents/pence
     * @param  string  $paymentMethodId  Stripe payment method ID
     * @return Subscription
     */
    public function createSubscription(User $user, int $amountInCents, string $paymentMethodId): Subscription;

    /**
     * Cancel a Stripe subscription and update the local sponsorship record.
     *
     * Sets status to 'cancelled' and records the ends_at timestamp.
     *
     * @param  Sponsorship  $sponsorship
     * @return void
     */
    public function cancelSubscription(Sponsorship $sponsorship): void;

    /**
     * Generate a Stripe Customer Portal URL for payment method management.
     *
     * @param  User  $user
     * @return string
     */
    public function getPortalUrl(User $user): string;
}
