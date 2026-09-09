<?php

namespace App\Http\Controllers;

use App\Services\VerifikasiService;
use Illuminate\Http\Request;
use Exception;

class PemilikVerifikasiController extends Controller
{
    public function create()
    {
        return view('pemilik.verifikasi.create');
    }

    public function store(Request $request, VerifikasiService $verifikasiService)
    {
        $validated = $request->validate([
            'dokumen_identitas' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        try {
            $verifikasiService->ajukanVerifikasi(auth()->user(), $request->file('dokumen_identitas'));

            return back()->with('success', 'Dokumen verifikasi berhasil diajukan, menunggu peninjauan admin.');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}