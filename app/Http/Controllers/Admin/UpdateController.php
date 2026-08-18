<?php

namespace App\Http\Controllers\Admin;

use App\Events\HorseUpdateCreated;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateRequest;
use App\Models\Horse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class UpdateController extends Controller
{
    /**
     * Show the form for creating a new update for a horse.
     */
    public function create(Horse $horse): View
    {
        return view('admin.updates.create', compact('horse'));
    }

    /**
     * Store a newly created update for a horse.
     */
    public function store(UpdateRequest $request, Horse $horse): RedirectResponse
    {
        $update = $horse->updates()->create($request->only(['title', 'body']));

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('updates', 'public');
                $update->photos()->create(['path' => $path]);
            }
        }

        event(new HorseUpdateCreated($update));

        return redirect()->route('admin.horses.show', $horse)
            ->with('status', 'Update created successfully.');
    }
}
