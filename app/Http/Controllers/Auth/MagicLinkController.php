<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\MagicLinkRequest;
use App\Mail\MagicLinkMail;
use App\Models\MagicLink;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\View\View;

class MagicLinkController extends Controller
{
    /**
     * Display the magic link request form.
     */
    public function showRequestForm(): View
    {
        return view('auth.magic-link');
    }

    /**
     * Handle a magic link request.
     *
     * Validates email, finds the user, creates a token, and sends the magic link email.
     */
    public function request(MagicLinkRequest $request): RedirectResponse
    {
        $user = User::where('email', $request->validated('email'))->first();

        $magicLink = MagicLink::create([
            'user_id' => $user->id,
            'token' => Str::random(64),
            'expires_at' => now()->addMinutes(15),
        ]);

        Mail::to($user)->send(new MagicLinkMail($magicLink));

        return redirect()->route('magic-link.sent');
    }

    /**
     * Display the "check your email" confirmation page.
     */
    public function sent(): View
    {
        return view('auth.magic-link-sent');
    }

    /**
     * Authenticate a user via a magic link token.
     *
     * Handles error cases: expired, used, or invalid tokens.
     */
    public function authenticate(string $token): RedirectResponse
    {
        $magicLink = MagicLink::where('token', $token)->first();

        // Invalid token - 404
        if (! $magicLink) {
            abort(404);
        }

        // Already used
        if (! is_null($magicLink->used_at)) {
            return redirect()->route('login')->with('status', 'This magic link has already been used. Please request a new one.');
        }

        // Expired
        if ($magicLink->expires_at->isPast()) {
            return redirect()->route('login')->with('status', 'This magic link has expired. Please request a new one.');
        }

        // Mark as used and authenticate
        $magicLink->update(['used_at' => now()]);
        Auth::login($magicLink->user);

        $request = request();
        $request->session()->regenerate();

        return redirect()->route('sponsor.dashboard');
    }
}
