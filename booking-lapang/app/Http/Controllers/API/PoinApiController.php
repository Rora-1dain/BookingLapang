<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\LoyaltyService;
use App\Services\VoucherService;
use Illuminate\Http\Request;
use Exception;

class PoinApiController extends Controller
{
    public function index(Request $request, LoyaltyService $loyaltyService)
    {
        $user = $request->user();

        $riwayat = $user->poinHistories()->latest()->paginate(15);

        return response()->json([
            'poin_saat_ini' => $user->poin,
            'tier' => $loyaltyService->tentukanTier($user),
            'riwayat' => $riwayat,
        ]);
    }

    public function redeem(Request $request, LoyaltyService $loyaltyService, VoucherService $voucherService)
    {
        $validated = $request->validate([
            'jumlah_poin' => 'required|integer|min:100|multiple_of:100',
        ]);

        try {
            $voucher = $loyaltyService->redeemPoin(
                $request->user(), $validated['jumlah_poin'], $voucherService
            );

            return response()->json([
                'message' => "Voucher {$voucher->kode} berhasil dibuat.",
                'voucher' => [
                    'kode' => $voucher->kode,
                    'nilai' => (float) $voucher->nilai,
                    'berlaku_sampai' => $voucher->berlaku_sampai,
                ],
                'sisa_poin' => $request->user()->fresh()->poin,
            ], 201);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }
}
