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
    public function download(Payout $payout)
    {
        abort_if($payout->pemilik_id !== auth()->id(), 403);

        $bookings = Booking::where('payout_id', $payout->id)->get();

        $pdf = \PDF::loadView('pemilik.payout.pdf', compact('payout', 'bookings'));

        return $pdf->download('payout-' . $payout->id . '.pdf');
    }
}