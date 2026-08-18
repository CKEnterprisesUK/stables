<?php

namespace App\Http\Controllers;

use App\Enums\SponsorshipStatus;
use App\Models\Sponsorship;
use App\Notifications\PaymentFailedNotification;
use Laravel\Cashier\Http\Controllers\WebhookController as CashierWebhookController;
use Symfony\Component\HttpFoundation\Response;

class WebhookController extends CashierWebhookController
{
    /**
     * Handle customer subscription deleted.
     *
     * Extends Cashier's handler to also update the local Sponsorship record.
     */
    protected function handleCustomerSubscriptionDeleted(array $payload): Response
    {
        // Let Cashier handle its own subscription state first
        parent::handleCustomerSubscriptionDeleted($payload);

        // Update local sponsorship record
        $stripeSubscriptionId = $payload['data']['object']['id'];

        $sponsorship = Sponsorship::where('stripe_subscription_id', $stripeSubscriptionId)->first();

        if ($sponsorship) {
            $sponsorship->update([
                'status' => SponsorshipStatus::Cancelled,
                'ends_at' => now(),
            ]);
        }

        return $this->successMethod();
    }

    /**
     * Handle invoice payment failed.
     *
     * Finds the sponsorship associated with the failed subscription and
     * notifies the sponsor via email.
     */
    protected function handleInvoicePaymentFailed(array $payload): Response
    {
        $stripeSubscriptionId = $payload['data']['object']['subscription'] ?? null;

        if ($stripeSubscriptionId) {
            $sponsorship = Sponsorship::where('stripe_subscription_id', $stripeSubscriptionId)->first();

            if ($sponsorship && $sponsorship->user) {
                $sponsorship->user->notify(new PaymentFailedNotification($sponsorship));
            }
        }

        return $this->successMethod();
    }
}
