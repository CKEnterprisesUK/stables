<?php

namespace App\Services;

use App\Models\Horse;
use App\Models\StableBranding;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PosterService
{
    /**
     * Generate a sponsorship poster PDF for a specific horse.
     *
     * @return string PDF content as raw string
     */
    public function generateHorsePoster(Horse $horse): string
    {
        $branding = StableBranding::first();
        $horsePhoto = $horse->photos()->first();
        $sponsorUrl = route('signup.create', $horse);

        $qrCode = QrCode::format('svg')
            ->size(200)
            ->margin(0)
            ->generate($sponsorUrl);

        $pdf = Pdf::loadView('posters.horse', [
            'horse' => $horse,
            'horseName' => $horse->name,
            'horseFacts' => $horse->facts,
            'horsePhoto' => $horsePhoto?->path,
            'stableName' => $branding?->name ?? config('app.name', 'Stables'),
            'stableLogo' => $branding?->logo_path,
            'sponsorUrl' => $sponsorUrl,
            'qrCode' => base64_encode($qrCode),
        ])->setPaper('a4', 'portrait');

        return $pdf->output();
    }

    /**
     * Generate a generic sponsorship poster PDF (not horse-specific).
     *
     * @return string PDF content as raw string
     */
    public function generateGenericPoster(): string
    {
        $branding = StableBranding::first();
        $sponsorUrl = route('sponsorship-info');

        $qrCode = QrCode::format('svg')
            ->size(200)
            ->margin(0)
            ->generate($sponsorUrl);

        $pdf = Pdf::loadView('posters.generic', [
            'stableName' => $branding?->name ?? config('app.name', 'Stables'),
            'stableLogo' => $branding?->logo_path,
            'sponsorshipInfo' => $branding?->sponsorship_info,
            'sponsorUrl' => $sponsorUrl,
            'qrCode' => base64_encode($qrCode),
        ])->setPaper('a4', 'portrait');

        return $pdf->output();
    }
}
