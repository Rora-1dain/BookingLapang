<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Ulasan;
use App\Notifications\UlasanBurukDiterima;
use App\Services\UlasanService;
use Illuminate\Http\Request;
use Exception;

class UlasanController extends Controller
{
    public function store(Request $request, Booking $booking, UlasanService $ulasanService)
    {
        $booking->pastikanMilikUser(auth()->id());

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

            return back()->with('success', 'Terima kasih atas ulasan Anda.');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    // Tambahan: endpoint untuk editUlasan() di UlasanService (belum ada di soal,
    // tapi dibutuhkan supaya tombol "Edit Ulasan" di frontend Revano ada tujuannya)
    public function update(Request $request, Ulasan $ulasan, UlasanService $ulasanService)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'komentar' => 'nullable|string|max:500',
        ]);

        try {
            $ulasanService->editUlasan(
                $ulasan, auth()->id(), $validated['rating'], $validated['komentar'] ?? null
            );

            return back()->with('success', 'Ulasan berhasil diperbarui.');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    // Tambahan: endpoint untuk laporkanUlasan() — dipanggil user lain yang menandai
    // ulasan tidak pantas/spam
    public function laporkan(Ulasan $ulasan, UlasanService $ulasanService)
    {
        $ulasanService->laporkanUlasan($ulasan);

        return back()->with('success', 'Ulasan telah dilaporkan dan akan ditinjau admin.');
    }
}
