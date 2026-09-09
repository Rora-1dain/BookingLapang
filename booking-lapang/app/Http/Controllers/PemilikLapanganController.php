<?php

namespace App\Http\Controllers;

use App\Models\Lapangan;
use Illuminate\Http\Request;

class PemilikLapanganController extends Controller
{
    public function index()
    {
        $lapangans = Lapangan::where('pemilik_id', auth()->id())->latest()->get();

        return view('pemilik.lapangan.index', compact('lapangans'));
    }

    public function create()
    {
        return view('pemilik.lapangan.create');
    }

    public function store(Request $request)
    {
        if (auth()->user()->status_verifikasi !== 'terverifikasi') {
            return back()->with('error', 'Anda harus terverifikasi terlebih dahulu sebelum mengajukan lapangan.');
        }

        $validated = $request->validate([
            'nama_lapangan' => 'required|string|max:255',
            'jenis' => 'required|string',
            'harga_per_jam' => 'required|numeric|min:0',
        ]);

        Lapangan::create([
            ...$validated,
            'pemilik_id' => auth()->id(),
            'status_approval' => 'pending',
            'status' => 'nonaktif',
        ]);

        return redirect()->route('pemilik.lapangan.index')
            ->with('success', 'Lapangan diajukan, menunggu persetujuan admin.');
    }

    public function edit(Lapangan $lapangan)
    {
        if ($lapangan->pemilik_id !== auth()->id()) {
            abort(403, 'Anda tidak berhak mengedit lapangan ini.');
        }

        return view('pemilik.lapangan.edit', compact('lapangan'));
    }

    public function update(Request $request, Lapangan $lapangan)
    {
        if ($lapangan->pemilik_id !== auth()->id()) {
            abort(403, 'Anda tidak berhak mengubah lapangan ini.');
        }

        $validated = $request->validate([
            'nama_lapangan' => 'required|string|max:255',
            'jenis' => 'required|string',
            'harga_per_jam' => 'required|numeric|min:0',
        ]);

        $lapangan->update($validated);

        return redirect()->route('pemilik.lapangan.index')
            ->with('success', 'Lapangan berhasil diperbarui.');
    }

     public function dashboard()
{
    $lapanganIds = Lapangan::where('pemilik_id', auth()->id())->pluck('id');

    $totalLapanganAktif = Lapangan::where('pemilik_id', auth()->id())
        ->where('status_approval', 'disetujui')
        ->count();

    $jumlahBookingBulanIni = \App\Models\Booking::whereIn('lapangan_id', $lapanganIds)
        ->whereMonth('tanggal_booking', now()->month)
        ->count();

    $pendapatanBulanIni = \App\Models\Booking::whereIn('lapangan_id', $lapanganIds)
        ->where('status_pembayaran', 'paid')
        ->whereMonth('tanggal_booking', now()->month)
        ->sum('total_harga');

    return view('pemilik.dashboard', compact(
        'totalLapanganAktif',
        'jumlahBookingBulanIni',
        'pendapatanBulanIni'
    ));
}

}