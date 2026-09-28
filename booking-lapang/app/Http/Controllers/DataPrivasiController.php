<?php

namespace App\Http\Controllers;

use App\Services\DataPrivasiService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DataPrivasiController extends Controller
{
    public function __construct(protected DataPrivasiService $service) {}

    // GET /privasi/ekspor  (dibatasi 3x/hari lewat limiter 'ekspor-data')
    public function ekspor(Request $request)
    {
        $data = $this->service->eksporData($request->user());

        $namaFile = 'data-pribadi-' . $request->user()->id . '-' . now()->format('Ymd-His') . '.json';

        return response()->streamDownload(function () use ($data) {
            echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }, $namaFile, ['Content-Type' => 'application/json']);
    }

    // DELETE /privasi/hapus-akun
    public function hapusAkun(Request $request)
    {
        $request->validate(['password' => ['required', 'string']]);

        try {
            $this->service->ajukanHapusAkun($request->user(), $request->input('password'));
        } catch (Exception $e) {
            // Akun tidak berubah jika ditolak
            return response()->json(['message' => $e->getMessage()], 422);
        }

        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['message' => 'Akun berhasil dihapus.']);
    }
}   