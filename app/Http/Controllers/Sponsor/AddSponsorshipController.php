<?php

namespace App\Http\Controllers\Sponsor;

use App\Enums\SponsorshipStatus;
use App\Http\Controllers\Controller;
use App\Models\Horse;
use App\Models\Sponsorship;
use App\Models\StripeSetting;
use App\Notifications\WelcomeSponsorNotification;
use App\Services\StripeServiceInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AddSponsorshipController extends Controller
{
    /**
     * Show available horses for an existing sponsor to add a new sponsorship.
     */
    public function create(): View
    {
        $user = auth()->user();
        $settings = StripeSetting::first();
        $monthlyAmount = $settings?->sponsorship_amount;

        if (!$monthlyAmount) {
            abort(503, 'Sponsorship pricing has not been configured yet.');
        }

        // Get IDs of horses the sponsor already actively sponsors
        $activeSponsoredHorseIds = $user->sponsorships()
            ->where('status', SponsorshipStatus::Active)
            ->pluck('horse_id')
            ->toArray();

        // Show all horses not already actively sponsored by this user
        $horses = Horse::with('photos')
            ->whereNotIn('id', $activeSponsoredHorseIds)
            ->orderBy('name')
            ->get();

        return view('sponsor.add-sponsorship', [
            'horses' => $horses,
            'monthlyAmount' => $monthlyAmount,
            'stripeKey' => config('services.stripe.key'),
        ]);
    }

    /**
     * Process a new sponsorship for an existing sponsor.
     */
    public function store(Request $request, Horse $horse, StripeServiceInterface $stripeService): RedirectResponse
    {
        $validated = $request->validate([
            'child_name' => ['nullable', 'string', 'max:255'],
            'payment_method' => ['required', 'string'],
        ]);

        $user = auth()->user();
        $settings = StripeSetting::first();
        $amountInCents = $settings->sponsorship_amount_cents;

        if (!$amountInCents) {
            return back()->with('error', 'Sponsorship pricing has not been configured. Please try again later.');
        }

        // Check they don't already actively sponsor this horse
        $existing = $user->sponsorships()
            ->where('horse_id', $horse->id)
            ->where('status', SponsorshipStatus::Active)
            ->exists();

        if ($existing) {
            return back()->with('error', 'You already have an active sponsorship for this horse.');
        }

        return DB::transaction(function () use ($user, $horse, $amountInCents, $validated, $stripeService) {
            // Create Stripe subscription
            $subscription = $stripeService->createSubscription(
                $user,
                $amountInCents,
                $validated['payment_method']
            );

            // Create local Sponsorship record
            $sponsorship = Sponsorship::create([
                'user_id' => $user->id,
                'horse_id' => $horse->id,
                'stripe_subscription_id' => $subscription->stripe_id,
                'monthly_amount' => $amountInCents,
                'child_name' => $validated['child_name'] ?? null,
                'status' => SponsorshipStatus::Active,
            ]);

            // Send confirmation notification
            $user->notify(new WelcomeSponsorNotification($sponsorship));

            return redirect()->route('sponsor.dashboard')
                ->with('status', "You're now sponsoring {$horse->name}!");
        });
    }
}
