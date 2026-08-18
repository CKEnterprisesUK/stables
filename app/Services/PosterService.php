<?php

namespace App\Services;

use App\Models\Horse;
use App\Models\StableBranding;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PosterService
{
    /**
     * Get the data needed for a horse-specific sponsorship poster.
     */
    public function getHorsePosterData(Horse $horse): array
    {
        $branding = StableBranding::first();
        $horsePhoto = $horse->photos()->first();
        $sponsorUrl = route('signup.create', $horse);

        $qrCode = QrCode::format('svg')
            ->size(200)
            ->margin(0)
            ->generate($sponsorUrl);

        return [
            'horse' => $horse,
            'horseName' => $horse->name,
            'horseFacts' => $horse->facts,
            'horsePhoto' => $horsePhoto?->path,
            'stableName' => $branding?->name ?? config('app.name', 'Stables'),
            'stableLogo' => $branding?->logo_path,
            'sponsorUrl' => $sponsorUrl,
            'qrCode' => base64_encode($qrCode),
        ];
    }

    /**
     * Get the data needed for a generic sponsorship poster.
     */
    public function getGenericPosterData(): array
    {
        $branding = StableBranding::first();
        $sponsorUrl = route('sponsorship-info');

        $qrCode = QrCode::format('svg')
            ->size(200)
            ->margin(0)
            ->generate($sponsorUrl);

        return [
            'stableName' => $branding?->name ?? config('app.name', 'Stables'),
            'stableLogo' => $branding?->logo_path,
            'sponsorshipInfo' => $branding?->sponsorship_info,
            'sponsorUrl' => $sponsorUrl,
            'qrCode' => base64_encode($qrCode),
        ];
    }
}
