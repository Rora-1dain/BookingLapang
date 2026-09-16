<?php

namespace App\Http\Controllers;

use App\Models\Lapangan;
use Illuminate\Http\Request;

class JadwalOperasionalController extends Controller
{
    public function edit(Lapangan $lapangan)
    {
        $this->authorize('update', $lapangan);

        $jadwal = $lapangan->jadwalOperasionals()->orderBy('hari')->get()->keyBy('hari');

        return view('pemilik.jadwal.edit', compact('lapangan', 'jadwal'));
    }

  public function update(Request $request, Lapangan $lapangan)
{
    $this->authorize('update', $lapangan);

    $request->validate([
        'jadwal' => ['required', 'array'],
        'jadwal.*.jam_buka' => ['nullable', 'date_format:H:i,H:i:s'],
        'jadwal.*.jam_tutup' => ['nullable', 'date_format:H:i,H:i:s'],
    ]);

    foreach ($request->input('jadwal', []) as $hari => $data) {
        $lapangan->jadwalOperasionals()->updateOrCreate(
            ['hari' => $hari],
            [
                'jam_buka' => $data['jam_buka'] ?? null,
                'jam_tutup' => $data['jam_tutup'] ?? null,
                'is_tutup' => (bool) ($data['is_tutup'] ?? false),
            ]
        );
    }

    return back()->with('success', 'Jadwal operasional diperbarui.');
}
}