<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\RefundLog;
use App\Models\User;
use App\Notifications\RefundDiminta;
use App\Notifications\RefundDitolak;
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

    /**
     * User mengajukan refund sendiri (dua langkah). Tidak memanggil Midtrans —
     * hanya menandai booking menunggu keputusan admin. Admin yang nanti
     * memproses (ajukanRefund) atau menolak (tolakPermintaanRefund).
     */
    public function mintaRefund(Booking $booking, string $alasan, int $userId): Booking
    {
        if ($booking->user_id !== $userId) {
            throw new Exception('Booking ini bukan milik Anda.');
        }

        if ($booking->status_pembayaran !== 'paid') {
            throw new Exception('Hanya booking yang sudah dibayar yang bisa diajukan refund.');
        }

        if ($booking->status_refund !== 'belum_refund') {
            throw new Exception('Refund untuk booking ini sudah pernah diajukan.');
        }

        if (! in_array($booking->status, ['pending', 'confirmed'], true)) {
            throw new Exception('Booking ini tidak bisa diajukan refund.');
        }

        // Hanya tolak kalau tanggalnya sudah lewat (booking hari ini masih boleh).
        if ($booking->tanggal_booking->lt(now()->startOfDay())) {
            throw new Exception('Booking yang jadwalnya sudah lewat tidak bisa diajukan refund.');
        }

        $sebelum = ['status_refund' => $booking->status_refund, 'status' => $booking->status];

        $booking->update([
            'status_refund' => 'diminta',
            'alasan_pembatalan' => $alasan,
        ]);

        app(AuditService::class)->catat('refund.diminta', $booking, $sebelum, [
            'status_refund' => 'diminta',
            'alasan' => $alasan,
        ]);

        // Beri tahu seluruh admin bahwa ada pengajuan refund baru. Kegagalan
        // kirim notifikasi tidak boleh membatalkan pengajuan user (DB sudah
        // ter-update), jadi ditelan per-admin.
        foreach (User::where('role', 'admin')->get() as $admin) {
            try {
                $admin->notify(new RefundDiminta($booking));
            } catch (Exception $e) {
                report($e);
            }
        }

        return $booking->fresh();
    }

    /**
     * Admin menolak pengajuan refund user (tanpa menyentuh Midtrans).
     */
    public function tolakPermintaanRefund(Booking $booking, ?string $catatan, int $adminId): Booking
    {
        if ($booking->status_refund !== 'diminta') {
            throw new Exception('Hanya pengajuan refund yang menunggu bisa ditolak.');
        }

        $sebelum = ['status_refund' => $booking->status_refund];

        $booking->update([
            'status_refund' => 'ditolak',
            'catatan_refund' => $catatan,
        ]);

        app(AuditService::class)->catat('refund.ditolak', $booking, $sebelum, [
            'status_refund' => 'ditolak',
            'catatan' => $catatan,
        ]);

        $booking->user?->notify(new RefundDitolak($booking));

        return $booking->fresh();
    }

    public function ajukanRefund(Booking $booking, string $alasan, int $adminId): Booking
    {
        if ($booking->status_pembayaran !== 'paid') {
            throw new Exception('Hanya booking yang sudah dibayar yang bisa direfund.');
        }

        // Boleh memproses booking yang belum pernah diajukan (aksi admin
        // langsung) maupun pengajuan user yang masih menunggu ('diminta').
        if (! in_array($booking->status_refund, ['belum_refund', 'diminta'], true)) {
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
