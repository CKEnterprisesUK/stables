<?php

namespace App\Http\Controllers\Sponsor;

use App\Http\Controllers\Controller;
use App\Models\Horse;
use App\Models\HorseUpdate;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class HorseUpdatesController extends Controller
{
    /**
     * Display all updates for a sponsored horse.
     *
     * Only accessible if the authenticated user has an active sponsorship for this horse.
     */
    public function index(Horse $horse): View|Response
    {
        $sponsor = auth()->user();

        // Verify the user has an active sponsorship for this horse
        $hasActiveSponsorship = $sponsor->sponsorships()
            ->where('horse_id', $horse->id)
            ->where('status', 'active')
            ->exists();

        if (!$hasActiveSponsorship) {
            abort(403, 'You do not have an active sponsorship for this horse.');
        }

        $updates = $horse->updates()
            ->with('photos')
            ->orderByDesc('created_at')
            ->paginate(10);

        $horse->load('photos');

        return view('sponsor.horse-updates', compact('horse', 'updates'));
    }
}
