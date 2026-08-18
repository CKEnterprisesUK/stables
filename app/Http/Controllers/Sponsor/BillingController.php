<?php

namespace App\Http\Controllers\Sponsor;

use App\Http\Controllers\Controller;
use App\Services\StripeServiceInterface;
use Illuminate\Http\RedirectResponse;

class BillingController extends Controller
{
    public function __construct(
        private StripeServiceInterface $stripeService
    ) {}

    /**
     * Redirect the sponsor to the Stripe Customer Portal for payment method management.
     */
    public function redirectToStripe(): RedirectResponse
    {
        $user = auth()->user();

        if (!$user->hasStripeId()) {
            return back()->with('error', 'No billing account found. Please create a sponsorship first.');
        }

        $url = $this->stripeService->getPortalUrl($user);

        return redirect()->away($url);
    }
}
