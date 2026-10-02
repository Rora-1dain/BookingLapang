<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Support\PreferensiNotifikasi;
use Illuminate\Http\Request;

class PengaturanNotifikasiApiController extends Controller
{
    public function show(Request $request)
    {
        return response()->json(['data' => $this->payload($request)]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'preferensi' => 'required|array',
            'preferensi.*' => 'array',
            'preferensi.*.*' => 'string|in:'.implode(',', PreferensiNotifikasi::CHANNEL),
        ]);

        $peta = [];
        foreach (array_keys(PreferensiNotifikasi::JENIS) as $kunci) {
            $peta[$kunci] = PreferensiNotifikasi::bersihkan($request->input("preferensi.$kunci", []));
        }

        $request->user()->preferensi_notifikasi = $peta;
        $request->user()->save();

        return response()->json(['message' => 'Preferensi notifikasi disimpan.', 'data' => $this->payload($request)]);
    }

    private function payload(Request $request): array
    {
        return [
            'jenis' => collect(PreferensiNotifikasi::JENIS)->map(fn ($j) => ['label' => $j[0], 'transaksional' => $j[2]]),
            'preferensi' => PreferensiNotifikasi::untuk($request->user()),
        ];
    }
}