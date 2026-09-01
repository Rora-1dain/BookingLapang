<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Ulasan;
use Exception;

class UlasanService
{
    public function buatUlasan(Booking $booking, int $rating, ?string $komentar): Ulasan
    {
        if ($booking->status !== 'confirmed') {
            throw new Exception('Hanya booking yang sudah confirmed yang bisa diulas.');
        }

        if ($booking->tanggal_booking->isFuture()) {
            throw new Exception('Booking belum selesai, belum bisa diulas.');
        }

        if ($booking->ulasan()->exists()) {
            throw new Exception('Booking ini sudah pernah diulas.');
        }

        return Ulasan::create([
            'booking_id' => $booking->id,
            'rating' => $rating,
            'komentar' => $komentar,
        ]);
    }

    public function editUlasan(Ulasan $ulasan, int $userId, int $rating, ?string $komentar): Ulasan
    {
        if ($ulasan->booking->user_id !== $userId) {
            throw new Exception('Anda tidak berhak mengedit ulasan ini.');
        }

        if ($ulasan->created_at->diffInHours(now()) > 24) {
            throw new Exception('Batas waktu edit ulasan (24 jam) sudah lewat.');
        }

        $ulasan->update(['rating' => $rating, 'komentar' => $komentar]);

        return $ulasan;
    }

    public function laporkanUlasan(Ulasan $ulasan): Ulasan
    {
        $ulasan->update(['dilaporkan' => true]);

        return $ulasan;
    }
}
