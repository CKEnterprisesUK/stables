<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StableBranding;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SponsorshipInfoController extends Controller
{
    /**
     * Show the form for editing the "What Your Sponsorship Goes To" content.
     */
    public function edit(): View
    {
        $branding = StableBranding::first();

        return view('admin.sponsorship-info.edit', compact('branding'));
    }

    /**
     * Update the sponsorship info content.
     */
    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'sponsorship_info' => ['required', 'string', 'max:10000'],
        ]);

        StableBranding::updateOrCreate(
            ['id' => 1],
            ['sponsorship_info' => $request->input('sponsorship_info')]
        );

        return redirect()->route('admin.sponsorship-info.edit')
            ->with('status', 'Sponsorship information updated successfully.');
    }
}
