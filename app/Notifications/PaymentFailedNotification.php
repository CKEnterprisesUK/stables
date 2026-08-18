<?php

namespace App\Notifications;

use App\Models\Sponsorship;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentFailedNotification extends Notification implements ShouldQueue
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
            ->subject('Payment Failed for Your Sponsorship')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line("We were unable to process your monthly payment for your sponsorship of {$horseName}.")
            ->line('Please update your payment method to ensure your sponsorship remains active.')
            ->line('If you need assistance, please don\'t hesitate to contact us.')
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
