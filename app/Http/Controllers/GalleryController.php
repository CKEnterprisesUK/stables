<?php

namespace App\Http\Controllers;

use App\Models\Horse;
use App\Models\HorseUpdate;
use App\Models\StableBranding;
use Illuminate\View\View;

class GalleryController extends Controller
{
    /**
     * Display the public horse gallery.
     */
    public function index(): View
    {
        $horses = Horse::with('photos')->orderBy('name')->get();
        $branding = StableBranding::first();

        return view('gallery.index', compact('horses', 'branding'));
    }

    /**
     * Display a single horse's detail page.
     */
    public function show(Horse $horse): View
    {
        $horse->load('photos');
        $branding = StableBranding::first();

        return view('gallery.show', compact('horse', 'branding'));
    }

    /**
     * Display the stables page showing all horses with their updates.
     *
     * Sponsors with active sponsorships can see full updates.
     * Non-sponsors see greyed-out update previews to encourage sponsorship.
     */
    public function stables(): View
    {
        $horses = Horse::with(['photos', 'updates' => function ($query) {
            $query->with('photos')->orderByDesc('created_at')->limit(3);
        }])->orderBy('name')->get();

        $branding = StableBranding::first();

        // Get the current user's actively sponsored horse IDs
        $sponsoredHorseIds = [];
        if (auth()->check()) {
            $sponsoredHorseIds = auth()->user()->sponsorships()
                ->where('status', 'active')
                ->pluck('horse_id')
                ->toArray();
        }

        return view('gallery.stables', compact('horses', 'branding', 'sponsoredHorseIds'));
    }
}
