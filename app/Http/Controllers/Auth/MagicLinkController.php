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
     * Always returns the same response to prevent user enumeration.
     */
    public function request(MagicLinkRequest $request): RedirectResponse
    {
        $user = User::where('email', $request->validated('email'))->first();

        // Only create and send the magic link if the user exists,
        // but always return the same redirect to prevent enumeration.
        if ($user) {
            // Invalidate any existing unused magic links for this user
            MagicLink::where('user_id', $user->id)
                ->whereNull('used_at')
                ->where('expires_at', '>', now())
                ->update(['expires_at' => now()]);

            $magicLink = MagicLink::create([
                'user_id' => $user->id,
                'token' => Str::random(64),
                'expires_at' => now()->addMinutes(15),
            ]);

            Mail::to($user)->send(new MagicLinkMail($magicLink));
        }

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
     * Show the confirm sign-in page for a magic link token (GET).
     *
     * This intermediate step prevents email security scanners (Office 365 Safe Links,
     * Mimecast, Proofpoint, etc.) from consuming the magic link via automated GET requests.
     * The actual authentication only happens when the user clicks the button (POST).
     */
    public function verify(string $token): View|RedirectResponse
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

        return view('auth.magic-link-confirm', ['token' => $token]);
    }

    /**
     * Authenticate a user via a magic link token (POST).
     *
     * Only triggered by the user clicking the sign-in button on the confirm page.
     */
    public function login(string $token): RedirectResponse
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

        // Redirect based on user role
        if ($magicLink->user->role === \App\Enums\UserRole::Sponsor) {
            return redirect()->route('sponsor.dashboard');
        }

        return redirect()->route('admin.horses.index');
    }
}
