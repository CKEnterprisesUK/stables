<?php

namespace App\Notifications;

use App\Models\Sponsorship;
use App\Models\StableBranding;
use App\Services\CertificateService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WelcomeSponsorNotification extends Notification implements ShouldQueue
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
        $horse = $this->sponsorship->horse;
        $horseName = $horse->name ?? 'Your Horse';
        $branding = StableBranding::first();
        $centreName = $branding->name ?? 'Margaret Haes Riding Centre';

        // Get horse photo for the avatar
        $horsePhoto = null;
        $firstPhoto = $horse->photos()->first();
        if ($firstPhoto) {
            $horsePhoto = asset('storage/' . $firstPhoto->path);
        }

        // Generate the certificate PDF to attach
        $certificateService = app(CertificateService::class);
        $pdf = $certificateService->generate($this->sponsorship);

        $filename = 'sponsorship-certificate-' . str_replace(' ', '-', strtolower($horseName)) . '.pdf';

        return (new MailMessage)
            ->subject("Thank you from {$horseName}!")
            ->from(config('mail.from.address'), $horseName . ' via ' . $centreName)
            ->view('emails.welcome-sponsor', [
                'horseName' => $horseName,
                'centreName' => $centreName,
                'horsePhoto' => $horsePhoto,
                'sponsorName' => $notifiable->name,
                'childName' => $this->sponsorship->child_name,
                'monthlyAmount' => $this->sponsorship->monthly_amount,
                'certificateUrl' => route('sponsor.certificate.download', $this->sponsorship),
                'portalUrl' => route('sponsor.dashboard'),
            ])
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
            'sponsorship_id' => $this->sponsorship->id,
            'horse_id' => $this->sponsorship->horse_id,
        ];
    }
}
