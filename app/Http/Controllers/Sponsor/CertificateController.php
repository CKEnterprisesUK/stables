<?php

namespace App\Http\Controllers\Sponsor;

use App\Http\Controllers\Controller;
use App\Models\Sponsorship;
use App\Services\CertificateService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class CertificateController extends Controller
{
    use AuthorizesRequests;

    public function __construct(
        protected CertificateService $certificateService
    ) {}

    /**
     * Display the certificate as inline PDF in the browser.
     */
    public function show(Sponsorship $sponsorship): BinaryFileResponse
    {
        $this->authorize('view', $sponsorship);

        $path = $this->certificateService->generate($sponsorship);

        return response()->file($path, [
            'Content-Type' => 'application/pdf',
        ])->deleteFileAfterSend(true);
    }

    /**
     * Download the certificate as a PDF file.
     */
    public function download(Sponsorship $sponsorship): BinaryFileResponse
    {
        $this->authorize('view', $sponsorship);

        $path = $this->certificateService->generate($sponsorship);
        $filename = 'sponsorship-certificate-' . $sponsorship->horse->name . '.pdf';

        return response()->download($path, $filename, [
            'Content-Type' => 'application/pdf',
        ])->deleteFileAfterSend(true);
    }
}
