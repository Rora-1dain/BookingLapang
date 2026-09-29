<?php

namespace App\Http\Controllers;

use App\Models\Lapangan;
use App\Notifications\LapanganDisetujui;
use App\Notifications\LapanganDitolak;
use App\Services\AuditService;
use Illuminate\Http\Request;

class AdminLapanganController extends Controller
{
    public function approval()
    {
        $menunggu = Lapangan::with('pemilik')
            ->where('status_approval', 'pending')
            ->latest()
            ->get();

        return view('admin.lapangan.approval', compact('menunggu'));
    }

    public function setujui(Lapangan $lapangan)
    {
        $this->authorize('update', $lapangan);

        $sebelum = $lapangan->only(['status_approval', 'status']); // [AUDIT]

        $lapangan->update([
            'status_approval' => 'disetujui',
            'status' => 'aktif',
        ]);

        app(AuditService::class)->catat( // [AUDIT]
            'lapangan.disetujui', $lapangan, $sebelum, $lapangan->only(['status_approval', 'status'])
        );

        $lapangan->pemilik->notify(new LapanganDisetujui($lapangan));

        return back()->with('success', 'Lapangan disetujui.');
    }

    public function tolak(Request $request, Lapangan $lapangan)
    {
        $this->authorize('update', $lapangan);

        $validated = $request->validate([
            'alasan' => 'required|string',
        ]);

        $sebelum = $lapangan->only(['status_approval', 'status']); // [AUDIT]

        $lapangan->update(['status_approval' => 'ditolak']);

        app(AuditService::class)->catat('lapangan.ditolak', $lapangan, $sebelum, [ // [AUDIT]
            'status_approval' => 'ditolak',
            'alasan' => $validated['alasan'],
        ]);

        $lapangan->pemilik->notify(new LapanganDitolak($lapangan, $validated['alasan']));

        return back()->with('success', 'Lapangan ditolak.');
    }
}
