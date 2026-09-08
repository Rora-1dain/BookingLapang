<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\RefundLog;
use Midtrans\Transaction;
use Exception;

class RefundService
{
    public function hitungPersentaseRefund(Booking $booking): float
    {
        $jamSebelumJadwal = now()->diffInHours($booking->tanggal_booking, false);

        return $jamSebelumJadwal >= 24 ? 1.0 : 0.5;
    }

    public function ajukanRefund(Booking $booking, string $alasan, int $adminId): Booking
    {
        if ($booking->status_pembayaran !== 'paid') {
            throw new Exception('Hanya booking yang sudah dibayar yang bisa direfund.');
        }

        if ($booking->status_refund !== 'belum_refund') {
            throw new Exception('Refund untuk booking ini sudah pernah diajukan.');
        }

        $persentase = $this->hitungPersentaseRefund($booking);
        $nominalRefund = (int) ($booking->total_harga * $persentase);

        $booking->update([
            'status_refund' => 'diproses',
            'alasan_pembatalan' => $alasan,
        ]);

        try {
            Transaction::refund($booking->payment_reference, [
                'refund_key' => 'refund-' . $booking->id . '-' . time(),
                'amount' => $nominalRefund,
                'reason' => $alasan,
            ]);

            $booking->update(['status_refund' => 'selesai', 'status' => 'cancelled']);
            $hasil = 'berhasil';
        } catch (Exception $e) {
            $booking->update(['status_refund' => 'ditolak', 'catatan_refund' => $e->getMessage()]);
            $hasil = 'gagal: ' . $e->getMessage();
        }

        RefundLog::create([
            'booking_id' => $booking->id,
            'admin_id' => $adminId,
            'nominal' => $nominalRefund,
            'persentase' => $persentase * 100,
            'hasil' => $hasil,
        ]);

        if ($booking->status_refund === 'ditolak') {
            throw new Exception('Refund gagal diproses.');
        }

        return $booking->fresh();
    }
}
