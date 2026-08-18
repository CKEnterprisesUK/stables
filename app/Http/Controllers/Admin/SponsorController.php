<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\Sponsorship;
use App\Models\User;
use App\Notifications\SponsorshipCancelledByAdminNotification;
use App\Services\StripeServiceInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SponsorController extends Controller
{
    /**
     * Display a list of all sponsors with their sponsorships.
     */
    public function index(): View
    {
        $sponsors = User::where('role', UserRole::Sponsor)
            ->with(['sponsorships.horse'])
            ->orderBy('name')
            ->get();

        return view('admin.sponsors.index', compact('sponsors'));
    }

    /**
     * Cancel a sponsorship on behalf of the admin.
     *
     * Cancels the Stripe subscription, notifies the sponsor, and redirects back.
     */
    public function cancel(Sponsorship $sponsorship, StripeServiceInterface $stripeService): RedirectResponse
    {
        $stripeService->cancelSubscription($sponsorship);

        $sponsorship->user->notify(new SponsorshipCancelledByAdminNotification($sponsorship));

        return redirect()->route('admin.sponsors.index')
            ->with('status', 'Sponsorship cancelled and sponsor notified.');
    }
}
