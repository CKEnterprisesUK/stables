<?php

namespace App\Services;

use App\Models\GiftSponsorship;
use App\Models\StableBranding;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

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

        $redeemUrl = route('gift.redeem.create', ['code' => $gift->code]);

        // Generate QR code as base64 PNG data URI for embedding in the PDF
        $qrCodeSvg = QrCode::format('svg')
            ->size(150)
            ->margin(1)
            ->generate($redeemUrl);

        $qrCodeDataUri = 'data:image/svg+xml;base64,' . base64_encode($qrCodeSvg);

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
            'redeemUrl' => $redeemUrl,
            'qrCodeDataUri' => $qrCodeDataUri,
        ])->setPaper('a4', 'landscape');

        return $pdf->output();
    }
}
