<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\VerifikasiService;
use Exception;
use Illuminate\Http\Request;

class VerifikasiApiController extends Controller
{
    public function ajukan(Request $request, VerifikasiService $verifikasiService)
    {
        $validated = $request->validate([
            'dokumen' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        try {
            $verifikasiService->ajukanVerifikasi($request->user(), $validated['dokumen']);

            return response()->json([
                'message' => 'Dokumen verifikasi berhasil diunggah, menunggu peninjauan admin.',
            ], 201);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }
}
