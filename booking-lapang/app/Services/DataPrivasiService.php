<?php

namespace App\Services;

use App\Models\Payout;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DataPrivasiService
{
    /**
     * Kumpulkan data pribadi user. TANPA password, remember_token, two_factor_*.
     */
    public function eksporData(User $user): array
    {
        app(AuditService::class)->catat('privasi.ekspor_data', $user);

        return [
            'diekspor_pada' => now()->toIso8601String(),
            'profil' => $user->only(['name', 'email', 'created_at']),
            'booking' => $user->bookings()
                ->get(['id', 'lapangan_id', 'tanggal_booking', 'jam_mulai', 'jam_selesai', 'total_harga', 'status'])
                ->toArray(),
            'poin' => $user->poinHistories()
                ->get(['jumlah', 'keterangan', 'created_at'])
                ->toArray(),
            // Ulasan terhubung ke user lewat booking
            'ulasan' => $user->ulasans()
                ->get(['ulasans.booking_id', 'ulasans.rating', 'ulasans.komentar', 'ulasans.created_at'])
                ->toArray(),
            // Hanya pesan yang DIKIRIM user. Pesan lawan bicara adalah data pribadi orang lain.
            'chat' => $user->pesansDikirim()
                ->get(['percakapan_id', 'isi', 'created_at'])
                ->toArray(),
        ];
    }

    /**
     * Tolak jika masih ada kewajiban yang belum selesai.
     */
    protected function cekBisaDihapus(User $user): void
    {
        // 1. Booking aktif di masa depan
        $bookingAktif = $user->bookings()
            ->where('status', '!=', 'cancelled')
            ->where('tanggal_booking', '>=', now()->toDateString())
            ->exists();

        if ($bookingAktif) {
            throw new Exception('Masih ada booking aktif. Selesaikan atau batalkan dulu.');
        }

        // 2. Refund berstatus diproses (nama kolom mengikuti contoh Ardan: status_refund)
        $refundDiproses = $user->bookings()
            ->where('status_refund', 'diproses')
            ->exists();

        if ($refundDiproses) {
            throw new Exception('Masih ada refund yang sedang diproses. Tunggu sampai selesai.');
        }

        // 3. Payout milik user (pemilik) yang belum selesai: menunggu / diproses.
        // User biasa tidak punya baris payout, jadi tidak perlu cek role.
        $payoutBelumSelesai = Payout::where('pemilik_id', $user->id)
            ->where('status', '!=', 'selesai')
            ->exists();

        if ($payoutBelumSelesai) {
            throw new Exception('Masih ada payout yang belum selesai.');
        }
    }

    /**
     * Hapus akun lewat anonimisasi. Baris booking & transaksi tetap ada.
     */
    public function ajukanHapusAkun(User $user, string $password): void
    {
        if (! Hash::check($password, $user->password)) {
            throw new Exception('Password salah.');
        }

        $this->cekBisaDihapus($user);

        $pathDokumen = $user->path_dokumen_identitas;

        DB::transaction(function () use ($user) {
            // Audit dicatat SEBELUM data pelaku diubah. Jangan masukkan data pribadi ke log.
            app(AuditService::class)->catat('privasi.akun_dihapus', $user);

            $user->tokens()->delete(); // cabut semua token Sanctum

            // forceFill: remember_token & kolom two_factor_* tidak ada di #[Fillable],
            // jadi update() akan diam-diam mengabaikannya
            $user->forceFill([
                'name' => 'Pengguna Terhapus',
                'email' => 'terhapus-'.$user->id.'-'.Str::random(8).'@invalid.local',
                'password' => Str::random(40), // cast 'hashed' di User yang meng-hash; tidak bisa dipakai login
                'path_dokumen_identitas' => null,
                'catatan_verifikasi' => null,
                'ip_terakhir' => null,
                'remember_token' => null,
                'two_factor_secret' => null,
                'two_factor_aktif_pada' => null,
                'two_factor_recovery_codes' => null,
            ])->save();
        });

        // File dihapus setelah transaksi DB sukses
        if ($pathDokumen) {
            Storage::disk('local')->delete($pathDokumen);
        }
    }
}
