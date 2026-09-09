<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\VerifikasiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminVerifikasiController extends Controller
{
    public function index()
    {
        $menunggu = User::where('status_verifikasi', 'menunggu')->latest()->get();

        return view('admin.verifikasi.index', compact('menunggu'));
    }

    public function lihatDokumen(User $pemilik)
    {
        abort_unless(auth()->user()->role === 'admin', 403);

        return response('<img src="' . $pemilik->path_dokumen_identitas . '" style="max-width:100%">')
            ->header('Content-Type', 'text/html');
    }

    public function tinjau(Request $request, User $pemilik, VerifikasiService $verifikasiService)
    {
        $validated = $request->validate([
            'keputusan' => 'required|in:setuju,tolak',
            'catatan' => 'nullable|string',
        ]);

        $verifikasiService->tinjauVerifikasi(
            $pemilik, $validated['keputusan'] === 'setuju', $validated['catatan'] ?? null
        );

        return back()->with('success', 'Keputusan verifikasi tersimpan.');
    }
}