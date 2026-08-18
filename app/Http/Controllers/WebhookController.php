<?php

namespace App\Http\Controllers;

use App\Enums\SponsorshipStatus;
use App\Models\Invoice;
use App\Models\Sponsorship;
use App\Models\User;
use App\Notifications\PaymentFailedNotification;
use Carbon\Carbon;
use Laravel\Cashier\Http\Controllers\WebhookController as CashierWebhookController;
use Symfony\Component\HttpFoundation\Response;

class WebhookController extends CashierWebhookController
{
    /**
     * Create a new WebhookController instance.
     *
     * Ensures the webhook secret is configured — if not, the endpoint
     * refuses all requests to prevent unverified webhooks being processed.
     */
    public function __construct()
    {
        parent::__construct();

        if (!config('cashier.webhook.secret')) {
            abort(500, 'Stripe webhook secret is not configured.');
        }
    }

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

    /**
     * Handle invoice paid event.
     *
     * Stores a local invoice record when a Stripe invoice is successfully paid.
     */
    protected function handleInvoicePaid(array $payload): Response
    {
        $this->storeInvoice($payload);

        return $this->successMethod();
    }

    /**
     * Handle invoice payment succeeded event.
     *
     * Alternative event for capturing paid invoices (Stripe may send either).
     */
    protected function handleInvoicePaymentSucceeded(array $payload): Response
    {
        $this->storeInvoice($payload);

        return $this->successMethod();
    }

    /**
     * Handle invoice finalized event.
     *
     * Captures invoices when they are finalized (covers open/pending states).
     */
    protected function handleInvoiceFinalized(array $payload): Response
    {
        $this->storeInvoice($payload);

        return $this->successMethod();
    }

    /**
     * Store or update a local invoice record from a Stripe webhook payload.
     */
    private function storeInvoice(array $payload): void
    {
        $invoice = $payload['data']['object'];
        $stripeInvoiceId = $invoice['id'];
        $stripeCustomerId = $invoice['customer'] ?? null;
        $stripeSubscriptionId = $invoice['subscription'] ?? null;

        if (!$stripeCustomerId) {
            return;
        }

        // Find the user by their Stripe customer ID
        $user = User::where('stripe_id', $stripeCustomerId)->first();

        if (!$user) {
            return;
        }

        // Try to link to a specific sponsorship via the subscription ID
        $sponsorship = null;
        if ($stripeSubscriptionId) {
            $sponsorship = Sponsorship::where('stripe_subscription_id', $stripeSubscriptionId)
                ->where('user_id', $user->id)
                ->first();
        }

        // Create or update the invoice record
        Invoice::updateOrCreate(
            ['stripe_invoice_id' => $stripeInvoiceId],
            [
                'user_id' => $user->id,
                'sponsorship_id' => $sponsorship?->id,
                'amount' => $invoice['amount_paid'] ?? $invoice['total'] ?? 0,
                'currency' => $invoice['currency'] ?? config('cashier.currency', 'gbp'),
                'status' => $invoice['status'] ?? 'paid',
                'invoice_date' => isset($invoice['created'])
                    ? Carbon::createFromTimestamp($invoice['created'])
                    : now(),
                'hosted_invoice_url' => $invoice['hosted_invoice_url'] ?? null,
                'pdf_url' => $invoice['invoice_pdf'] ?? null,
                'description' => $invoice['description'] ?? ($invoice['lines']['data'][0]['description'] ?? null),
            ]
        );
    }
}
