<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StripeSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GeneralSettingsController extends Controller
{
    /**
     * Show the general settings page (sponsorship pricing).
     */
    public function edit(): View
    {
        $settings = StripeSetting::first();

        return view('admin.settings.general', compact('settings'));
    }

    /**
     * Update the general settings (sponsorship pricing).
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'sponsorship_amount' => ['required', 'numeric', 'min:1'],
        ]);

        $amountInCents = (int) round($validated['sponsorship_amount'] * 100);

        StripeSetting::updateOrCreate(
            ['id' => 1],
            ['sponsorship_amount_cents' => $amountInCents]
        );

        return redirect()->route('admin.settings.general')
            ->with('status', 'Sponsorship pricing updated successfully.');
    }
}
