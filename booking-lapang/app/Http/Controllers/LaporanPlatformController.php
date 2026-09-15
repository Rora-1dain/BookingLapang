<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\PlatformReportService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LaporanPlatformController extends Controller
{
    public function index(Request $request, PlatformReportService $service)
    {
        $mulai = Carbon::parse($request->input('mulai', now()->startOfMonth()));
        $selesai = Carbon::parse($request->input('selesai', now()->endOfMonth()));
        $ringkasan = $service->ringkasanKeuanganPlatform($mulai, $selesai);
        $topPemilik = $service->topPemilikBerdasarkanPendapatan(10);
        $tren = $service->trenGmvBulanan(12);

        return view('admin.laporan.platform', compact('ringkasan', 'topPemilik', 'tren', 'mulai', 'selesai'));
    }
}