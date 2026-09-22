<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\VoucherService;
use Illuminate\Http\Request;
use Exception;

class VoucherApiController extends Controller
{
    public function cek(Request $request, VoucherService $voucherService)
    {
        $validated = $request->validate([
            'kode' => 'required|string',
            'total_harga' => 'required|numeric|min:0',
        ]);

        try {
            $voucher = $voucherService->validasiVoucher(
                $validated['kode'], $request->user()->id, $validated['total_harga']
            );

            $diskon = $voucherService->hitungDiskon($voucher, $validated['total_harga']);

            return response()->json([
                'valid' => true,
                'kode' => $voucher->kode,
                'jenis_diskon' => $voucher->jenis_diskon,
                'nilai' => (float) $voucher->nilai,
                'nominal_diskon' => $diskon,
                'total_setelah_diskon' => $validated['total_harga'] - $diskon,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'valid' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }
}
