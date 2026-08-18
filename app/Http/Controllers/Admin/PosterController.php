<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Horse;
use App\Services\PosterService;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PosterController extends Controller
{
    public function __construct(
        protected PosterService $posterService
    ) {}

    /**
     * Download a sponsorship poster for a specific horse.
     */
    public function horse(Horse $horse): BinaryFileResponse
    {
        $path = $this->posterService->generateHorsePoster($horse);
        $filename = 'sponsor-' . str($horse->name)->slug() . '-poster.pdf';

        return response()->download($path, $filename, [
            'Content-Type' => 'application/pdf',
        ])->deleteFileAfterSend(true);
    }

    /**
     * Download a generic sponsorship poster for the stables.
     */
    public function generic(): BinaryFileResponse
    {
        $path = $this->posterService->generateGenericPoster();

        return response()->download($path, 'sponsorship-poster.pdf', [
            'Content-Type' => 'application/pdf',
        ])->deleteFileAfterSend(true);
    }
}
