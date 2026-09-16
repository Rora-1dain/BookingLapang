<?php

namespace App\Http\Controllers;

use App\Models\HariLibur;
use App\Models\Lapangan;
use Illuminate\Http\Request;

class HariLiburController extends Controller
{
    public function index(Lapangan $lapangan)
    {
        $this->authorize('update', $lapangan);

        $hariLiburs = $lapangan->hariLiburs()->orderBy('tanggal')->get();

        return view('pemilik.hari-libur.index', compact('lapangan', 'hariLiburs'));
    }

    public function store(Request $request, Lapangan $lapangan)
    {
        $this->authorize('update', $lapangan);

        $data = $request->validate([
            'tanggal' => ['required', 'date', 'after_or_equal:today'],
            'keterangan' => ['nullable', 'string', 'max:255'],
        ]);

        $lapangan->hariLiburs()->updateOrCreate(
            ['tanggal' => $data['tanggal']],
            ['keterangan' => $data['keterangan'] ?? null]
        );

        return back()->with('success', 'Tanggal libur ditambahkan.');
    }

    public function destroy(Lapangan $lapangan, HariLibur $hariLibur)
    {
        $this->authorize('update', $lapangan);
        abort_if($hariLibur->lapangan_id !== $lapangan->id, 403);

        $hariLibur->delete();

        return back()->with('success', 'Tanggal libur dihapus.');
    }
}