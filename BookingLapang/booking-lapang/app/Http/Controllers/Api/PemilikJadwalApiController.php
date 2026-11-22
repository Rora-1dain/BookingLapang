<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lapangan;
use Illuminate\Http\Request;

class PemilikJadwalApiController extends Controller
{
    private const HARI = [
        0 => 'Minggu',
        1 => 'Senin',
        2 => 'Selasa',
        3 => 'Rabu',
        4 => 'Kamis',
        5 => 'Jumat',
        6 => 'Sabtu',
    ];

    private function pastikanPemilik(Request $request, Lapangan $lapangan): void
    {
        if ($lapangan->pemilik_id !== $request->user()->id && $request->user()->role !== 'admin') {
            abort(403, 'Anda tidak berhak mengelola jadwal lapangan ini.');
        }
    }

    public function show(Request $request, Lapangan $lapangan)
    {
        $this->pastikanPemilik($request, $lapangan);

        $jadwals = $lapangan->jadwalOperasionals()->get()->keyBy('hari');

        $result = [];
        foreach (self::HARI as $hari => $nama) {
            $item = $jadwals->get($hari);
            $result[] = [
                'hari' => $hari,
                'nama_hari' => $nama,
                'jam_buka' => $item?->jam_buka ? substr((string) $item->jam_buka, 0, 5) : '08:00',
                'jam_tutup' => $item?->jam_tutup ? substr((string) $item->jam_tutup, 0, 5) : '22:00',
                'is_tutup' => (bool) ($item?->is_tutup ?? false),
            ];
        }

        return response()->json(['data' => $result]);
    }

    public function update(Request $request, Lapangan $lapangan)
    {
        $this->pastikanPemilik($request, $lapangan);

        $request->validate([
            'jadwal' => ['required', 'array'],
            'jadwal.*.hari' => ['required', 'integer', 'between:0,6'],
            'jadwal.*.jam_buka' => ['nullable'],
            'jadwal.*.jam_tutup' => ['nullable'],
            'jadwal.*.is_tutup' => ['nullable', 'boolean'],
        ]);

        foreach ($request->input('jadwal', []) as $data) {
            $hari = (int) $data['hari'];
            $lapangan->jadwalOperasionals()->updateOrCreate(
                ['hari' => $hari],
                [
                    'jam_buka' => ! empty($data['jam_buka']) ? $data['jam_buka'] : null,
                    'jam_tutup' => ! empty($data['jam_tutup']) ? $data['jam_tutup'] : null,
                    'is_tutup' => (bool) ($data['is_tutup'] ?? false),
                ]
            );
        }

        return response()->json(['message' => 'Jadwal operasional berhasil diperbarui.']);
    }
}
