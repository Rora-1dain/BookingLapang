<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\WaitlistService;
use Exception;
use Illuminate\Http\Request;

class WaitlistApiController extends Controller
{
    // GET /api/waitlist — daftar waitlist milik user yang sedang login.
    public function index(Request $request)
    {
        $waitlists = \App\Models\Waitlist::with('lapangan:id,nama_lapangan,jenis,alamat,kota')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get()
            ->map(fn (\App\Models\Waitlist $w) => [
                'id' => $w->id,
                'lapangan_id' => $w->lapangan_id,
                'lapangan' => $w->lapangan?->nama_lapangan,
                'jenis' => $w->lapangan?->jenis,
                'alamat' => $w->lapangan?->alamat ?? $w->lapangan?->kota,
                'tanggal_booking' => $w->tanggal_booking?->toDateString(),
                'jam_mulai' => substr((string) $w->jam_mulai, 0, 5),
                'jam_selesai' => substr((string) $w->jam_selesai, 0, 5),
                'status' => $w->status,
                'ditawarkan_pada' => $w->ditawarkan_pada?->toIso8601String(),
            ]);

        return response()->json(['data' => $waitlists]);
    }

    public function daftar(Request $request, WaitlistService $waitlistService)
    {
        $validated = $request->validate([
            'lapangan_id' => 'required|exists:lapangans,id',
            'tanggal_booking' => 'required|date|after_or_equal:today',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
        ]);

        $validated['user_id'] = $request->user()->id;

        try {
            $waitlist = $waitlistService->daftarTunggu($validated);

            return response()->json(['data' => $waitlist], 201);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }
}
