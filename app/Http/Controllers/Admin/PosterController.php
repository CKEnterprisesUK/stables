<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Horse;
use App\Services\PosterService;
use Illuminate\Http\Response;

class PosterController extends Controller
{
    public function __construct(
        protected PosterService $posterService
    ) {}

    /**
     * Download a sponsorship poster for a specific horse.
     */
    public function horse(Horse $horse): Response
    {
        $pdf = $this->posterService->generateHorsePoster($horse);
        $filename = 'sponsor-' . str($horse->name)->slug() . '-poster.pdf';

        return response($pdf, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    /**
     * Download a generic sponsorship poster for the stables.
     */
    public function generic(): Response
    {
        $pdf = $this->posterService->generateGenericPoster();

        return response($pdf, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="sponsorship-poster.pdf"',
        ]);
    }
}
