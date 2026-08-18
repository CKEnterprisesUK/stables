<?php

namespace App\Notifications;

use App\Models\Sponsorship;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SponsorshipCancelledByAdminNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Sponsorship $sponsorship,
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
        $horseName = $this->sponsorship->horse->name ?? 'your horse';

        return (new MailMessage)
            ->subject('Your Sponsorship Has Been Cancelled')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line("We're writing to let you know that your sponsorship of {$horseName} has been cancelled by the stable administrator.")
            ->line('If you believe this was done in error or have any questions, please contact us.')
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
            'sponsorship_id' => $this->sponsorship->id,
            'horse_id' => $this->sponsorship->horse_id,
        ];
    }
}
