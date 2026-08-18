<?php

namespace App\Http\Controllers\Sponsor;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Sponsorship;
use Carbon\Carbon;
use Illuminate\View\View;
use Laravel\Cashier\Cashier;

class FinanceController extends Controller
{
    /**
     * Display the sponsor's invoice/payment history.
     *
     * Falls back to the Stripe API if no local invoices exist for the user.
     */
    public function index(): View
    {
        $user = auth()->user();

        // If the user has a Stripe ID but no local invoices, backfill from Stripe
        if ($user->hasStripeId() && $user->invoices()->count() === 0) {
            $this->backfillFromStripe($user);
        }

        $invoices = $user->invoices()
            ->with('sponsorship.horse')
            ->orderByDesc('invoice_date')
            ->paginate(20);

        return view('sponsor.finance', compact('invoices'));
    }

    /**
     * Fetch invoices from the Stripe API and store them locally.
     */
    private function backfillFromStripe($user): void
    {
        try {
            $stripeInvoices = Cashier::stripe()->invoices->all([
                'customer' => $user->stripe_id,
                'limit' => 100,
            ]);

            foreach ($stripeInvoices->autoPagingIterator() as $stripeInvoice) {
                $stripeSubscriptionId = $stripeInvoice->subscription ?? null;

                $sponsorship = null;
                if ($stripeSubscriptionId) {
                    $sponsorship = Sponsorship::where('stripe_subscription_id', $stripeSubscriptionId)
                        ->where('user_id', $user->id)
                        ->first();
                }

                Invoice::updateOrCreate(
                    ['stripe_invoice_id' => $stripeInvoice->id],
                    [
                        'user_id' => $user->id,
                        'sponsorship_id' => $sponsorship?->id,
                        'amount' => $stripeInvoice->amount_paid ?? $stripeInvoice->total ?? 0,
                        'currency' => $stripeInvoice->currency ?? config('cashier.currency', 'gbp'),
                        'status' => $stripeInvoice->status ?? 'paid',
                        'invoice_date' => isset($stripeInvoice->created)
                            ? Carbon::createFromTimestamp($stripeInvoice->created)
                            : now(),
                        'hosted_invoice_url' => $stripeInvoice->hosted_invoice_url ?? null,
                        'pdf_url' => $stripeInvoice->invoice_pdf ?? null,
                        'description' => $stripeInvoice->description
                            ?? ($stripeInvoice->lines->data[0]->description ?? null),
                    ]
                );
            }
        } catch (\Exception $e) {
            // Log the error but don't break the page — show whatever local data exists
            report($e);
        }
    }
}
