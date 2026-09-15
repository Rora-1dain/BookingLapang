<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\User;
use Carbon\Carbon;

class PlatformReportService
{
    public function ringkasanKeuanganPlatform(Carbon $mulai, Carbon $selesai): array
    {
        $query = Booking::where('status_pembayaran', 'paid')
            ->whereBetween('tanggal_booking', [$mulai, $selesai]);

        return [
            'total_gmv' => (float) $query->sum('total_harga'),
            'total_komisi_platform' => (float) $query->sum('nominal_komisi'),
            'total_ke_pemilik' => (float) $query->sum('pendapatan_pemilik'),
            'jumlah_booking' => $query->count(),
        ];
    }

    public function topPemilikBerdasarkanPendapatan(int $limit = 10)
    {
        return User::where('role', 'pemilik_lapangan')
            ->withSum(['lapangans as pendapatan' => function ($q) {
                $q->join('bookings', 'bookings.lapangan_id', '=', 'lapangans.id')
                    ->where('bookings.status_pembayaran', 'paid');
            }], 'bookings.pendapatan_pemilik')
            ->orderByDesc('pendapatan')
            ->limit($limit)
            ->get();
    }

    public function trenGmvBulanan(int $bulanTerakhir = 12): array
{
    $mulai = now()->subMonths($bulanTerakhir - 1)->startOfMonth();

    $rows = Booking::where('status_pembayaran', 'paid')
        ->where('tanggal_booking', '>=', $mulai)
        ->selectRaw("TO_CHAR(tanggal_booking, 'YYYY-MM') as bulan, SUM(total_harga) as gmv, SUM(nominal_komisi) as komisi")
        ->groupBy('bulan')
        ->orderBy('bulan')
        ->get()
        ->keyBy('bulan');

    return [
        'label' => $rows->keys()->toArray(),
        'data' => $rows->values()->map(fn ($r) => (float) $r->gmv)->toArray(),
        'data_komisi' => $rows->values()->map(fn ($r) => (float) $r->komisi)->toArray(),
    ];
}
}