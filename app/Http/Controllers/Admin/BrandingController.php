<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StableBranding;
use enshrined\svgSanitize\Sanitizer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
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
            'favicon' => ['nullable', 'file', 'mimes:ico,png,svg', 'max:512'],
        ]);

        $branding = StableBranding::first();
        $data = ['name' => $request->input('name')];

        if ($request->hasFile('logo')) {
            // Delete old logo if one exists
            if ($branding && $branding->logo_path) {
                Storage::disk('public')->delete($branding->logo_path);
            }

            $data['logo_path'] = $this->storeFile($request->file('logo'), 'branding');
        }

        if ($request->hasFile('favicon')) {
            // Delete old favicon if one exists
            if ($branding && $branding->favicon_path) {
                Storage::disk('public')->delete($branding->favicon_path);
            }

            $data['favicon_path'] = $this->storeFile($request->file('favicon'), 'branding');
        }

        StableBranding::updateOrCreate(['id' => 1], $data);

        return redirect()->route('admin.branding.edit')
            ->with('status', 'Branding updated successfully.');
    }

    /**
     * Store an uploaded file, sanitizing SVG content to prevent XSS.
     */
    private function storeFile(UploadedFile $file, string $directory): string
    {
        $extension = strtolower($file->getClientOriginalExtension());

        if ($extension === 'svg') {
            $sanitizer = new Sanitizer();
            $sanitizer->removeRemoteReferences(true);
            $dirty = file_get_contents($file->path());
            $clean = $sanitizer->sanitize($dirty);

            if ($clean === false) {
                // Sanitization failed entirely — store as empty SVG to avoid XSS
                $clean = '<svg xmlns="http://www.w3.org/2000/svg"></svg>';
            }

            $filename = $directory . '/' . uniqid() . '.svg';
            Storage::disk('public')->put($filename, $clean);

            return $filename;
        }

        return $file->store($directory, 'public');
    }
}
