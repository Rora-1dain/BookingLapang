<?php

namespace App\Services;

use App\Events\PesanDibaca;
use App\Events\PesanDikirim;
use App\Models\Lapangan;
use App\Models\Percakapan;
use App\Models\Pesan;
use App\Models\User;
use App\Notifications\PesanBaruDiterima;
use Illuminate\Support\Facades\Log;

class ChatService
{
    public function mulaiAtauLanjutkan(int $userId, int $lapanganId): Percakapan
    {
        $lapangan = Lapangan::findOrFail($lapanganId);

        return Percakapan::firstOrCreate(
            ['lapangan_id' => $lapanganId, 'user_id' => $userId],
            ['pemilik_id' => $lapangan->pemilik_id, 'tipe' => 'lapangan']
        );
    }

    /**
     * Mulai (atau lanjutkan) percakapan user dengan admin platform.
     * Dipakai tombol "Hubungi Admin" di halaman membership. Admin penerima
     * ditentukan config('services.admin_chat_user_id'); kalau kosong, fallback
     * ke user dengan role 'admin' pertama.
     */
    public function mulaiAtauLanjutkanAdmin(int $userId): Percakapan
    {
        $adminId = config('services.admin_chat_user_id');

        if (! $adminId) {
            $adminId = User::where('role', 'admin')->orderBy('id')->value('id');
        }

        if (! $adminId) {
            throw new \Exception('Belum ada admin yang bisa dihubungi.');
        }

        if ((int) $adminId === $userId) {
            throw new \Exception('Admin tidak bisa memulai percakapan dengan dirinya sendiri.');
        }

        return Percakapan::firstOrCreate(
            ['tipe' => 'admin', 'user_id' => $userId],
            ['pemilik_id' => (int) $adminId, 'lapangan_id' => null]
        );
    }

    public function kirimPesan(Percakapan $percakapan, int $pengirimId, string $isi): Pesan
    {
        if (! in_array($pengirimId, [$percakapan->user_id, $percakapan->pemilik_id])) {
            throw new \Exception('Anda bukan bagian dari percakapan ini.');
        }

        $pesan = Pesan::create([
            'percakapan_id' => $percakapan->id,
            'pengirim_id' => $pengirimId,
            'isi' => $isi,
        ]);

        $penerimaId = $pengirimId === $percakapan->user_id
            ? $percakapan->pemilik_id
            : $percakapan->user_id;

        try {
            User::find($penerimaId)?->notify(new PesanBaruDiterima($pesan));
        } catch (\Throwable $e) {
            Log::warning('Gagal kirim notifikasi pesan baru: '.$e->getMessage());
        }

        // Pesan sudah tersimpan; kalau Pusher bermasalah jangan sampai API balas 500.
        try {
            broadcast(new PesanDikirim($pesan))->toOthers();
        } catch (\Throwable $e) {
            Log::warning('Gagal broadcast pesan: '.$e->getMessage());
        }

        return $pesan;
    }

    public function tandaiDibaca(Percakapan $percakapan, int $userId): void
    {
        $jumlah = $percakapan->pesans()
            ->where('pengirim_id', '!=', $userId)
            ->whereNull('dibaca_pada')
            ->update(['dibaca_pada' => now()]);

        if ($jumlah > 0) {
            try {
                broadcast(new PesanDibaca($percakapan->id, $userId))->toOthers();
            } catch (\Throwable $e) {
                Log::warning('Gagal broadcast status dibaca: '.$e->getMessage());
            }
        }
    }
}