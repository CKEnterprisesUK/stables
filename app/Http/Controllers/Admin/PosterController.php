<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Horse;
use App\Services\PosterService;
use Illuminate\View\View;

class PosterController extends Controller
{
    public function __construct(
        protected PosterService $posterService
    ) {}

    /**
     * Display a printable sponsorship poster for a specific horse.
     */
    public function horse(Horse $horse): View
    {
        $data = $this->posterService->getHorsePosterData($horse);

        return view('posters.horse', $data);
    }

    /**
     * Display a printable generic sponsorship poster.
     */
    public function generic(): View
    {
        $data = $this->posterService->getGenericPosterData();

        return view('posters.generic', $data);
    }
}
