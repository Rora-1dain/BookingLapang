<?php

namespace App\Http\Controllers;

use App\Services\LoyaltyService;
use App\Services\VoucherService;
use Illuminate\Http\Request;
use Exception;

class LoyaltyController extends Controller
{
    public function redeem(Request $request, LoyaltyService $loyaltyService, VoucherService $voucherService)
    {
        $validated = $request->validate([
            'jumlah_poin' => 'required|integer|min:100|multiple_of:100',
        ]);

        try {
            $voucher = $loyaltyService->redeemPoin(
                auth()->user(), $validated['jumlah_poin'], $voucherService
            );

            return back()->with('success', "Voucher {$voucher->kode} berhasil dibuat.");
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
