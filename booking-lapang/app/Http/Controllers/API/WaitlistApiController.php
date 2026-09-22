<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\WaitlistService;
use Illuminate\Http\Request;
use Exception;

class WaitlistApiController extends Controller
{
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
