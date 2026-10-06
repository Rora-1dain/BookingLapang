<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lapangan;
use App\Notifications\LapanganDisetujui;
use App\Notifications\LapanganDitolak;
use App\Services\AuditService;
use App\Services\CommissionService;
use Exception;
use Illuminate\Http\Request;

class AdminLapanganApiController extends Controller
{
    public function approval()
    {
        try {
            $menunggu = Lapangan::with(['pemilik:id,name,email'])
                ->where('status_approval', 'pending')
                ->latest()
                ->get()
                ->map(fn (Lapangan $l) => [
                    'id' => $l->id,
                    'nama_lapangan' => $l->nama_lapangan,
                    'jenis' => $l->jenis,
                    'harga_per_jam' => (float) $l->harga_per_jam,
                    'alamat' => $l->alamat,
                    'no_wa' => $l->no_wa,
                    'kota' => $l->kota,
                    'status_approval' => $l->status_approval,
                    'persentase_komisi' => (float) ($l->persentase_komisi ?? 10),
                    'pemilik' => $l->pemilik ? [
                        'id' => $l->pemilik->id,
                        'name' => $l->pemilik->name,
                        'email' => $l->pemilik->email,
                    ] : null,
                ]);

            return response()->json(['data' => $menunggu]);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ], 500);
        }
    }

    public function setujui(Lapangan $lapangan)
    {
        $sebelum = $lapangan->only(['status_approval', 'status']); // [AUDIT]

        $lapangan->update(['status_approval' => 'disetujui', 'status' => 'aktif']);

        app(AuditService::class)->catat( // [AUDIT]
            'lapangan.disetujui', $lapangan, $sebelum, $lapangan->only(['status_approval', 'status'])
        );

        $lapangan->pemilik?->notify(new LapanganDisetujui($lapangan));

        return response()->json(['message' => 'Lapangan disetujui.', 'data' => $lapangan->fresh()]);
    }

    public function tolak(Request $request, Lapangan $lapangan)
    {
        $validated = $request->validate(['alasan' => 'required|string']);

        $sebelum = $lapangan->only(['status_approval', 'status']); // [AUDIT]

        $lapangan->update(['status_approval' => 'ditolak']);

        app(AuditService::class)->catat('lapangan.ditolak', $lapangan, $sebelum, [ // [AUDIT]
            'status_approval' => 'ditolak',
            'alasan' => $validated['alasan'],
        ]);

        $lapangan->pemilik?->notify(new LapanganDitolak($lapangan, $validated['alasan']));

        return response()->json(['message' => 'Lapangan ditolak.', 'data' => $lapangan->fresh()]);
    }

    // PUT /api/admin/lapangan/{lapangan}/komisi — logika & audit-nya ada di
    // CommissionService::ubahPersentaseKomisi().
    public function ubahKomisi(Request $request, Lapangan $lapangan, CommissionService $commissionService)
    {
        $validated = $request->validate(['persentase_komisi' => 'required|numeric|min:0|max:100']);

        try {
            $lapangan = $commissionService->ubahPersentaseKomisi($lapangan, (float) $validated['persentase_komisi']);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Persentase komisi diperbarui.', 'data' => $lapangan]);
    }
}