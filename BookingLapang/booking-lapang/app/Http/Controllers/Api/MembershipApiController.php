<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LanggananUser;
use App\Models\MembershipPaket;
use App\Services\MembershipService;
use Illuminate\Http\Request;

class MembershipApiController extends Controller
{
    // GET /api/membership/paket (publik)
    public function paket()
    {
        $data = MembershipPaket::orderBy('harga_bulanan')->get()
            ->map(fn (MembershipPaket $p) => $this->formatPaket($p));

        return response()->json(['data' => $data]);
    }

    // GET /api/membership — langganan aktif milik user (null kalau belum ada)
    public function saya(Request $request, MembershipService $service)
    {
        $langganan = $service->langgananAktif($request->user()->id);

        return response()->json(['data' => $langganan ? $this->formatLangganan($langganan) : null]);
    }

    // POST /api/membership/berlangganan { membership_paket_id }
    public function berlangganan(Request $request, MembershipService $service)
    {
        $validated = $request->validate([
            'membership_paket_id' => 'required|integer|exists:membership_pakets,id',
        ]);

        $paket = MembershipPaket::findOrFail($validated['membership_paket_id']);
        $langganan = $service->berlangganan($request->user(), $paket);

        return response()->json([
            'message' => "Membership {$paket->nama} aktif sampai {$langganan->tanggal_berakhir->toDateString()}.",
            'data' => $this->formatLangganan($langganan),
        ], 201);
    }

    private function formatPaket(MembershipPaket $p): array
    {
        return [
            'id' => $p->id,
            'nama' => $p->nama,
            'harga_bulanan' => (float) $p->harga_bulanan,
            'diskon' => (float) $p->persentase_diskon_booking,
        ];
    }

    private function formatLangganan(LanggananUser $l): array
    {
        return [
            'id' => $l->id,
            'paket' => $this->formatPaket($l->paket),
            'tanggal_mulai' => $l->tanggal_mulai->toDateString(),
            'tanggal_berakhir' => $l->tanggal_berakhir->toDateString(),
            'sisa_hari' => max(0, (int) now()->startOfDay()->diffInDays($l->tanggal_berakhir->copy()->startOfDay(), false)),
        ];
    }
}