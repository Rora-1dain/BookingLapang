<?php

namespace App\Services;

use App\Models\Booking;

class CommissionService
{
    public function hitungKomisi(Booking $booking): Booking
    {
        $persentase = $booking->lapangan->persentase_komisi;
        $nominalKomisi = round($booking->total_harga * ($persentase / 100), 2);
        $pendapatanPemilik = $booking->total_harga - $nominalKomisi;

        $booking->update([
            'nominal_komisi' => $nominalKomisi,
            'pendapatan_pemilik' => $pendapatanPemilik,
        ]);

        return $booking->fresh();
    }
}
