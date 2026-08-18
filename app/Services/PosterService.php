<?php

namespace App\Services;

use App\Models\Horse;
use App\Models\StableBranding;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Spatie\LaravelPdf\Facades\Pdf;
use Spatie\LaravelPdf\Enums\Format;

class PosterService
{
    /**
     * Generate a sponsorship poster PDF for a specific horse.
     *
     * @return string Path to the generated PDF
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

        $tempPath = storage_path('app/temp/poster-horse-' . $horse->id . '-' . time() . '.pdf');

        if (!is_dir(dirname($tempPath))) {
            mkdir(dirname($tempPath), 0755, true);
        }

        Pdf::view('posters.horse', [
            'horse' => $horse,
            'horseName' => $horse->name,
            'horseFacts' => $horse->facts,
            'horsePhoto' => $horsePhoto?->path,
            'stableName' => $branding?->name ?? config('app.name', 'Stables'),
            'stableLogo' => $branding?->logo_path,
            'sponsorUrl' => $sponsorUrl,
            'qrCode' => base64_encode($qrCode),
        ])
            ->format(Format::A4)
            ->margins(0, 0, 0, 0)
            ->save($tempPath);

        return $tempPath;
    }

    /**
     * Generate a generic sponsorship poster PDF (not horse-specific).
     *
     * @return string Path to the generated PDF
     */
    public function generateGenericPoster(): string
    {
        $branding = StableBranding::first();
        $sponsorUrl = route('sponsorship-info');

        $qrCode = QrCode::format('svg')
            ->size(200)
            ->margin(0)
            ->generate($sponsorUrl);

        $tempPath = storage_path('app/temp/poster-generic-' . time() . '.pdf');

        if (!is_dir(dirname($tempPath))) {
            mkdir(dirname($tempPath), 0755, true);
        }

        Pdf::view('posters.generic', [
            'stableName' => $branding?->name ?? config('app.name', 'Stables'),
            'stableLogo' => $branding?->logo_path,
            'sponsorshipInfo' => $branding?->sponsorship_info,
            'sponsorUrl' => $sponsorUrl,
            'qrCode' => base64_encode($qrCode),
        ])
            ->format(Format::A4)
            ->margins(0, 0, 0, 0)
            ->save($tempPath);

        return $tempPath;
    }
}
