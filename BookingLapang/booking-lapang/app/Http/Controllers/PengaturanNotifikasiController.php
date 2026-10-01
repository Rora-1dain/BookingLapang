<?php

namespace App\Http\Controllers;

use App\Support\PreferensiNotifikasi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PengaturanNotifikasiController extends Controller
{
    public function edit(Request $request): View
    {
        return view('pengaturan.notifikasi', [
            'jenis' => PreferensiNotifikasi::JENIS,
            'preferensi' => PreferensiNotifikasi::untuk($request->user()),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $request->user()->preferensi_notifikasi = $this->petaDariRequest($request);
        $request->user()->save();

        return redirect()->route('pengaturan.notifikasi')->with('status', 'preferensi-disimpan');
    }

    /** Checkbox yang tidak dicentang tidak terkirim, jadi bangun peta dari daftar jenis. */
    private function petaDariRequest(Request $request): array
    {
        $peta = [];
        foreach (array_keys(PreferensiNotifikasi::JENIS) as $kunci) {
            $peta[$kunci] = PreferensiNotifikasi::bersihkan($request->input("notifikasi.$kunci", []));
        }

        return $peta;
    }
}