<?php
// app/Http/Controllers/PoinController.php

namespace App\Http\Controllers;

use App\Services\LoyaltyService;
use App\Services\VoucherService;
use Illuminate\Http\Request;
use Exception;

class PoinController extends Controller
{
    public function redeem(Request $request, LoyaltyService $loyaltyService, VoucherService $voucherService)
    {
        $request->validate([
            'jumlah_poin' => 'required|integer|min:100',
        ]);

        try {
            $voucher = $loyaltyService->redeemPoin(auth()->user(), $request->jumlah_poin, $voucherService);
            return back()->with('success', "Berhasil! Voucher kode {$voucher->kode} sudah bisa dipakai.");
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}