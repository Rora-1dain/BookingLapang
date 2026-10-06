<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\RefundLog;
use App\Models\User;
use App\Notifications\RefundDiminta;
use App\Notifications\RefundDitolak;
use App\Notifications\RefundGagal;
use Exception;
use Illuminate\Notifications\Notification;
use Midtrans\Transaction;

class RefundService
{
    /**
     * Kirim notifikasi tanpa pernah menggagalkan alur refund. Kegagalan
     * pengiriman (mis. SMTP menolak kredensial) hanya dicatat ke log —
     * perubahan status refund di database tetap dianggap berhasil dan pesan
     * error infrastruktur tidak boleh bocor ke respons API/panel admin.
     */
    private function kirimNotifikasi(?object $penerima, Notification $notifikasi): void
    {
        if (! $penerima) {
            return;
        }

        try {
            $penerima->notify($notifikasi);
        } catch (Exception $e) {
            report($e);
        }
    }

    /**
     * Terjemahkan pesan error mentah Midtrans menjadi pesan yang ramah untuk
     * panel admin. Midtrans menolak refund otomatis dengan HTTP 418 untuk
     * transaksi yang belum boleh direfund ("Payment Provider doesn't allow
     * refund within this time") — untuk kasus ini admin harus memakai jalur
     * refund manual, jadi pesannya diarahkan ke sana.
     */
    private function pesanRefundGagal(string $pesanMentah): string
    {
        if (str_contains($pesanMentah, '418')
            || stripos($pesanMentah, 'doesn\'t allow refund') !== false
            || stripos($pesanMentah, 'does not allow refund') !== false) {
            return 'Midtrans menolak refund otomatis untuk transaksi ini (belum memenuhi syarat waktu refund). '
                .'Lakukan refund manual (transfer langsung ke user), lalu tandai selesai.';
        }

        if (stripos($pesanMentah, 'already refund') !== false || stripos($pesanMentah, 'duplicate') !== false) {
            return 'Transaksi ini sudah pernah direfund di Midtrans.';
        }

        return 'Refund otomatis gagal diproses lewat Midtrans. Coba lagi atau lakukan refund manual.';
    }

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
            $this->kirimNotifikasi($admin, new RefundDiminta($booking));
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

        $this->kirimNotifikasi($booking->user, new RefundDitolak($booking));

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

        $pesanGagal = null;

        try {
            Transaction::refund($booking->payment_reference, [
                'refund_key' => 'refund-'.$booking->id.'-'.time(),
                'amount' => $nominalRefund,
                'reason' => $alasan,
            ]);

            $booking->update(['status_refund' => 'selesai', 'status' => 'cancelled']);
            $hasil = 'berhasil';
        } catch (Exception $e) {
            // Midtrans menolak refund otomatis untuk sebagian transaksi
            // (mis. HTTP 418 "Payment Provider doesn't allow refund within
            // this time"). Simpan pesan yang ramah untuk panel admin, tapi
            // detail mentahnya tetap dicatat ke log untuk penelusuran.
            $pesanGagal = $this->pesanRefundGagal($e->getMessage());

            report($e);

            $booking->update(['status_refund' => 'ditolak', 'catatan_refund' => $pesanGagal]);
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
            // Kegagalan kirim TIDAK boleh menutupi pesan asli "refund gagal".
            $this->kirimNotifikasi($booking->user, new RefundGagal($booking));
            $this->kirimNotifikasi(User::find($adminId), new RefundGagal($booking));

            throw new Exception($pesanGagal ?? 'Refund gagal diproses.');
        }

        return $booking->fresh();
    }
}
