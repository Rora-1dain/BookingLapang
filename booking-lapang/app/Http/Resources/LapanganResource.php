<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LapanganResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'nama_lapangan' => $this->nama_lapangan,
            'jenis' => $this->jenis,
            'harga_per_jam' => (float) $this->harga_per_jam,
            'status' => $this->status,
            'kota' => $this->kota,
            'rating' => $this->rataRataRating(),
            'foto_utama' => $this->fotoUtama()
                ? asset('storage/'.$this->fotoUtama()->path_file)
                : null,
            // hanya disertakan saat memanggil Lapangan::with('jadwalOperasionals')
            // di controller — kalau relasi tidak di-load, ini otomatis null,
            // jadi tidak memicu query N+1 tambahan saat menampilkan daftar lapangan.
            'jadwal_hari_ini' => $this->whenLoaded('jadwalOperasionals', function () {
                $jadwal = $this->jadwalOperasionals->firstWhere('hari', now()->dayOfWeek);

                return $jadwal ? [
                    'is_tutup' => $jadwal->is_tutup,
                    'jam_buka' => $jadwal->jam_buka,
                    'jam_tutup' => $jadwal->jam_tutup,
                ] : null;
            }),
        ];
    }
}
