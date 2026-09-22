<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lapangan;
use App\Notifications\LapanganDisetujui;
use App\Notifications\LapanganDitolak;
use Illuminate\Http\Request;

class AdminLapanganApiController extends Controller
{
    public function approval()
    {
        $menunggu = Lapangan::with('pemilik')
            ->where('status_approval', 'pending')
            ->latest()
            ->get();

        return response()->json(['data' => $menunggu]);
    }

    public function setujui(Lapangan $lapangan)
    {
        $lapangan->update(['status_approval' => 'disetujui', 'status' => 'aktif']);
        $lapangan->pemilik?->notify(new LapanganDisetujui($lapangan));

        return response()->json(['message' => 'Lapangan disetujui.', 'data' => $lapangan->fresh()]);
    }

    public function tolak(Request $request, Lapangan $lapangan)
    {
        $validated = $request->validate(['alasan' => 'required|string']);

        $lapangan->update(['status_approval' => 'ditolak']);
        $lapangan->pemilik?->notify(new LapanganDitolak($lapangan, $validated['alasan']));

        return response()->json(['message' => 'Lapangan ditolak.', 'data' => $lapangan->fresh()]);
    }
}
