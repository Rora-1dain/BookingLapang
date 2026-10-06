<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Lapangan;
use App\Models\Ulasan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

/**
 * GET /api/admin/dashboard?dari=YYYY-MM-DD&sampai=YYYY-MM-DD
 * Rute dilindungi middleware 'admin'. Sengaja tidak memakai DashboardService
 * (itu memakai Cache::tags yang hanya jalan di redis/memcached) supaya endpoint
 * ini tetap jalan di cache driver apa pun.
 */
class AdminDashboardApiController extends Controller
{
    private const BULAN = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

    public function index(Request $request)
    {
        $request->validate([
            'dari' => 'nullable|date',
            'sampai' => 'nullable|date|after_or_equal:dari',
        ]);

        $dari = $request->filled('dari') ? Carbon::parse($request->query('dari'))->toDateString() : null;
        $sampai = $request->filled('sampai') ? Carbon::parse($request->query('sampai'))->toDateString() : null;

        $perStatus = $this->periode(Booking::query(), $dari, $sampai)
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->map(fn ($n) => (int) $n);

        $totalBooking = (int) $perStatus->sum();
        $dibatalkan = (int) ($perStatus['cancelled'] ?? 0);

        $dibayar = fn () => $this->periode(Booking::where('status_pembayaran', 'paid'), $dari, $sampai);

        $favorit = $dibayar()
            ->selectRaw('lapangan_id, COUNT(*) as total_booking, SUM(total_harga) as pendapatan')
            ->groupBy('lapangan_id')
            ->orderByDesc('total_booking')
            ->limit(5)
            ->get();
        $namaLapangan = Lapangan::whereIn('id', $favorit->pluck('lapangan_id'))->pluck('nama_lapangan', 'id');

        $terbaru = Booking::with(['user:id,name', 'lapangan:id,nama_lapangan'])
            ->tap(fn ($q) => $this->periode($q, $dari, $sampai))
            ->latest('id')
            ->limit(6)
            ->get()
            ->map(fn (Booking $b) => [
                'id' => $b->id,
                'pemesan' => $b->user?->name,
                'lapangan' => $b->lapangan?->nama_lapangan,
                'tanggal' => $b->tanggal_booking->toDateString(),
                'jam_mulai' => substr((string) $b->jam_mulai, 0, 5),
                'jam_selesai' => substr((string) $b->jam_selesai, 0, 5),
                'total_harga' => (float) $b->total_harga,
                'status' => $b->status,
                'status_pembayaran' => $b->status_pembayaran,
            ]);

        $totalPendapatan = (float) $dibayar()->sum('total_harga');
        $totalKomisi = (float) $dibayar()->sum('nominal_komisi');

        return response()->json([
            'periode' => ['dari' => $dari, 'sampai' => $sampai],
            'ringkasan' => [
                'total_pendapatan' => $totalPendapatan,
                'total_komisi' => $totalKomisi,
                'booking_dibayar' => $dibayar()->count(),
                'total_booking' => $totalBooking,
                'tingkat_pembatalan' => $totalBooking > 0 ? round($dibatalkan / $totalBooking * 100, 1) : 0,
                'per_status' => $perStatus,
            ],
            'antrian' => [
                'lapangan_menunggu' => Lapangan::where('status_approval', 'pending')->count(),
                'verifikasi_menunggu' => User::where('status_verifikasi', 'menunggu')->count(),
                'refund_diproses' => Booking::whereIn('status_refund', ['diminta', 'diproses'])->count(),
                'ulasan_dilaporkan' => Ulasan::where('dilaporkan', true)->where('disembunyikan', false)->count(),
            ],
            'pengguna' => [
                'pemesan' => User::where('role', 'user')->count(),
                'pemilik' => User::where('role', 'pemilik_lapangan')->count(),
            ],
            'pendapatan_bulanan' => $this->pendapatanBulanan(),
            'lapangan_favorit' => $favorit->map(fn ($f) => [
                'lapangan_id' => $f->lapangan_id,
                'nama' => $namaLapangan[$f->lapangan_id] ?? '-',
                'total_booking' => (int) $f->total_booking,
                'pendapatan' => (float) $f->pendapatan,
            ])->values(),
            'booking_terbaru' => $terbaru,
        ]);
    }

    private function periode($query, ?string $dari, ?string $sampai)
    {
        return $query
            ->when($dari, fn ($q) => $q->whereDate('tanggal_booking', '>=', $dari))
            ->when($sampai, fn ($q) => $q->whereDate('tanggal_booking', '<=', $sampai));
    }

    /** GMV booking dibayar, 6 bulan terakhir (termasuk bulan ini). Dikelompokkan di PHP supaya tidak bergantung pada SQL spesifik DB. */
    private function pendapatanBulanan(): array
    {
        $awal = now()->startOfMonth()->subMonths(5);

        $rows = Booking::where('status_pembayaran', 'paid')
            ->whereDate('tanggal_booking', '>=', $awal->toDateString())
            ->get(['tanggal_booking', 'total_harga']);

        return collect(range(0, 5))->map(function ($i) use ($awal, $rows) {
            $bulan = $awal->copy()->addMonths($i);
            $total = $rows
                ->filter(fn ($b) => $b->tanggal_booking->format('Y-m') === $bulan->format('Y-m'))
                ->sum('total_harga');

            return [
                'bulan' => $bulan->format('Y-m'),
                'label' => self::BULAN[$bulan->month - 1],
                'total' => (float) $total,
            ];
        })->all();
    }
}