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
            ['pemilik_id' => $lapangan->pemilik_id]
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