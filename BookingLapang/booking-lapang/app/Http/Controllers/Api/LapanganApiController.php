<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\LapanganResource;
use App\Models\Lapangan;
use App\Services\LapanganSearchService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LapanganApiController extends Controller
{
    private const HARI = [
        0 => 'Minggu',
        1 => 'Senin',
        2 => 'Selasa',
        3 => 'Rabu',
        4 => 'Kamis',
        5 => 'Jumat',
        6 => 'Sabtu',
    ];

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
        if ($lapangan->status_approval !== 'disetujui' || $lapangan->status !== 'aktif') {
            return response()->json(['message' => 'Lapangan tidak ditemukan.'], 404);
        }

        $lapangan->load(['jadwalOperasionals', 'fotos']);

        $data = (new LapanganResource($lapangan))->toArray(request());
        $data['galeri'] = $lapangan->fotos->map(fn ($foto) => [
            'id' => $foto->id,
            'url' => Storage::disk('public')->url($foto->path_file),
            'is_utama' => $foto->is_utama,
        ]);

        $data['ulasan_terbaru'] = $lapangan->ulasans()
            ->where('ulasans.disembunyikan', false)
            ->with('booking.user:id,name')
            ->latest('ulasans.created_at')
            ->limit(5)
            ->get()
            ->map(fn ($u) => [
                'id' => $u->id,
                'rating' => $u->rating,
                'komentar' => $u->komentar,
                'nama_user' => $u->booking?->user?->name ?? 'Anonim',
                'created_at' => $u->created_at->format('Y-m-d'),
            ]);

        return response()->json(['data' => $data]);
    }

    public function ulasans(Lapangan $lapangan)
    {
        $ulasans = $lapangan->ulasans()
            ->where('ulasans.disembunyikan', false)
            ->with('booking.user:id,name')
            ->latest('ulasans.created_at')
            ->paginate(15);

        return response()->json([
            'data' => collect($ulasans->items())->map(fn ($u) => [
                'id' => $u->id,
                'rating' => $u->rating,
                'komentar' => $u->komentar,
                'nama_user' => $u->booking?->user?->name ?? 'Anonim',
                'created_at' => $u->created_at->format('Y-m-d'),
            ]),
            'meta' => [
                'current_page' => $ulasans->currentPage(),
                'last_page' => $ulasans->lastPage(),
                'total' => $ulasans->total(),
            ],
        ]);
    }

    public function jadwal(Lapangan $lapangan)
    {
        $jadwals = $lapangan->jadwalOperasionals()->get()->keyBy('hari');

        $result = [];
        foreach (self::HARI as $hari => $nama) {
            $item = $jadwals->get($hari);
            $result[] = [
                'hari' => $hari,
                'nama_hari' => $nama,
                'jam_buka' => $item?->jam_buka ? substr((string) $item->jam_buka, 0, 5) : '08:00',
                'jam_tutup' => $item?->jam_tutup ? substr((string) $item->jam_tutup, 0, 5) : '22:00',
                'is_tutup' => (bool) ($item?->is_tutup ?? false),
            ];
        }

        return response()->json(['data' => $result]);
    }
}
