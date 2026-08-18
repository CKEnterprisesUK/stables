<?php

namespace App\Http\Controllers;

use App\Enums\GiftSponsorshipStatus;
use App\Models\GiftSponsorship;
use App\Models\Horse;
use App\Models\StripeSetting;
use App\Notifications\GiftPurchaseConfirmation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\View\View;

class GiftPurchaseController extends Controller
{
    /**
     * Show the gift sponsorship information/landing page.
     */
    public function info(): View
    {
        $settings = StripeSetting::first();
        $monthlyAmount = $settings?->sponsorship_amount;
        $branding = \App\Models\StableBranding::first();
        $horses = Horse::with('photos')->orderBy('name')->get();

        return view('gift.info', [
            'monthlyAmount' => $monthlyAmount ?? 0,
            'branding' => $branding,
            'horses' => $horses,
        ]);
    }

    /**
     * Show the gift sponsorship purchase form.
     */
    public function create(Horse $horse): View
    {
        $horse->load('photos');

        $settings = StripeSetting::first();
        $monthlyAmount = $settings?->sponsorship_amount;

        if (!$monthlyAmount) {
            abort(503, 'Sponsorship pricing has not been configured yet.');
        }

        return view('gift.purchase', [
            'horse' => $horse,
            'stripeKey' => config('services.stripe.key'),
            'monthlyAmount' => $monthlyAmount,
            'monthlyAmountCents' => $settings->sponsorship_amount_cents,
        ]);
    }

    /**
     * Process the gift sponsorship purchase.
     *
     * Uses Stripe Payment Intents for a one-time charge (not a subscription).
     */
    public function store(Request $request, Horse $horse): RedirectResponse
    {
        $validated = $request->validate([
            'purchaser_name' => ['required', 'string', 'max:255'],
            'purchaser_email' => ['required', 'email', 'max:255'],
            'recipient_name' => ['nullable', 'string', 'max:255'],
            'recipient_message' => ['nullable', 'string', 'max:500'],
            'months' => ['required', 'integer', 'in:3,6,12'],
            'payment_intent_id' => ['required', 'string'],
        ]);

        $settings = StripeSetting::first();
        $amountInCents = $settings->sponsorship_amount_cents;

        if (!$amountInCents) {
            return back()->with('error', 'Sponsorship pricing has not been configured. Please try again later.');
        }

        $totalAmount = $amountInCents * $validated['months'];

        // Create the gift sponsorship record
        $gift = GiftSponsorship::create([
            'code' => GiftSponsorship::generateCode(),
            'horse_id' => $horse->id,
            'purchaser_name' => $validated['purchaser_name'],
            'purchaser_email' => $validated['purchaser_email'],
            'recipient_name' => $validated['recipient_name'] ?? null,
            'recipient_message' => $validated['recipient_message'] ?? null,
            'months' => $validated['months'],
            'amount_paid' => $totalAmount,
            'stripe_payment_intent_id' => $validated['payment_intent_id'],
            'status' => GiftSponsorshipStatus::Purchased,
            'expires_at' => now()->addYear(), // Gift code valid for 1 year from purchase
        ]);

        // Send confirmation email with gift card PDF to purchaser
        Notification::route('mail', $validated['purchaser_email'])
            ->notify(new GiftPurchaseConfirmation($gift));

        return redirect()->route('gift.success', $gift)
            ->with('status', 'Gift sponsorship purchased successfully!');
    }

    /**
     * Show the purchase success page with download link.
     */
    public function success(GiftSponsorship $gift): View
    {
        $gift->load('horse.photos');

        return view('gift.success', [
            'gift' => $gift,
        ]);
    }

    /**
     * Create a Stripe Payment Intent for the gift purchase.
     *
     * Called via AJAX from the frontend before form submission.
     */
    public function createPaymentIntent(Request $request, Horse $horse)
    {
        $validated = $request->validate([
            'months' => ['required', 'integer', 'in:3,6,12'],
        ]);

        $settings = StripeSetting::first();
        $amountInCents = $settings->sponsorship_amount_cents;
        $totalAmount = $amountInCents * $validated['months'];

        $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));

        $paymentIntent = $stripe->paymentIntents->create([
            'amount' => $totalAmount,
            'currency' => config('cashier.currency', 'gbp'),
            'metadata' => [
                'type' => 'gift_sponsorship',
                'horse_id' => $horse->id,
                'months' => $validated['months'],
            ],
        ]);

        return response()->json([
            'clientSecret' => $paymentIntent->client_secret,
            'amount' => $totalAmount,
        ]);
    }
}
