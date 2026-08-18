<?php

namespace App\Services;

use App\Models\Sponsorship;
use App\Models\StableBranding;
use Barryvdh\DomPDF\Facade\Pdf;

class CertificateService
{
    /**
     * Generate a PDF certificate for the given sponsorship.
     *
     * @return string PDF content as raw string
     */
    public function generate(Sponsorship $sponsorship): string
    {
        $branding = StableBranding::first();
        $displayName = $this->getDisplayName($sponsorship);
        $horsePhoto = $sponsorship->horse->photos()->first();

        $pdf = Pdf::loadView('certificates.sponsorship', [
            'displayName' => $displayName,
            'horseName' => $sponsorship->horse->name,
            'startDate' => $sponsorship->created_at->format('F j, Y'),
            'stableName' => $branding?->name ?? 'Our Stable',
            'stableLogo' => $branding?->logo_path,
            'horsePhoto' => $horsePhoto?->path,
        ]);

        return $pdf->output();
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
