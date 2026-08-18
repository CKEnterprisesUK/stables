<?php

namespace App\Http\Controllers\Admin;

use App\Events\HorseUpdateCreated;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateRequest;
use App\Models\Horse;
use App\Models\HorseUpdate;
use App\Notifications\HorseUpdateNotification;
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
            ->with('status', 'Update created and sponsors notified.');
    }

    /**
     * Resend an existing update notification to all active sponsors.
     *
     * Useful if the original notification failed or new sponsors have
     * joined since the update was first posted.
     */
    public function notify(Horse $horse, HorseUpdate $update): RedirectResponse
    {
        $sponsors = $horse->activeSponsors();

        if ($sponsors->isEmpty()) {
            return redirect()->route('admin.horses.show', $horse)
                ->with('error', 'No active sponsors to notify for this horse.');
        }

        foreach ($sponsors as $sponsor) {
            $sponsor->notify(new HorseUpdateNotification($update));
        }

        return redirect()->route('admin.horses.show', $horse)
            ->with('status', "Update \"{$update->title}\" sent to {$sponsors->count()} sponsor(s).");
    }
}
