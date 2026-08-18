<?php

namespace App\Http\Controllers\Sponsor;

use App\Http\Controllers\Controller;
use App\Models\HorseUpdate;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    /**
     * Display the sponsor's dashboard with sponsorship management at top
     * and a Facebook-like feed of horse updates below.
     */
    public function index(): View
    {
        $sponsor = auth()->user();

        $sponsorships = $sponsor->sponsorships()
            ->with(['horse.photos'])
            ->orderByDesc('created_at')
            ->get();

        // Get all active sponsorship horse IDs for the feed
        $activeHorseIds = $sponsorships
            ->where('status.value', 'active')
            ->pluck('horse_id')
            ->unique();

        // Load updates as a chronological feed (newest first) for active sponsorships
        $feed = collect();
        if ($activeHorseIds->isNotEmpty()) {
            $feed = HorseUpdate::whereIn('horse_id', $activeHorseIds)
                ->with(['photos', 'horse.photos'])
                ->orderByDesc('created_at')
                ->paginate(15);
        }

        return view('sponsor.dashboard', compact('sponsorships', 'feed'));
    }
}
