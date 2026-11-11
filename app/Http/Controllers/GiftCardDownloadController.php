<?php

namespace App\Http\Controllers;

use App\Models\GiftSponsorship;
use App\Services\GiftCardService;
use Symfony\Component\HttpFoundation\Response;

class GiftCardDownloadController extends Controller
{
    /**
     * Download the gift card PDF.
     *
     * This is accessible via a signed URL or directly after purchase.
     */
    public function download(GiftSponsorship $gift, GiftCardService $giftCardService): Response
    {
        $horseName = $gift->horse->name ?? 'horse';
        $safeName = preg_replace('/[^a-zA-Z0-9\-_]/', '-', $horseName);
        $filename = "gift-sponsorship-{$safeName}.pdf";

        $pdf = $giftCardService->generate($gift);

        return response($pdf, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }
}
