<?php

namespace App\Http\Controllers;

use App\Enums\GiftSponsorshipStatus;
use App\Enums\SponsorshipStatus;
use App\Enums\UserRole;
use App\Models\GiftSponsorship;
use App\Models\Sponsorship;
use App\Models\User;
use App\Notifications\WelcomeSponsorNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class GiftRedemptionController extends Controller
{
    /**
     * Show the gift redemption form.
     *
     * The recipient enters their gift code and registers an account.
     * No credit card required.
     */
    public function create(Request $request): View
    {
        $code = $request->query('code');
        $gift = null;
        $error = null;

        if ($code) {
            $gift = GiftSponsorship::with('horse.photos')->where('code', $code)->first();

            if (!$gift) {
                $error = 'This gift code was not found. Please check and try again.';
            } elseif ($gift->isRedeemed()) {
                $error = 'This gift code has already been redeemed.';
            } elseif ($gift->isExpired()) {
                $error = 'This gift code has expired.';
            }
        }

        return view('gift.redeem', [
            'code' => $code,
            'gift' => $gift,
            'error' => $error,
        ]);
    }

    /**
     * Process gift redemption: create user account and sponsorship without Stripe.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:32'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'child_name' => ['nullable', 'string', 'max:255'],
        ]);

        // Find and validate the gift code
        $gift = GiftSponsorship::where('code', $validated['code'])->first();

        if (!$gift) {
            return back()->withInput()->withErrors(['code' => 'This gift code was not found.']);
        }

        if ($gift->isRedeemed()) {
            return back()->withInput()->withErrors(['code' => 'This gift code has already been redeemed.']);
        }

        if ($gift->isExpired()) {
            return back()->withInput()->withErrors(['code' => 'This gift code has expired.']);
        }

        return DB::transaction(function () use ($validated, $gift) {
            // 1. Create user account (no Stripe customer needed)
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password'] ?? Str::random(32)),
                'role' => UserRole::Sponsor,
            ]);

            // 2. Create sponsorship record — no stripe_subscription_id, uses Gift status
            $endsAt = now()->addMonths($gift->months);

            $sponsorship = Sponsorship::create([
                'user_id' => $user->id,
                'horse_id' => $gift->horse_id,
                'stripe_subscription_id' => null,
                'monthly_amount' => (int) ($gift->amount_paid / $gift->months),
                'child_name' => $validated['child_name'] ?? $gift->recipient_name,
                'status' => SponsorshipStatus::Gift,
                'ends_at' => $endsAt,
                'gift_sponsorship_id' => $gift->id,
            ]);

            // 3. Mark the gift as redeemed
            $gift->update([
                'status' => GiftSponsorshipStatus::Redeemed,
                'redeemed_by_user_id' => $user->id,
                'sponsorship_id' => $sponsorship->id,
                'redeemed_at' => now(),
            ]);

            // 4. Send welcome email with certificate
            $user->notify(new WelcomeSponsorNotification($sponsorship));

            // 5. Authenticate user
            Auth::login($user);

            // 6. Redirect to sponsor portal
            return redirect()->route('sponsor.dashboard')
                ->with('status', "Welcome! Your gift sponsorship for {$gift->horse->name} is active until {$endsAt->format('F j, Y')}.");
        });
    }
}
