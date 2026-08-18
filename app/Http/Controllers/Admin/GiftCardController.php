<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GiftSponsorship;
use App\Notifications\GiftPurchaseConfirmation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Notification;
use Illuminate\View\View;

class GiftCardController extends Controller
{
    /**
     * Display all gift sponsorships with their status.
     */
    public function index(): View
    {
        $gifts = GiftSponsorship::with(['horse.photos', 'redeemedBy', 'sponsorship'])
            ->orderByDesc('created_at')
            ->get();

        return view('admin.gift-cards.index', compact('gifts'));
    }

    /**
     * Show details of a specific gift sponsorship.
     */
    public function show(GiftSponsorship $gift): View
    {
        $gift->load(['horse.photos', 'redeemedBy', 'sponsorship.user']);

        return view('admin.gift-cards.show', compact('gift'));
    }

    /**
     * Resend the gift card email to the purchaser.
     */
    public function resend(GiftSponsorship $gift): RedirectResponse
    {
        Notification::route('mail', $gift->purchaser_email)
            ->notify(new GiftPurchaseConfirmation($gift));

        return redirect()->route('admin.gift-cards.index')
            ->with('status', "Gift card resent to {$gift->purchaser_email}.");
    }
}
