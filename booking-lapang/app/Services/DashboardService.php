<?php

namespace App\Services;

use App\Models\Booking;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    public function totalPendapatan(): float
    {
        return (float) Booking::where('status_pembayaran', 'paid')->sum('total_harga');
    }

    public function jumlahBookingPerStatus(): array
    {
        return Booking::select('status', DB::raw('count(*) as jumlah'))
            ->groupBy('status')
            ->pluck('jumlah', 'status')
            ->toArray();
    }

    public function lapanganTerfavorit(int $limit = 3)
    {
        return Booking::select('lapangan_id', DB::raw('count(*) as total_booking'))
            ->with('lapangan')
            ->groupBy('lapangan_id')
            ->orderByDesc('total_booking')
            ->limit($limit)
            ->get();
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

    public function tingkatPembatalan(): float
    {
        $total = Booking::count();

        if ($total === 0) {
            return 0;
        }

        $dibatalkan = Booking::where('status', 'cancelled')->count();

        return round(($dibatalkan / $total) * 100, 2);
    }

    public function pendapatanPerJenisLapangan(): array
    {
        return Booking::join('lapangans', 'bookings.lapangan_id', '=', 'lapangans.id')
            ->select('lapangans.jenis', DB::raw('sum(bookings.total_harga) as total'))
            ->where('bookings.status_pembayaran', 'paid')
            ->groupBy('lapangans.jenis')
            ->pluck('total', 'jenis')
            ->toArray();
    }

    public function userPalingAktif(int $limit = 5)
    {
        return Booking::select('user_id', DB::raw('count(*) as total_booking'))
            ->with('user:id,name')
            ->groupBy('user_id')
            ->orderByDesc('total_booking')
            ->limit($limit)
            ->get();
    }
}
