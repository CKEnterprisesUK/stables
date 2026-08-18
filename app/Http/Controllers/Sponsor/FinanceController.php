<?php

namespace App\Http\Controllers\Sponsor;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class FinanceController extends Controller
{
    /**
     * Display the sponsor's invoice/payment history.
     */
    public function index(): View
    {
        $user = auth()->user();

        $invoices = $user->invoices()
            ->with('sponsorship.horse')
            ->orderByDesc('invoice_date')
            ->paginate(20);

        return view('sponsor.finance', compact('invoices'));
    }
}
