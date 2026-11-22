<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HariLibur;
use App\Models\Lapangan;
use Illuminate\Http\Request;

class PemilikHariLiburApiController extends Controller
{
    private function pastikanPemilik(Request $request, Lapangan $lapangan): void
    {
        if ($lapangan->pemilik_id !== $request->user()->id && $request->user()->role !== 'admin') {
            abort(403, 'Anda tidak berhak mengelola hari libur lapangan ini.');
        }
    }

    public function index(Request $request, Lapangan $lapangan)
    {
        $this->pastikanPemilik($request, $lapangan);

        $hariLiburs = $lapangan->hariLiburs()
            ->orderBy('tanggal')
            ->get(['id', 'lapangan_id', 'tanggal', 'keterangan']);

        return response()->json(['data' => $hariLiburs]);
    }

    public function store(Request $request, Lapangan $lapangan)
    {
        $this->pastikanPemilik($request, $lapangan);

        $data = $request->validate([
            'tanggal' => ['required', 'date', 'after_or_equal:today'],
            'keterangan' => ['nullable', 'string', 'max:255'],
        ]);

        $hariLibur = $lapangan->hariLiburs()->updateOrCreate(
            ['tanggal' => $data['tanggal']],
            ['keterangan' => $data['keterangan'] ?? null]
        );

        return response()->json([
            'message' => 'Tanggal libur berhasil ditambahkan.',
            'data' => $hariLibur,
        ], 201);
    }

    public function destroy(Request $request, Lapangan $lapangan, HariLibur $hariLibur)
    {
        $this->pastikanPemilik($request, $lapangan);
        abort_if($hariLibur->lapangan_id !== $lapangan->id, 403, 'Hari libur tidak sesuai dengan lapangan.');

        $hariLibur->delete();

        return response()->json(['message' => 'Tanggal libur berhasil dihapus.']);
    }
}
