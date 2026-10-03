<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Lapangan;
use App\Models\Payout;
use App\Models\Ulasan;
use Illuminate\Http\Request;

/**
 * GET /api/pemilik/dashboard — ringkasan untuk pemilik lapangan yang sedang login.
 * Menggantikan PemilikLapanganApiController::dashboard (kunci lama
 * total_lapangan_aktif & pendapatan_bulan_ini tetap ada).
 */
class PemilikDashboardApiController extends Controller
{
    private const BULAN = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

    public function index(Request $request)
    {
        $user = $request->user();

        $punyaLapangan = Lapangan::where('pemilik_id', $user->id)->exists();
        if (! in_array($user->role, ['pemilik_lapangan', 'admin'], true) && ! $punyaLapangan) {
            return response()->json(['message' => 'Dashboard ini khusus pemilik lapangan.'], 403);
        }

        $lapangans = Lapangan::where('pemilik_id', $user->id)->latest()->get();
        $ids = $lapangans->pluck('id');

        $bookingBulanIni = Booking::whereIn('lapangan_id', $ids)
            ->whereMonth('tanggal_booking', now()->month)
            ->whereYear('tanggal_booking', now()->year);

        $dibayarBulanIni = (clone $bookingBulanIni)->where('status_pembayaran', 'paid');

        $rating = Ulasan::whereHas('booking', fn ($q) => $q->whereIn('lapangan_id', $ids))->avg('rating');

        $payout = Payout::where('pemilik_id', $user->id);
        $payoutSelesai = (float) (clone $payout)->where('status', 'selesai')->sum('total_nominal');
        $payoutMenunggu = (float) (clone $payout)->whereIn('status', ['menunggu', 'diproses'])->sum('total_nominal');

        $terbaru = Booking::with(['user:id,name', 'lapangan:id,nama_lapangan'])
            ->whereIn('lapangan_id', $ids)
            ->latest('id')
            ->limit(8)
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

        $lapanganAktif = $lapangans->where('status_approval', 'disetujui')->count();
        $pendapatan = (float) $dibayarBulanIni->sum('pendapatan_pemilik');

        return response()->json([
            // kunci lama (kompatibilitas)
            'total_lapangan_aktif' => $lapanganAktif,
            'pendapatan_bulan_ini' => (float) (clone $dibayarBulanIni)->sum('total_harga'),

            'verifikasi' => [
                'status' => $user->status_verifikasi,
                'catatan' => $user->catatan_verifikasi,
            ],
            'ringkasan' => [
                'total_lapangan' => $lapangans->count(),
                'lapangan_aktif' => $lapanganAktif,
                'lapangan_menunggu' => $lapangans->where('status_approval', 'pending')->count(),
                'booking_bulan_ini' => (clone $bookingBulanIni)->where('status', '!=', 'cancelled')->count(),
                'pendapatan_bulan_ini' => $pendapatan,
                'rating_rata_rata' => $rating ? round((float) $rating, 1) : null,
                'payout_diterima' => $payoutSelesai,
                'payout_menunggu' => $payoutMenunggu,
            ],
            'lapangan' => $lapangans->map(fn (Lapangan $l) => [
                'id' => $l->id,
                'nama_lapangan' => $l->nama_lapangan,
                'jenis' => $l->jenis,
                'kota' => $l->kota,
                'harga_per_jam' => (float) $l->harga_per_jam,
                'status' => $l->status,
                'status_approval' => $l->status_approval,
                'rating' => $l->rataRataRating(),
            ])->values(),
            'pendapatan_bulanan' => $this->pendapatanBulanan($ids),
            'booking_terbaru' => $terbaru,
        ]);
    }

    /** Bagian pemilik (pendapatan_pemilik) dari booking dibayar, 6 bulan terakhir. */
    private function pendapatanBulanan($lapanganIds): array
    {
        $awal = now()->startOfMonth()->subMonths(5);

        $rows = Booking::whereIn('lapangan_id', $lapanganIds)
            ->where('status_pembayaran', 'paid')
            ->whereDate('tanggal_booking', '>=', $awal->toDateString())
            ->get(['tanggal_booking', 'pendapatan_pemilik']);

        return collect(range(0, 5))->map(function ($i) use ($awal, $rows) {
            $bulan = $awal->copy()->addMonths($i);

            return [
                'bulan' => $bulan->format('Y-m'),
                'label' => self::BULAN[$bulan->month - 1],
                'total' => (float) $rows
                    ->filter(fn ($b) => $b->tanggal_booking->format('Y-m') === $bulan->format('Y-m'))
                    ->sum('pendapatan_pemilik'),
            ];
        })->all();
    }
}