<?php

namespace App\Http\Controllers\Sponsor;

use App\Http\Controllers\Controller;
use App\Models\Sponsorship;
use App\Services\StripeServiceInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;

class SponsorshipController extends Controller
{
    /**
     * Cancel an active sponsorship.
     *
     * Authorizes the sponsor owns the sponsorship, then delegates
     * cancellation to StripeService which handles both the Stripe API
     * call and local record update.
     */
    public function cancel(Sponsorship $sponsorship, StripeServiceInterface $stripeService): RedirectResponse
    {
        Gate::authorize('cancel', $sponsorship);

        $stripeService->cancelSubscription($sponsorship);

        $endDate = $sponsorship->fresh()->ends_at->format('j F Y');

        return redirect()->route('sponsor.dashboard')
            ->with('status', "Sponsorship cancelled. It will end on {$endDate}.");
    }
}
