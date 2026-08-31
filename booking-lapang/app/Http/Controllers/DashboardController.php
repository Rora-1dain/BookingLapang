<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Services\DashboardService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(protected DashboardService $dashboardService) {}

    public function index(Request $request)
    {
        $dari = $request->query('dari');
        $sampai = $request->query('sampai');

        $totalPendapatan = $this->dashboardService->totalPendapatan($dari, $sampai);
        $bookingPerStatus = $this->dashboardService->jumlahBookingPerStatus($dari, $sampai);
        $lapanganFavorit = $this->dashboardService->lapanganTerfavorit(3, $dari, $sampai);
        $pendapatanBulanan = $this->dashboardService->pendapatanPerBulan();
        $tingkatPembatalan = $this->dashboardService->tingkatPembatalan($dari, $sampai);
        $userAktif = $this->dashboardService->userPalingAktif(5, $dari, $sampai);

        $bookingTerbaru = Booking::with(['user:id,name', 'lapangan:id,nama_lapangan'])
            ->when($dari, fn ($q) => $q->whereDate('tanggal_booking', '>=', $dari))
            ->when($sampai, fn ($q) => $q->whereDate('tanggal_booking', '<=', $sampai))
            ->latest('tanggal_booking')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalPendapatan', 'bookingPerStatus', 'lapanganFavorit',
            'pendapatanBulanan', 'tingkatPembatalan', 'userAktif',
            'bookingTerbaru', 'dari', 'sampai'
        ));
    }
}