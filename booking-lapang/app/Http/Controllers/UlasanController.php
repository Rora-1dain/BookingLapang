<?php
// app/Http/Controllers/UlasanController.php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Notifications\UlasanBurukDiterima;
use Illuminate\Http\Request;
use Exception;

class UlasanController extends Controller
{
    public function store(Request $request, Booking $booking)
    {
        $booking->pastikanMilikUser(auth()->id());

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'komentar' => 'nullable|string|max:500',
        ]);

        try {
            // TODO(merge Ardan): ganti baris ini
            // dari: $ulasan = $booking->ulasan()->create([...]);
            // jadi: $ulasan = app(\App\Services\UlasanService::class)->buatUlasan(
            //           $booking, $validated['rating'], $validated['komentar'] ?? null
            //       );
            $ulasan = $booking->ulasan()->create([
                'rating' => $validated['rating'],
                'komentar' => $validated['komentar'] ?? null,
            ]);

            if ($ulasan->rating <= 2) {
                $admin = \App\Models\User::where('role', 'admin')->first();
                $admin?->notify(new UlasanBurukDiterima($ulasan));
            }

            return back()->with('success', 'Terima kasih atas ulasan Anda.');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}