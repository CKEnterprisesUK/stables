<?php

namespace App\Http\Controllers\Sponsor;

use App\Http\Controllers\Controller;
use App\Models\Sponsorship;
use App\Services\CertificateService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Response;

class CertificateController extends Controller
{
    use AuthorizesRequests;

    public function __construct(
        protected CertificateService $certificateService
    ) {}

    /**
     * Display the certificate as inline PDF in the browser.
     */
    public function show(Sponsorship $sponsorship): Response
    {
        $this->authorize('view', $sponsorship);

        $pdf = $this->certificateService->generate($sponsorship);

        return response($pdf, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="certificate.pdf"',
        ]);
    }

    /**
     * Download the certificate as a PDF file.
     */
    public function download(Sponsorship $sponsorship): Response
    {
        $this->authorize('view', $sponsorship);

        $pdf = $this->certificateService->generate($sponsorship);
        $filename = 'sponsorship-certificate-' . $sponsorship->horse->name . '.pdf';

        return response($pdf, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}
