<?php

namespace App\Notifications;

use App\Models\HorseUpdate;
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
        $horseName = $this->update->horse->name ?? 'your horse';
        $updateTitle = $this->update->title;

        return (new MailMessage)
            ->subject("New Update: {$horseName}")
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line("There's a new update for {$horseName}: \"{$updateTitle}\".")
            ->line('Log in to your sponsor portal to read the full update and see any new photos.')
            ->action('View Update', route('sponsor.dashboard'))
            ->salutation('Thank you for your support!');
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
