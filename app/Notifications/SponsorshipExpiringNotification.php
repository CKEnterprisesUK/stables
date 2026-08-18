<?php

namespace App\Notifications;

use App\Models\Sponsorship;
use App\Models\StableBranding;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SponsorshipExpiringNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $deleteWhenMissingModels = true;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Sponsorship $sponsorship,
        public int $daysRemaining,
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
        $horse = $this->sponsorship->horse;
        $horseName = $horse->name ?? 'Your Horse';
        $branding = StableBranding::first();
        $centreName = $branding->name ?? 'Margaret Haes Riding Centre';
        $endsAt = $this->sponsorship->ends_at->format('F j, Y');

        $subject = $this->daysRemaining === 0
            ? "Your sponsorship of {$horseName} has expired"
            : "Your sponsorship of {$horseName} expires in {$this->daysRemaining} day" . ($this->daysRemaining > 1 ? 's' : '');

        $message = (new MailMessage)
            ->subject($subject)
            ->from(config('mail.from.address'), $centreName);

        if ($this->daysRemaining === 0) {
            $message->greeting("Sponsorship Expired")
                ->line("Your gift sponsorship of {$horseName} has now expired as of {$endsAt}.")
                ->line("We hope you enjoyed being a sponsor! If you'd like to continue sponsoring {$horseName}, you can set up a new monthly sponsorship.")
                ->action('Continue Sponsoring', route('signup.create', $horse));
        } else {
            $message->greeting("Sponsorship Expiring Soon")
                ->line("Your gift sponsorship of {$horseName} will expire on {$endsAt} ({$this->daysRemaining} day" . ($this->daysRemaining > 1 ? 's' : '') . " remaining).")
                ->line("If you'd like to continue sponsoring {$horseName} after your gift period ends, you can set up a monthly sponsorship.")
                ->action('Continue Sponsoring', route('signup.create', $horse));
        }

        return $message->line("Thank you for being part of the {$centreName} family!")
            ->salutation('Sponsorship portal kindly provided by [CK Enterprises UK](https://ckenterprises.co.uk)');
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
            'days_remaining' => $this->daysRemaining,
        ];
    }
}
