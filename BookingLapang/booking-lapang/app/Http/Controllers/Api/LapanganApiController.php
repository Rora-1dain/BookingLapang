<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\LapanganResource;
use App\Models\Lapangan;
use App\Services\LapanganSearchService;
use Illuminate\Http\Request;

class LapanganApiController extends Controller
{
    public function index(Request $request, LapanganSearchService $searchService)
    {
        $kriteria = $request->only([
            'jenis', 'kota', 'harga_min', 'harga_max', 'rating_min', 'kata_kunci',
        ]);

        $lapangans = $searchService->cari($kriteria);

        return LapanganResource::collection($lapangans);
    }

    public function show(Lapangan $lapangan)
    {
        // findOrFail lewat route model binding sudah otomatis 404 kalau tidak ada,
        // tapi lapangan yang belum disetujui/tidak aktif tidak boleh dibuka lewat API publik
        if ($lapangan->status_approval !== 'disetujui' || $lapangan->status !== 'aktif') {
            return response()->json(['message' => 'Lapangan tidak ditemukan.'], 404);
        }

        $lapangan->load('jadwalOperasionals', 'fotos');

        $data = (new LapanganResource($lapangan))->toArray(request());
        $data['galeri'] = $lapangan->fotos->map(fn ($foto) => [
            'id' => $foto->id,
            'url' => asset('storage/'.$foto->path_file),
            'is_utama' => $foto->is_utama,
        ]);

        return response()->json(['data' => $data]);
    }
}
