<?php

namespace App\Services;

use App\Models\Sponsorship;
use App\Models\StableBranding;
use Spatie\LaravelPdf\Facades\Pdf;
use Spatie\LaravelPdf\Enums\Format;

class CertificateService
{
    /**
     * Generate a PDF certificate for the given sponsorship and save to a temp file.
     *
     * @return string Path to the generated PDF
     */
    public function generate(Sponsorship $sponsorship): string
    {
        $branding = StableBranding::first();
        $displayName = $this->getDisplayName($sponsorship);
        $horsePhoto = $sponsorship->horse->photos()->first();

        $tempPath = storage_path('app/temp/certificate-' . $sponsorship->id . '-' . time() . '.pdf');

        if (!is_dir(dirname($tempPath))) {
            mkdir(dirname($tempPath), 0755, true);
        }

        Pdf::view('certificates.sponsorship', [
            'displayName' => $displayName,
            'horseName' => $sponsorship->horse->name,
            'startDate' => $sponsorship->created_at->format('F j, Y'),
            'stableName' => $branding?->name ?? 'Our Stable',
            'stableLogo' => $branding?->logo_path,
            'horsePhoto' => $horsePhoto?->path,
        ])
            ->format(Format::A4)
            ->landscape()
            ->margins(0, 0, 0, 0)
            ->save($tempPath);

        return $tempPath;
    }

    /**
     * Get the display name for a sponsorship certificate.
     * Returns the child's name for child sponsorships, or the sponsor's name otherwise.
     */
    public function getDisplayName(Sponsorship $sponsorship): string
    {
        if ($sponsorship->isChildSponsorship()) {
            return $sponsorship->child_name;
        }

        return $sponsorship->user->name;
    }
}
