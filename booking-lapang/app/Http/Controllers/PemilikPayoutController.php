<?php

namespace App\Http\Controllers;

use App\Models\Payout;

class PemilikPayoutController extends Controller
{
    public function index()
    {
        $payouts = Payout::where('pemilik_id', auth()->id())
            ->latest()
            ->get();

        return view('pemilik.payout.index', compact('payouts'));
    }
}