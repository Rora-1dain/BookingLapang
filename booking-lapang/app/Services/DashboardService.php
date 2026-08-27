<?php

namespace App\Services;

use App\Models\Booking;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class DashboardService
{
    /**
     * Terapkan filter tanggal ke query kalau $dari/$sampai diisi.
     */
    protected function applyDateFilter($query, ?string $dari, ?string $sampai)
    {
        if ($dari) {
            $query->whereDate('tanggal_booking', '>=', $dari);
        }
        if ($sampai) {
            $query->whereDate('tanggal_booking', '<=', $sampai);
        }

        return $query;
    }

    public function totalPendapatan(?string $dari = null, ?string $sampai = null): float
    {
        $cacheKey = 'dashboard.total_pendapatan.' . ($dari ?? 'all') . '.' . ($sampai ?? 'all');

        return Cache::remember($cacheKey, 600, function () use ($dari, $sampai) {
            $query = Booking::where('status_pembayaran', 'paid');
            $this->applyDateFilter($query, $dari, $sampai);

            return (float) $query->selectRaw('COALESCE(sum(total_harga), 0) as total')->value('total');
        });
    }

    public function jumlahBookingPerStatus(?string $dari = null, ?string $sampai = null): array
    {
        $query = Booking::select('status', DB::raw('count(*) as jumlah'));
        $this->applyDateFilter($query, $dari, $sampai);

        return $query->groupBy('status')
            ->pluck('jumlah', 'status')
            ->toArray();
    }

    public function lapanganTerfavorit(int $limit = 3, ?string $dari = null, ?string $sampai = null): array
    {
        $cacheKey = "dashboard.lapangan_favorit.{$limit}." . ($dari ?? 'all') . '.' . ($sampai ?? 'all');

        return Cache::remember($cacheKey, 600, function () use ($limit, $dari, $sampai) {
            $query = Booking::select('lapangan_id', DB::raw('count(*) as total_booking'))
                ->with('lapangan');
            $this->applyDateFilter($query, $dari, $sampai);

            return $query->groupBy('lapangan_id')
                ->orderByDesc('total_booking')
                ->limit($limit)
                ->get()
                ->map(function ($item) {
                    return [
                        'lapangan_id' => $item->lapangan_id,
                        'total_booking' => $item->total_booking,
                        'nama_lapangan' => $item->lapangan->nama_lapangan ?? '-',
                    ];
                })
                ->toArray();
        });
    }

    public function pendapatanPerBulan(int $bulanTerakhir = 6): array
    {
        return Booking::select(
                DB::raw("DATE_FORMAT(tanggal_booking, '%Y-%m') as bulan"),
                DB::raw('sum(total_harga) as total')
            )
            ->where('status_pembayaran', 'paid')
            ->where('tanggal_booking', '>=', now()->subMonths($bulanTerakhir))
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->pluck('total', 'bulan')
            ->toArray();
    }

    public function tingkatPembatalan(?string $dari = null, ?string $sampai = null): float
    {
        $query = Booking::query();
        $this->applyDateFilter($query, $dari, $sampai);

        $total = (clone $query)->count();

        if ($total === 0) {
            return 0;
        }

        $dibatalkan = (clone $query)->where('status', 'cancelled')->count();

        return round(($dibatalkan / $total) * 100, 2);
    }

    public function pendapatanPerJenisLapangan(?string $dari = null, ?string $sampai = null): array
    {
        $query = Booking::join('lapangans', 'bookings.lapangan_id', '=', 'lapangans.id')
            ->select('lapangans.jenis', DB::raw('sum(bookings.total_harga) as total'))
            ->where('bookings.status_pembayaran', 'paid');

        if ($dari) {
            $query->whereDate('bookings.tanggal_booking', '>=', $dari);
        }
        if ($sampai) {
            $query->whereDate('bookings.tanggal_booking', '<=', $sampai);
        }

        return $query->groupBy('lapangans.jenis')
            ->pluck('total', 'jenis')
            ->toArray();
    }

    public function userPalingAktif(int $limit = 5, ?string $dari = null, ?string $sampai = null): array
    {
        $query = Booking::select('user_id', DB::raw('count(*) as total_booking'))
            ->with('user:id,name');
        $this->applyDateFilter($query, $dari, $sampai);

        return $query->groupBy('user_id')
            ->orderByDesc('total_booking')
            ->limit($limit)
            ->get()
            ->map(function ($item) {
                return [
                    'user_id' => $item->user_id,
                    'total_booking' => $item->total_booking,
                    'nama' => $item->user->name ?? '-',
                ];
            })
            ->toArray();
    }
}