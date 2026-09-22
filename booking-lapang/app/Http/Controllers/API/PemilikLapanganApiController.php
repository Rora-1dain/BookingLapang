<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lapangan;
use Illuminate\Http\Request;

class PemilikLapanganApiController extends Controller
{
    public function index(Request $request)
    {
        $lapangans = Lapangan::where('pemilik_id', $request->user()->id)->latest()->get();

        return response()->json(['data' => $lapangans]);
    }

    public function store(Request $request)
    {
        // PENTING: pengecekan ini WAJIB ada di sini juga, bukan cuma di
        // PemilikLapanganController versi web. Soal Verifikasi Pemilik (9 Sept)
        // secara eksplisit meminta "menolak pengajuan lapangan baru jika pemilik
        // belum terverifikasi" — kalau cuma dicek di Controller web, pemilik
        // bisa lolos aturan ini dengan memakai endpoint API ini langsung.
        if ($request->user()->status_verifikasi !== 'terverifikasi') {
            return response()->json([
                'message' => 'Anda harus menyelesaikan verifikasi identitas sebelum mengajukan lapangan baru.',
            ], 403);
        }

        $validated = $request->validate([
            'nama_lapangan' => 'required|string|max:255',
            'jenis' => 'required|string',
            'harga_per_jam' => 'required|numeric|min:0',
            'kota' => 'nullable|string|max:255',
        ]);

        $lapangan = Lapangan::create([
            ...$validated,
            'pemilik_id' => $request->user()->id,
            'status_approval' => 'pending',
            'status' => 'nonaktif',
        ]);

        return response()->json([
            'message' => 'Lapangan diajukan, menunggu persetujuan admin.',
            'data' => $lapangan,
        ], 201);
    }

    public function update(Request $request, Lapangan $lapangan)
    {
        $this->authorize('update', $lapangan);

        $validated = $request->validate([
            'nama_lapangan' => 'sometimes|required|string|max:255',
            'jenis' => 'sometimes|required|string',
            'harga_per_jam' => 'sometimes|required|numeric|min:0',
            'kota' => 'nullable|string|max:255',
        ]);

        $lapangan->update($validated);

        return response()->json(['data' => $lapangan->fresh()]);
    }

    public function dashboard(Request $request)
    {
        $userId = $request->user()->id;
        $lapanganIds = Lapangan::where('pemilik_id', $userId)->pluck('id');

        $totalLapanganAktif = Lapangan::where('pemilik_id', $userId)
            ->where('status_approval', 'disetujui')->count();

        $pendapatanBulanIni = \App\Models\Booking::whereIn('lapangan_id', $lapanganIds)
            ->where('status_pembayaran', 'paid')
            ->whereMonth('tanggal_booking', now()->month)
            ->sum('total_harga');

        return response()->json([
            'total_lapangan_aktif' => $totalLapanganAktif,
            'pendapatan_bulan_ini' => (float) $pendapatanBulanIni,
        ]);
    }
}
