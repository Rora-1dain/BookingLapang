<?php

namespace App\Http\Controllers;

use App\Models\FotoLapangan;
use App\Models\Lapangan;
use App\Services\FotoLapanganService;
use Illuminate\Http\Request;
use Exception;

class FotoLapanganController extends Controller
{
    public function index(Lapangan $lapangan)
    {
        if (auth()->id() !== $lapangan->pemilik_id && auth()->user()->role !== 'admin') {
            abort(403, 'Anda tidak berhak melihat galeri lapangan ini.');
        }

        return view('lapangan.galeri', compact('lapangan'));
    }

    public function store(Request $request, Lapangan $lapangan, FotoLapanganService $fotoService)
    {
        // Hanya pemilik lapangan itu sendiri atau admin yang boleh unggah foto
        if (auth()->id() !== $lapangan->pemilik_id && auth()->user()->role !== 'admin') {
            abort(403, 'Anda tidak berhak mengelola foto lapangan ini.');
        }

        $validated = $request->validate([
            'foto' => 'required|array',
            'foto.*' => 'file|max:2048', // validasi tambahan di level Request; validasi detail tetap di Service
        ]);

        try {
            $fotoService->unggahFoto($lapangan, $request->file('foto'));

            return back()->with('success', 'Foto berhasil diunggah.');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy(FotoLapangan $foto, FotoLapanganService $fotoService)
    {
        $lapangan = $foto->lapangan;

        if (auth()->id() !== $lapangan->pemilik_id && auth()->user()->role !== 'admin') {
            abort(403);
        }

        $fotoService->hapusFoto($foto);

        return back()->with('success', 'Foto berhasil dihapus.');
    }

    public function jadikanUtama(FotoLapangan $foto, FotoLapanganService $fotoService)
    {
        $lapangan = $foto->lapangan;

        if (auth()->id() !== $lapangan->pemilik_id && auth()->user()->role !== 'admin') {
            abort(403);
        }

        $fotoService->jadikanUtama($foto);

        return back()->with('success', 'Foto utama diperbarui.');
    }
}