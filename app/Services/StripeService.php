<?php

namespace App\Services;

use App\Enums\SponsorshipStatus;
use App\Models\Sponsorship;
use App\Models\User;
use Laravel\Cashier\Subscription;

class StripeService implements StripeServiceInterface
{
    /**
     * Create a Stripe subscription for the given user with the specified monthly amount.
     *
     * Uses a pre-configured per-unit Stripe Price (e.g. £1/month per unit) and sets
     * the quantity to the amount in whole currency units (e.g. pounds/euros).
     * This allows sponsors to choose their own monthly amount dynamically.
     */
    public function createSubscription(User $user, int $amountInCents, string $paymentMethodId): Subscription
    {
        // Ensure the user has a Stripe customer record
        if (!$user->hasStripeId()) {
            $user->createAsStripeCustomer();
        }

        // Use per-unit price approach: quantity = amount in base units (e.g. pounds)
        // The Stripe Price should be configured as £1/month (or €1/month) per unit
        $priceId = config('services.stripe.price_id');
        $quantity = (int) ceil($amountInCents / 100);

        return $user->newSubscription('default', $priceId)
            ->quantity($quantity)
            ->create($paymentMethodId);
    }

    /**
     * Cancel a Stripe subscription and update the local sponsorship record.
     */
    public function cancelSubscription(Sponsorship $sponsorship): void
    {
        $user = $sponsorship->user;

        // Find the Cashier subscription by the Stripe subscription ID
        $subscription = $user->subscriptions()
            ->where('stripe_id', $sponsorship->stripe_subscription_id)
            ->first();

        if ($subscription) {
            $subscription->cancel();
        }

        // Update local sponsorship record
        $sponsorship->update([
            'status' => SponsorshipStatus::Cancelled,
            'ends_at' => now(),
        ]);
    }

    /**
     * Generate a Stripe Customer Portal URL for payment method management.
     */
    public function getPortalUrl(User $user): string
    {
        return $user->billingPortalUrl(config('app.url'));
    }
}
