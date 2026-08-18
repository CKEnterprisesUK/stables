<?php

namespace App\Listeners;

use App\Events\HorseUpdateCreated;
use App\Notifications\HorseUpdateNotification;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendUpdateNotification implements ShouldQueue
{
    /**
     * Handle the event.
     *
     * Sends a notification to all active sponsors of the horse
     * that received the update.
     */
    public function handle(HorseUpdateCreated $event): void
    {
        $update = $event->update;
        $sponsors = $update->horse->activeSponsors();

        foreach ($sponsors as $sponsor) {
            $sponsor->notify(new HorseUpdateNotification($update));
        }
    }
}
