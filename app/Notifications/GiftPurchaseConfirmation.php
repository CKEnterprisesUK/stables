<?php

namespace App\Notifications;

use App\Models\GiftSponsorship;
use App\Models\StableBranding;
use App\Services\GiftCardService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class GiftPurchaseConfirmation extends Notification implements ShouldQueue
{
    use Queueable;

    public $deleteWhenMissingModels = true;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public GiftSponsorship $gift,
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
        $horse = $this->gift->horse;
        $horseName = $horse->name ?? 'a horse';
        $branding = StableBranding::first();
        $centreName = $branding->name ?? 'Margaret Haes Riding Centre';

        // Generate the gift card PDF
        $giftCardService = app(GiftCardService::class);
        $pdf = $giftCardService->generate($this->gift);

        $filename = 'gift-sponsorship-' . str_replace(' ', '-', strtolower($horseName)) . '.pdf';

        return (new MailMessage)
            ->subject("Your Gift Sponsorship for {$horseName}")
            ->from(config('mail.from.address'), $centreName)
            ->greeting("Thank you, {$this->gift->purchaser_name}!")
            ->line("You've purchased a {$this->gift->months}-month gift sponsorship for {$horseName}.")
            ->line("The gift code is: **{$this->gift->code}**")
            ->line("The recipient can redeem this code to start their sponsorship without needing a credit card.")
            ->action('Download Gift Card', route('gift.download', $this->gift))
            ->line("The gift code is valid until {$this->gift->expires_at->format('F j, Y')}.")
            ->line('Thank you for your generous support!')
            ->attachData($pdf, $filename, [
                'mime' => 'application/pdf',
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
            'gift_sponsorship_id' => $this->gift->id,
            'horse_id' => $this->gift->horse_id,
        ];
    }
}
