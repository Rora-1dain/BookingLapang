<?php

namespace App\Http\Controllers;

use App\Models\Lapangan;
use App\Services\LapanganSearchService;
use Illuminate\Http\Request;

class LapanganController extends Controller
{
    public function index(Request $request, LapanganSearchService $searchService)
    {
        $kriteria = $request->only([
            'jenis', 'kota', 'harga_min', 'harga_max', 'rating_min', 'kata_kunci',
        ]);

        $lapangans = $searchService->cari($kriteria);

        $daftarKota = Lapangan::tampilPublik()
            ->whereNotNull('kota')
            ->distinct()
            ->pluck('kota');

        $daftarJenis = Lapangan::tampilPublik()
            ->distinct()
            ->pluck('jenis');

        return view('lapangan.index', compact('lapangans', 'daftarKota', 'daftarJenis', 'kriteria'));
    }
}