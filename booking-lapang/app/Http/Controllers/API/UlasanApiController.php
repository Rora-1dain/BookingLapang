<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Ulasan;
use App\Notifications\UlasanBurukDiterima;
use App\Services\UlasanService;
use Illuminate\Http\Request;
use Exception;

class UlasanApiController extends Controller
{
    public function store(Request $request, Booking $booking, UlasanService $ulasanService)
    {
        if ($booking->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Booking ini bukan milik Anda.'], 403);
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'komentar' => 'nullable|string|max:500',
        ]);

        try {
            $ulasan = $ulasanService->buatUlasan(
                $booking, $validated['rating'], $validated['komentar'] ?? null
            );

            if ($ulasan->rating <= 2) {
                $admin = \App\Models\User::where('role', 'admin')->first();
                $admin?->notify(new UlasanBurukDiterima($ulasan));
            }

            return response()->json(['data' => $ulasan], 201);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    public function update(Request $request, Ulasan $ulasan, UlasanService $ulasanService)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'komentar' => 'nullable|string|max:500',
        ]);

        try {
            $ulasan = $ulasanService->editUlasan(
                $ulasan, $request->user()->id, $validated['rating'], $validated['komentar'] ?? null
            );

            return response()->json(['data' => $ulasan]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    public function laporkan(Ulasan $ulasan, UlasanService $ulasanService)
    {
        $ulasanService->laporkanUlasan($ulasan);

        return response()->json(['message' => 'Ulasan telah dilaporkan dan akan ditinjau admin.']);
    }
}
