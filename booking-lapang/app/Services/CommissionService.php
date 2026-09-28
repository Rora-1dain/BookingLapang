<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Lapangan;
use Exception;

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

    // Hanya memengaruhi booking yang dibayar SETELAH perubahan (komisi dihitung
    // saat pembayaran lunas lewat hitungKomisi()); booking lama tidak berubah.
    public function ubahPersentaseKomisi(Lapangan $lapangan, float $persentase): Lapangan
    {
        if ($persentase < 0 || $persentase > 100) {
            throw new Exception('Persentase komisi harus antara 0 dan 100.');
        }

        $sebelum = ['persentase_komisi' => (float) $lapangan->persentase_komisi]; // [AUDIT]

        $lapangan->update(['persentase_komisi' => $persentase]);

        app(AuditService::class)->catat( // [AUDIT]
            'komisi.diubah', $lapangan, $sebelum, ['persentase_komisi' => $persentase]
        );

        return $lapangan->fresh();
    }
}
