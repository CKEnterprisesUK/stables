<?php

namespace App\Http\Controllers;

use App\Enums\SponsorshipStatus;
use App\Enums\UserRole;
use App\Http\Requests\SignupRequest;
use App\Models\Horse;
use App\Models\Sponsorship;
use App\Models\User;
use App\Notifications\WelcomeSponsorNotification;
use App\Services\StripeServiceInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class SignupController extends Controller
{
    /**
     * Display the sponsorship signup form for a specific horse.
     */
    public function create(Horse $horse): View
    {
        $horse->load('photos');

        return view('signup.create', [
            'horse' => $horse,
            'stripeKey' => config('services.stripe.key'),
        ]);
    }

    /**
     * Handle the sponsorship signup flow.
     *
     * 1. Create user account
     * 2. Create Stripe customer + subscription via StripeService
     * 3. Create local Sponsorship record
     * 4. Authenticate user
     * 5. Redirect to sponsor portal dashboard
     */
    public function store(SignupRequest $request, Horse $horse): RedirectResponse
    {
        $validated = $request->validated();

        return DB::transaction(function () use ($validated, $horse) {
            // 1. Create user account
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => UserRole::Sponsor,
            ]);

            // 2. Create Stripe customer + subscription
            $amountInCents = (int) ($validated['monthly_amount'] * 100);
            $stripeService = app(StripeServiceInterface::class);
            $subscription = $stripeService->createSubscription(
                $user,
                $amountInCents,
                $validated['payment_method']
            );

            // 3. Create local Sponsorship record
            $sponsorship = Sponsorship::create([
                'user_id' => $user->id,
                'horse_id' => $horse->id,
                'stripe_subscription_id' => $subscription->stripe_id,
                'monthly_amount' => $amountInCents,
                'child_name' => $validated['child_name'] ?? null,
                'status' => SponsorshipStatus::Active,
            ]);

            // 4. Send welcome email with certificate
            $user->notify(new WelcomeSponsorNotification($sponsorship));

            // 5. Authenticate user
            Auth::login($user);

            // 6. Redirect to sponsor portal dashboard
            return redirect()->route('sponsor.dashboard')
                ->with('status', 'Welcome! Your sponsorship has been set up successfully.');
        });
    }
}
