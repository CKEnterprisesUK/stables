<?php

namespace App\Notifications;

use App\Models\Sponsorship;
use App\Models\StableBranding;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentFailedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $deleteWhenMissingModels = true;

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
        $branding = StableBranding::first();
        $centreName = $branding->name ?? 'Margaret Haes Riding Centre';

        return (new MailMessage)
            ->subject('Payment Failed for Your Sponsorship')
            ->from(config('mail.from.address'), $centreName)
            ->view('emails.payment-failed', [
                'centreName' => $centreName,
                'sponsorName' => $notifiable->name,
                'horseName' => $horseName,
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
            'sponsorship_id' => $this->sponsorship->id,
            'horse_id' => $this->sponsorship->horse_id,
        ];
    }
}
