<?php

namespace App\Http\Controllers;

use App\Models\Horse;
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
}
