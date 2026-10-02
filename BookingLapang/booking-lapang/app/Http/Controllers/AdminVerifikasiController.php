<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\VerifikasiService;
use Illuminate\Http\Request;

class AdminVerifikasiController extends Controller
{
    public function index()
    {
       $this->authorize('verifikasi.tinjau');
 
       $menunggu = User::where('status_verifikasi', 'menunggu')->latest()->get();
 
       return view('admin.verifikasi.index', compact('menunggu'));
    }

    public function lihatDokumen(User $pemilik)
    {
    // Diganti dari: abort_unless(auth()->user()->role === 'admin', 403);
        $this->authorize('verifikasi.tinjau');
 
        return Storage::disk('local')->response($pemilik->path_dokumen_identitas);
    }  


    public function tinjau(Request $request, User $pemilik, VerifikasiService $verifikasiService)
    {
        $this->authorize('verifikasi.tinjau');
 
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
