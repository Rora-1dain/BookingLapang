<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Payout;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Exception;

class PayoutService
{
    public function buatPayout(User $pemilik, Carbon $mulai, Carbon $selesai): Payout
    {
        // Ambil semua ID lapangan milik pemilik ini
        $lapanganIds = $pemilik->lapangans()->pluck('id');

        // Cari booking yang: lunas, belum pernah dicairkan, dan dalam rentang periode
        $bookings = Booking::whereIn('lapangan_id', $lapanganIds)
            ->where('status_pembayaran', 'paid')
            ->whereNull('payout_id')
            ->whereBetween('tanggal_booking', [$mulai, $selesai])
            ->get();

        if ($bookings->isEmpty()) {
            throw new Exception('Tidak ada transaksi yang bisa dicairkan pada periode ini.');
        }

        $total = $bookings->sum('pendapatan_pemilik');

        $payout = Payout::create([
            'pemilik_id' => $pemilik->id,
            'periode_mulai' => $mulai,
            'periode_selesai' => $selesai,
            'total_nominal' => $total,
            'status' => 'menunggu',
        ]);

        // Tandai semua booking ini sudah masuk payout ini, biar tidak dicairkan dua kali
        Booking::whereIn('id', $bookings->pluck('id'))->update(['payout_id' => $payout->id]);

        return $payout;
    }

    public function tandaiSelesai(Payout $payout, int $adminId): Payout
    {
        $payout->update([
            'status' => 'selesai',
            'diproses_oleh' => $adminId,
            'selesai_pada' => now(),
        ]);

        return $payout->fresh();
    }

    public function generatePdf(Payout $payout)
    {
        $payout->load(['pemilik', 'bookings.lapangan']);

        return Pdf::loadView('pdf.payout', compact('payout'));
    }
}