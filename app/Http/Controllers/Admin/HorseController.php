<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\HorseRequest;
use App\Models\Horse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class HorseController extends Controller
{
    /**
     * Display a listing of all horses.
     */
    public function index(): View
    {
        $horses = Horse::with('photos')->orderBy('name')->get();

        return view('admin.horses.index', compact('horses'));
    }

    /**
     * Show the form for creating a new horse.
     */
    public function create(): View
    {
        return view('admin.horses.create');
    }

    /**
     * Store a newly created horse in storage.
     */
    public function store(HorseRequest $request): RedirectResponse
    {
        $horse = Horse::create($request->only([
            'name', 'facts', 'date_of_birth', 'breed', 'colour',
            'gender', 'height_hands', 'arrival_date', 'personality',
            'favourite_treats', 'backstory',
        ]));

        $this->uploadPhotos($horse, $request);

        return redirect()->route('admin.horses.index')
            ->with('status', 'Horse created successfully.');
    }

    /**
     * Display the specified horse.
     */
    public function show(Horse $horse): View
    {
        $horse->load(['photos', 'sponsorships.user']);

        return view('admin.horses.show', compact('horse'));
    }

    /**
     * Show the form for editing the specified horse.
     */
    public function edit(Horse $horse): View
    {
        $horse->load('photos');

        return view('admin.horses.edit', compact('horse'));
    }

    /**
     * Update the specified horse in storage.
     */
    public function update(HorseRequest $request, Horse $horse): RedirectResponse
    {
        $horse->update($request->only([
            'name', 'facts', 'date_of_birth', 'breed', 'colour',
            'gender', 'height_hands', 'arrival_date', 'personality',
            'favourite_treats', 'backstory',
        ]));

        // Delete selected photos
        if ($request->has('delete_photos')) {
            $photosToDelete = $horse->photos()->whereIn('id', $request->input('delete_photos'))->get();
            foreach ($photosToDelete as $photo) {
                Storage::disk('public')->delete($photo->path);
                $photo->delete();
            }
        }

        $this->uploadPhotos($horse, $request);

        return redirect()->route('admin.horses.index')
            ->with('status', 'Horse updated successfully.');
    }

    /**
     * Remove the specified horse from storage.
     */
    public function destroy(Horse $horse): RedirectResponse
    {
        // Delete photo files from disk
        foreach ($horse->photos as $photo) {
            Storage::disk('public')->delete($photo->path);
        }

        // Delete the horse (cascades to photos via DB or manually)
        $horse->photos()->delete();
        $horse->delete();

        return redirect()->route('admin.horses.index')
            ->with('status', 'Horse deleted successfully.');
    }

    /**
     * Handle uploading multiple photos for a horse.
     */
    private function uploadPhotos(Horse $horse, HorseRequest $request): void
    {
        if (!$request->hasFile('photos')) {
            return;
        }

        $maxSort = $horse->photos()->max('sort_order') ?? 0;

        foreach ($request->file('photos') as $photo) {
            $path = $photo->store('horses', 'public');
            $maxSort++;

            $horse->photos()->create([
                'path' => $path,
                'sort_order' => $maxSort,
            ]);
        }
    }
}
