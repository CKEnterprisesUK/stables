<?php

namespace App\Services;

use App\Models\GiftSponsorship;
use App\Models\StableBranding;
use Barryvdh\DomPDF\Facade\Pdf;

class GiftCardService
{
    /**
     * Generate a PDF gift card for the given gift sponsorship.
     *
     * @return string PDF content as raw string
     */
    public function generate(GiftSponsorship $gift): string
    {
        $branding = StableBranding::first();
        $horse = $gift->horse;
        $horsePhoto = $horse->photos()->first();

        $pdf = Pdf::loadView('gift.card-pdf', [
            'gift' => $gift,
            'horseName' => $horse->name,
            'horsePhoto' => $horsePhoto?->path,
            'recipientName' => $gift->recipient_name,
            'recipientMessage' => $gift->recipient_message,
            'purchaserName' => $gift->purchaser_name,
            'code' => $gift->code,
            'months' => $gift->months,
            'expiresAt' => $gift->expires_at->format('F j, Y'),
            'stableName' => $branding?->name ?? 'Our Stable',
            'stableLogo' => $branding?->logo_path,
            'redeemUrl' => route('gift.redeem.create', ['code' => $gift->code]),
        ])->setPaper('a4', 'landscape');

        return $pdf->output();
    }
}
