<?php

namespace App\Notifications;

use App\Models\HorseUpdate;
use App\Models\StableBranding;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class HorseUpdateNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public HorseUpdate $update,
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $horse = $this->update->horse;
        $horseName = $horse->name ?? 'Your Horse';
        $branding = StableBranding::first();
        $centreName = $branding->name ?? 'Margaret Haes Riding Centre';

        // Get the first horse photo for the avatar, if available
        $horsePhoto = null;
        $firstPhoto = $horse->photos()->first();
        if ($firstPhoto) {
            $horsePhoto = asset('storage/' . $firstPhoto->path);
        }

        // Get update photos
        $updatePhotos = $this->update->photos->map(function ($photo) {
            return asset('storage/' . $photo->path);
        })->toArray();

        return (new MailMessage)
            ->subject("Update from {$horseName}")
            ->from(config('mail.from.address'), $horseName . ' via ' . $centreName)
            ->view('emails.horse-update', [
                'horseName' => $horseName,
                'centreName' => $centreName,
                'horsePhoto' => $horsePhoto,
                'sponsorName' => $notifiable->name,
                'updateTitle' => $this->update->title,
                'updateBody' => $this->update->body,
                'updatePhotos' => $updatePhotos,
                'portalUrl' => route('sponsor.dashboard'),
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'update_id' => $this->update->id,
            'horse_id' => $this->update->horse_id,
            'title' => $this->update->title,
        ];
    }
}
