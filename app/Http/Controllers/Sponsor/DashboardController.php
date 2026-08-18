<?php

namespace App\Http\Controllers\Sponsor;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    /**
     * Display the sponsor's dashboard with all sponsorships and horse updates.
     *
     * Shows all sponsorships (active and past) with horse info.
     * For active sponsorships, includes the most recent horse updates.
     * Past/cancelled sponsorships do not show updates.
     */
    public function index(): View
    {
        $sponsor = auth()->user();

        $sponsorships = $sponsor->sponsorships()
            ->with(['horse.photos'])
            ->orderByDesc('created_at')
            ->get();

        // Load recent updates only for active sponsorships
        $activeSponshorshipIds = $sponsorships
            ->where('status.value', 'active')
            ->pluck('horse_id')
            ->unique();

        $updatesByHorse = [];
        if ($activeSponshorshipIds->isNotEmpty()) {
            $updatesByHorse = \App\Models\HorseUpdate::whereIn('horse_id', $activeSponshorshipIds)
                ->with('photos')
                ->orderByDesc('created_at')
                ->get()
                ->groupBy('horse_id')
                ->map(fn ($updates) => $updates->take(10));
        }

        return view('sponsor.dashboard', compact('sponsorships', 'updatesByHorse'));
    }
}
