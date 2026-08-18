<?php

namespace App\Mail;

use App\Models\MagicLink;
use App\Models\StableBranding;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MagicLinkMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public MagicLink $magicLink,
    ) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Magic Login Link',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $branding = StableBranding::first();
        $centreName = $branding->name ?? 'Margaret Haes Riding Centre';

        return new Content(
            view: 'emails.magic-link',
            with: [
                'url' => route('magic-link.verify', $this->magicLink->token),
                'expiresAt' => $this->magicLink->expires_at,
                'centreName' => $centreName,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
