<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payout;
use Illuminate\Http\Request;

class PemilikPayoutController extends Controller
{
    public function index(Request $request)
    {
        $base = fn () => Payout::where('pemilik_id', auth()->id());

        $counts = [
            'semua' => $base()->count(),
            'menunggu' => $base()->where('status', 'menunggu')->count(),
            'diproses' => $base()->where('status', 'diproses')->count(),
            'selesai' => $base()->where('status', 'selesai')->count(),
        ];

        $filter = $request->query('filter', 'semua');

        $query = $base();
        if ($filter !== 'semua') {
            $query->where('status', $filter);
        }

        $payouts = $query->latest('periode_mulai')->paginate(10)->withQueryString();

        $totalDiproses = $base()->where('status', 'diproses')->sum('total_nominal');
        $totalSelesaiBulanIni = $base()->where('status', 'selesai')
            ->whereMonth('selesai_pada', now()->month)
            ->sum('total_nominal');

        return view('pemilik.payout.index', compact('payouts', 'filter', 'counts', 'totalDiproses', 'totalSelesaiBulanIni'));
    }

    public function download(Payout $payout)
    {
        abort_if($payout->pemilik_id !== auth()->id(), 403);

        $bookings = Booking::where('payout_id', $payout->id)->get();

        $pdf = \PDF::loadView('pemilik.payout.pdf', compact('payout', 'bookings'));

        return $pdf->download('payout-'.$payout->id.'.pdf');
    }
}
