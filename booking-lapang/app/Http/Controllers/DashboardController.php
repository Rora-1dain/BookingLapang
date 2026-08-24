<?php

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

        $totalPendapatan   = $this->dashboardService->totalPendapatan();
        $bookingPerStatus  = $this->dashboardService->jumlahBookingPerStatus();
        $lapanganFavorit   = $this->dashboardService->lapanganTerfavorit();
        $pendapatanBulanan = $this->dashboardService->pendapatanPerBulan();
        $tingkatPembatalan = $this->dashboardService->tingkatPembatalan();
        $userAktif         = $this->dashboardService->userPalingAktif();

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