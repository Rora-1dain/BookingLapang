<?php

namespace App\Services;

use App\Models\Lapangan;
use Illuminate\Database\Eloquent\Builder;

class LapanganSearchService
{
    public function cari(array $kriteria)
    {
        return Lapangan::tampilPublik()
            ->when($kriteria['jenis'] ?? null, fn (Builder $q, $v) => $q->where('jenis', $v))
            ->when($kriteria['kota'] ?? null, fn (Builder $q, $v) => $q->where('kota', $v))
            ->when($kriteria['harga_min'] ?? null, fn (Builder $q, $v) => $q->where('harga_per_jam', '>=', $v))
            ->when($kriteria['harga_max'] ?? null, fn (Builder $q, $v) => $q->where('harga_per_jam', '<=', $v))
            ->when($kriteria['kata_kunci'] ?? null, function (Builder $q, $v) {
                $q->where('nama_lapangan', 'like', "%{$v}%");
            })
            ->when($kriteria['rating_min'] ?? null, function (Builder $q, $v) {
                $q->withAvg('ulasans', 'rating')
                    ->whereRaw(
                        '(select avg("ulasans"."rating") from "ulasans" inner join "bookings" on "bookings"."id" = "ulasans"."booking_id" where "lapangans"."id" = "bookings"."lapangan_id") >= ?',
                        [$v]
                    );
            })
            ->paginate(12);
    }
}