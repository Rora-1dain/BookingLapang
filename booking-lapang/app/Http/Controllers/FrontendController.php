<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Lapangan;
use App\Models\Waitlist;
use App\Services\LoyaltyService;
use Illuminate\Http\Request;

class FrontendController extends Controller
{
    public function home()
    {
        $lapangans = Lapangan::tampilPublik()->with('fotos')->latest()->take(6)->get();

        return view('welcome', compact('lapangans'));
    }

    public function showLapangan(Lapangan $lapangan)
    {
        abort_unless(
            $lapangan->status_approval === 'disetujui' && $lapangan->status === 'aktif',
            404
        );

        $lapangan->load('fotos');
        $ulasan = $lapangan->ulasanTerbaru(10);

        return view('lapangan.show', compact('lapangan', 'ulasan'));
    }

    public function points(Request $request, LoyaltyService $loyaltyService)
    {
        $user = $request->user();
        $riwayatPoin = $user->poinHistories()->latest()->get();
        $tier = $loyaltyService->tentukanTier($user);

        return view('profile.points', compact('user', 'riwayatPoin', 'tier'));
    }

    public function waitlist(Request $request)
    {
        $waitlists = Waitlist::with('lapangan')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get();

        return view('profile.waitlist', compact('waitlists'));
    }

    public function rating(Request $request, Booking $booking)
    {
        $booking->pastikanMilikUser($request->user()->id);

        abort_unless(
            $booking->status === 'confirmed' &&
            $booking->tanggal_booking->isPast() &&
            !$booking->ulasan()->exists(),
            404
        );

        $booking->load('lapangan', 'user');

        return view('booking.rating', compact('booking'));
    }
}
