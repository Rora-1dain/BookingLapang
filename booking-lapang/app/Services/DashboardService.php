<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Booking;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    /**
     * Terapkan filter tanggal ke query kalau $dari/$sampai diisi.
     */
    protected function terapkanFilterTanggal($query, ?string $dari, ?string $sampai)
    {
        if ($dari) {
            $query->whereDate('tanggal_booking', '>=', $dari);
        }
        if ($sampai) {
            $query->whereDate('tanggal_booking', '<=', $sampai);
        }

        return $query;
    }


    /**
     * Menjumlahkan seluruh pendapatan dari booking berstatus pembayaran 'paid',
     * dengan opsi filter rentang tanggal. Hasil di-cache selama 10 menit
     * di bawah tag 'dashboard' agar bisa di-flush sekaligus tanpa perlu tahu
     * kombinasi filter yang pernah dipakai.
     *
     * @param string|null $dari Tanggal awal filter, format Y-m-d (opsional)
     * @param string|null $sampai Tanggal akhir filter, format Y-m-d (opsional)
     * @return float Total pendapatan dalam Rupiah
     */
    public function totalPendapatan(?string $dari = null, ?string $sampai = null): float
    {
        $cacheKey = 'dashboard.total_pendapatan.'.($dari ?? 'all').'.'.($sampai ?? 'all');

        return Cache::tags(['dashboard'])->remember($cacheKey, 600, function () use ($dari, $sampai) {
            $query = Booking::where('status_pembayaran', 'paid');
            $this->terapkanFilterTanggal($query, $dari, $sampai);

            return (float) $query->selectRaw('COALESCE(sum(total_harga), 0) as total')->value('total');
        });
    }

    /**
     * Menghitung jumlah booking untuk setiap nilai status (pending/confirmed/cancelled).
     *
     * @return array<string, int> Contoh: ['pending' => 5, 'confirmed' => 3, 'cancelled' => 2]
     */
    public function jumlahBookingPerStatus(?string $dari = null, ?string $sampai = null): array
    {
        $query = Booking::select('status', DB::raw('count(*) as jumlah'));
        $this->terapkanFilterTanggal($query, $dari, $sampai);

        return $query->groupBy('status')
            ->pluck('jumlah', 'status')
            ->toArray();
    }


    /**
     * Mengambil lapangan dengan jumlah booking terbanyak.
     *
     * @param int $limit Jumlah lapangan yang ditampilkan, default 3
     * @return \Illuminate\Support\Collection Koleksi booking teragregasi per lapangan, diurutkan dari yang terbanyak
     */
    public function lapanganTerfavorit(int $limit = 3, ?string $dari = null, ?string $sampai = null): array
    {
        $cacheKey = "dashboard.lapangan_favorit.{$limit}.".($dari ?? 'all').'.'.($sampai ?? 'all');

        return Cache::tags(['dashboard'])->remember($cacheKey, 600, function () use ($limit, $dari, $sampai) {
            $query = Booking::select('lapangan_id', DB::raw('count(*) as total_booking'))
                ->with('lapangan');
            $this->terapkanFilterTanggal($query, $dari, $sampai);

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


    /**
     * Mengelompokkan total pendapatan (status_pembayaran = paid) per bulan
     * dalam rentang N bulan terakhir. Cocok untuk data grafik batang.
     *
     * @param int $bulanTerakhir Jumlah bulan ke belakang yang dihitung, default 6
     * @return array<string, float> Contoh: ['2026-07' => 500000, '2026-08' => 750000]
     */
    public function pendapatanPerBulan(int $bulanTerakhir = 6): array
    {
        return Booking::select(
                DB::raw("TO_CHAR(tanggal_booking, 'YYYY-MM') as bulan"),
                DB::raw('sum(total_harga) as total')
            )
            ->where('status_pembayaran', 'paid')
            ->where('tanggal_booking', '>=', now()->subMonths($bulanTerakhir))
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->pluck('total', 'bulan')
            ->toArray();
    }

    /**
     * Menghitung persentase booking berstatus 'cancelled' dibanding total seluruh booking.
     *
     * @return float Persentase pembatalan, dibulatkan 2 desimal (0 jika belum ada booking sama sekali)
     */
    public function tingkatPembatalan(?string $dari = null, ?string $sampai = null): float
    {
        $query = Booking::query();
        $this->terapkanFilterTanggal($query, $dari, $sampai);

        $total = (clone $query)->count();

        if ($total === 0) {
            return 0;
        }

        $dibatalkan = (clone $query)->where('status', 'cancelled')->count();

        return round(($dibatalkan / $total) * 100, 2);
    }

    /**
     * Mengelompokkan total pendapatan berdasarkan jenis lapangan (futsal/badminton/basket).
     *
     * @return array<string, float> Contoh: ['futsal' => 1200000, 'badminton' => 400000]
     */
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


    /**
     * Mengambil user dengan jumlah booking terbanyak.
     *
     * @param int $limit Jumlah user yang ditampilkan, default 5
     * @return \Illuminate\Support\Collection Koleksi booking teragregasi per user, lengkap dengan nama user
     */
    public function userPalingAktif(int $limit = 5, ?string $dari = null, ?string $sampai = null): array
    {
        $query = Booking::select('user_id', DB::raw('count(*) as total_booking'))
            ->with('user:id,name');
        $this->terapkanFilterTanggal($query, $dari, $sampai);

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


    /**
     * Menghitung berapa kali tiap voucher dipakai.
     *
     * @return \Illuminate\Support\Collection
     */
    public function statistikVoucher()
    {
        return \App\Models\VoucherUsage::selectRaw('voucher_id, count(*) as total_pakai')
            ->groupBy('voucher_id')
            ->with('voucher:id,kode')
            ->get();
    }


    /**
     * Mengambil daftar ulasan yang ditandai dilaporkan, menunggu tinjauan admin.
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function ulasanDilaporkan()
    {
        return \App\Models\Ulasan::where('dilaporkan', true)
            ->with('booking.user:id,name')
            ->latest()
            ->paginate(20);
    }
}