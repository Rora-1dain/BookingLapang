<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\VerifikasiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminVerifikasiApiController extends Controller
{
    public function index()
    {
        $menunggu = User::where('status_verifikasi', 'menunggu')->latest()->get();

        return response()->json(['data' => $menunggu]);
    }

    public function lihatDokumen(User $pemilik)
    {
        if (! $pemilik->path_dokumen_identitas || ! Storage::disk('local')->exists($pemilik->path_dokumen_identitas)) {
            return response()->json(['message' => 'Dokumen tidak ditemukan.'], 404);
        }

        return Storage::disk('local')->response($pemilik->path_dokumen_identitas);
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

        return response()->json(['message' => 'Keputusan verifikasi tersimpan.']);
    }
}
