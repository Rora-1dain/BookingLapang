<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\PlatformReportService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminLaporanApiController extends Controller
{
    public function platform(Request $request, PlatformReportService $reportService)
    {
        $mulai = $request->filled('mulai')
            ? Carbon::parse($request->query('mulai'))
            : now()->startOfMonth();

        $selesai = $request->filled('selesai')
            ? Carbon::parse($request->query('selesai'))
            : now()->endOfMonth();

        $ringkasan = $reportService->ringkasanKeuanganPlatform($mulai, $selesai);
        $topPemilik = $reportService->topPemilikBerdasarkanPendapatan(10);

        return response()->json([
            'periode' => ['mulai' => $mulai->toDateString(), 'selesai' => $selesai->toDateString()],
            'ringkasan' => $ringkasan,
            'top_pemilik' => $topPemilik,
        ]);
    }
}
