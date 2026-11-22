<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payout;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PemilikPayoutApiController extends Controller
{
    public function index(Request $request)
    {
        $payouts = Payout::where('pemilik_id', $request->user()->id)
            ->latest()
            ->paginate(15);

        return response()->json($payouts);
    }

    public function download(Request $request, Payout $payout)
    {
        if ($payout->pemilik_id !== $request->user()->id && $request->user()->role !== 'admin') {
            return response()->json(['message' => 'Tidak berhak mengunduh bukti payout ini.'], 403);
        }

        $bookings = Booking::where('payout_id', $payout->id)->get();
        $pdf = Pdf::loadView('pdf.payout', compact('payout', 'bookings'));

        return response($pdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="payout-'.$payout->id.'.pdf"',
        ]);
    }
}
