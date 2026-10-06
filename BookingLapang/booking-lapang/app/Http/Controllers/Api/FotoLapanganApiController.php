<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FotoLapangan;
use App\Models\Lapangan;
use App\Services\FotoLapanganService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FotoLapanganApiController extends Controller
{
    public function index(Request $request, Lapangan $lapangan)
    {
        $fotos = $lapangan->fotos()->get()->map(fn (FotoLapangan $f) => [
            'id' => $f->id,
            'lapangan_id' => $f->lapangan_id,
            'url' => Storage::disk('public')->url($f->path_file),
            'urutan' => $f->urutan,
            'is_utama' => (bool) $f->is_utama,
        ]);

        return response()->json(['data' => $fotos]);
    }

    public function store(Request $request, Lapangan $lapangan, FotoLapanganService $fotoService)
    {
        if ($request->user()->id !== $lapangan->pemilik_id && $request->user()->role !== 'admin') {
            return response()->json(['message' => 'Anda tidak berhak mengelola foto lapangan ini.'], 403);
        }

        $request->validate([
            'foto' => 'required',
        ]);

        // Tangani baik array file maupun single file
        $files = $request->file('foto');
        if (! is_array($files)) {
            $files = [$files];
        }

        try {
            $fotoService->unggahFoto($lapangan, $files);

            $fotos = $lapangan->fresh()->fotos()->get()->map(fn (FotoLapangan $f) => [
                'id' => $f->id,
                'lapangan_id' => $f->lapangan_id,
                'url' => Storage::disk('public')->url($f->path_file),
                'urutan' => $f->urutan,
                'is_utama' => (bool) $f->is_utama,
            ]);

            return response()->json([
                'message' => 'Foto berhasil diunggah.',
                'data' => $fotos,
            ], 201);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    public function destroy(Request $request, FotoLapangan $foto, FotoLapanganService $fotoService)
    {
        $lapangan = $foto->lapangan;

        if ($request->user()->id !== $lapangan->pemilik_id && $request->user()->role !== 'admin') {
            return response()->json(['message' => 'Anda tidak berhak menghapus foto ini.'], 403);
        }

        $fotoService->hapusFoto($foto);

        return response()->json(['message' => 'Foto berhasil dihapus.']);
    }

    public function jadikanUtama(Request $request, FotoLapangan $foto, FotoLapanganService $fotoService)
    {
        $lapangan = $foto->lapangan;

        if ($request->user()->id !== $lapangan->pemilik_id && $request->user()->role !== 'admin') {
            return response()->json(['message' => 'Anda tidak berhak mengubah foto ini.'], 403);
        }

        $fotoService->jadikanUtama($foto);

        return response()->json(['message' => 'Foto utama diperbarui.']);
    }
}
