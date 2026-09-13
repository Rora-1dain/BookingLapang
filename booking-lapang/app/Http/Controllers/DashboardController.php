<?php
declare(strict_types=1);
namespace App\Http\Controllers;
use App\Models\Booking;
use App\Models\Lapangan;
use App\Models\User;
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
        $statistikVoucher = $this->dashboardService->statistikVoucher();
        $ulasanDilaporkan = $this->dashboardService->ulasanDilaporkan();
        $bookingTerbaru = Booking::with(['user:id,name', 'lapangan:id,nama_lapangan'])
            ->when($dari, fn ($q) => $q->whereDate('tanggal_booking', '>=', $dari))
            ->when($sampai, fn ($q) => $q->whereDate('tanggal_booking', '<=', $sampai))
            ->latest('tanggal_booking')
            ->limit(5)
            ->get();

        // Data khusus admin: komisi platform & antrian yang butuh tindakan
        $totalKomisi = Booking::where('status_pembayaran', 'paid')
            ->when($dari, fn ($q) => $q->whereDate('tanggal_booking', '>=', $dari))
            ->when($sampai, fn ($q) => $q->whereDate('tanggal_booking', '<=', $sampai))
            ->sum('nominal_komisi');

        $lapanganMenunggu = Lapangan::with('pemilik')
            ->where('status_approval', 'pending')
            ->latest()
            ->limit(3)
            ->get();
        $totalLapanganMenunggu = Lapangan::where('status_approval', 'pending')->count();

        $mitraMenunggu = User::where('status_verifikasi', 'menunggu')->count();
        $totalMitraAktif = Lapangan::whereNotNull('pemilik_id')->distinct('pemilik_id')->count('pemilik_id');
        $refundMenunggu = Booking::where('status_refund', 'diproses')->count();

        return view('admin.dashboard', compact(
            'totalPendapatan', 'bookingPerStatus', 'lapanganFavorit',
            'pendapatanBulanan', 'tingkatPembatalan', 'userAktif',
            'bookingTerbaru', 'dari', 'sampai',
            'statistikVoucher', 'ulasanDilaporkan',
            'totalKomisi', 'lapanganMenunggu', 'totalLapanganMenunggu',
            'mitraMenunggu', 'totalMitraAktif', 'refundMenunggu'
        ));
    }
}