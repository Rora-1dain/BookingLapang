<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\RefundLog;
use App\Models\User;
use App\Notifications\RefundGagal;
use Exception;
use Midtrans\Transaction;

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

        // [AUDIT] keadaan sebelum refund diproses
        $sebelum = ['status_refund' => $booking->status_refund, 'status' => $booking->status];

        $booking->update([
            'status_refund' => 'diproses',
            'alasan_pembatalan' => $alasan,
        ]);

        try {
            Transaction::refund($booking->payment_reference, [
                'refund_key' => 'refund-'.$booking->id.'-'.time(),
                'amount' => $nominalRefund,
                'reason' => $alasan,
            ]);

            $booking->update(['status_refund' => 'selesai', 'status' => 'cancelled']);
            $hasil = 'berhasil';
        } catch (Exception $e) {
            $booking->update(['status_refund' => 'ditolak', 'catatan_refund' => $e->getMessage()]);
            $hasil = 'gagal: '.$e->getMessage();
        }

        RefundLog::create([
            'booking_id' => $booking->id,
            'admin_id' => $adminId,
            'nominal' => $nominalRefund,
            'persentase' => $persentase * 100,
            'hasil' => $hasil,
        ]);

        // [AUDIT] dicatat sebelum exception di bawah dilempar, jadi refund yang
        // gagal di Midtrans pun tetap meninggalkan jejak. Pesan error mentah
        // Midtrans sengaja tidak ikut dicatat, cukup berhasil/gagal.
        app(AuditService::class)->catat('refund.diajukan', $booking, $sebelum, [
            'status_refund' => $booking->status_refund,
            'status' => $booking->status,
            'nominal' => $nominalRefund,
            'persentase' => $persentase * 100,
            'hasil' => str_starts_with($hasil, 'berhasil') ? 'berhasil' : 'gagal',
        ]);

        if ($booking->status_refund === 'ditolak') {
            // Notifikasi kritikal: terkirim lewat mail apa pun preferensi user.
            $booking->user?->notify(new RefundGagal($booking));
            User::find($adminId)?->notify(new RefundGagal($booking));

            throw new Exception('Refund gagal diproses.');
        }

        return $booking->fresh();
    }
}