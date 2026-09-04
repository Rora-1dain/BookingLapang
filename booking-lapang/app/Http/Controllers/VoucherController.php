<?php

namespace App\Http\Controllers;

use App\Services\VoucherService;
use Illuminate\Http\Request;
use Exception;

class VoucherController extends Controller
{
    public function cek(Request $request, VoucherService $voucherService)
    {
        $request->validate([
            'kode_voucher' => 'required|string',
            'total_harga' => 'nullable|numeric',
        ]);

        try {
            $voucher = $voucherService->validasiVoucher(
                $request->kode_voucher,
                auth()->id(),
                $request->total_harga ?? 0
            );

            return response()->json([
                'valid' => true,
                'jenis_diskon' => $voucher->jenis_diskon,
                'nilai' => $voucher->nilai,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'valid' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }
}