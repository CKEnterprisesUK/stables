<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StableBranding;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class BrandingController extends Controller
{
    /**
     * Show the form for editing the stable branding.
     */
    public function edit(): View
    {
        $branding = StableBranding::first();

        return view('admin.branding.edit', compact('branding'));
    }

    /**
     * Update the stable branding in storage.
     */
    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,svg', 'max:2048'],
        ]);

        $data = ['name' => $request->input('name')];

        if ($request->hasFile('logo')) {
            // Delete old logo if one exists
            $branding = StableBranding::first();
            if ($branding && $branding->logo_path) {
                Storage::disk('public')->delete($branding->logo_path);
            }

            $data['logo_path'] = $request->file('logo')->store('branding', 'public');
        }

        StableBranding::updateOrCreate(['id' => 1], $data);

        return redirect()->route('admin.branding.edit')
            ->with('status', 'Branding updated successfully.');
    }
}
