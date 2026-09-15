<?php

namespace App\Services;

use App\Events\PesanDikirim;
use App\Models\Lapangan;
use App\Models\Percakapan;
use App\Models\Pesan;
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
        if (!in_array($pengirimId, [$percakapan->user_id, $percakapan->pemilik_id])) {
            throw new \Exception('Anda bukan bagian dari percakapan ini.');
        }

        $pesan = Pesan::create([
            'percakapan_id' => $percakapan->id,
            'pengirim_id'   => $pengirimId,
            'isi'           => $isi,
        ]);

        $penerimaId = $pengirimId === $percakapan->user_id
            ? $percakapan->pemilik_id
            : $percakapan->user_id;

        // Notifikasi & broadcast bersifat "nice to have" -- kalau gagal
        // (driver belum di-setup, tabel notifications belum ada, dsb),
        // jangan sampai bikin pesan gagal tersimpan / request 500.
        try {
            \App\Models\User::find($penerimaId)?->notify(new \App\Notifications\PesanBaruDiterima($pesan));
        } catch (\Throwable $e) {
            Log::warning('Gagal kirim notifikasi pesan baru: ' . $e->getMessage());
        }

        try {
            broadcast(new PesanDikirim($pesan))->toOthers();
        } catch (\Throwable $e) {
            Log::warning('Gagal broadcast pesan baru: ' . $e->getMessage());
        }

        return $pesan;
    }

    public function tandaiDibaca(Percakapan $percakapan, int $userId): void
    {
        $percakapan->pesans()
            ->where('pengirim_id', '!=', $userId)
            ->whereNull('dibaca_pada')
            ->update(['dibaca_pada' => now()]);
    }
}