<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LanggananUser;
use App\Models\MembershipPaket;
use App\Models\MembershipTransaction;
use App\Services\MembershipService;
use App\Services\PaymentService;
use Exception;
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

    /**
     * POST /api/membership/berlangganan { membership_paket_id }
     * Membuat transaksi Midtrans & mengembalikan snap_token. Membership BARU
     * aktif setelah pembayaran settlement (webhook / cek-status).
     */
    public function berlangganan(Request $request, PaymentService $paymentService)
    {
        $validated = $request->validate([
            'membership_paket_id' => 'required|integer|exists:membership_pakets,id',
        ]);

        $paket = MembershipPaket::findOrFail($validated['membership_paket_id']);

        try {
            $hasil = $paymentService->buatTransaksiMembership($request->user(), $paket);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json([
            'snap_token' => $hasil['snap_token'],
            'client_key' => config('services.midtrans.client_key'),
            'is_production' => (bool) config('services.midtrans.is_production'),
            'order_id' => $hasil['trx']->order_id,
            'transaction_id' => $hasil['trx']->id,
        ], 201);
    }

    /**
     * POST /api/membership/cek-status/{trx}
     * Cek status langsung ke Midtrans sebagai fallback webhook, lalu sinkronkan.
     */
    public function cekStatus(Request $request, MembershipTransaction $trx, PaymentService $paymentService)
    {
        if ($trx->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Tidak berhak melihat transaksi ini.'], 403);
        }

        try {
            $hasil = $paymentService->cekStatusTransaksiMembership($trx);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        $langganan = app(MembershipService::class)->langgananAktif($request->user()->id);

        return response()->json([
            ...$hasil,
            'data' => $langganan ? $this->formatLangganan($langganan) : null,
        ]);
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
