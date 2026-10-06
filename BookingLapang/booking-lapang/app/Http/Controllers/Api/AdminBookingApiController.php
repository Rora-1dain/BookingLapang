<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Services\RefundService;
use Exception;
use Illuminate\Http\Request;

class AdminBookingApiController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['lapangan', 'user']);

        if ($request->filled('status_pembayaran')) {
            $query->where('status_pembayaran', $request->query('status_pembayaran'));
        }

        return response()->json($query->latest()->paginate(20));
    }

    /**
     * Daftar pengajuan refund (semua status kecuali 'belum_refund').
     * Meniru AdminBookingController::refundIndex() versi web.
     */
    public function refundIndex(Request $request)
    {
        $baseQuery = Booking::where('status_refund', '!=', 'belum_refund');

        $counts = [
            'semua' => (clone $baseQuery)->count(),
            'diminta' => (clone $baseQuery)->where('status_refund', 'diminta')->count(),
            'diproses' => (clone $baseQuery)->where('status_refund', 'diproses')->count(),
            'selesai' => (clone $baseQuery)->where('status_refund', 'selesai')->count(),
            'ditolak' => (clone $baseQuery)->where('status_refund', 'ditolak')->count(),
        ];

        $query = Booking::with(['lapangan', 'user'])
            ->where('status_refund', '!=', 'belum_refund');

        if ($request->filled('status')) {
            $query->where('status_refund', $request->query('status'));
        }

        return response()->json([
            'data' => $query->latest()->paginate(20),
            'counts' => $counts,
        ]);
    }

    public function refund(Request $request, Booking $booking, RefundService $refundService)
    {
        $validated = $request->validate(['alasan' => 'required|string|max:255']);

        try {
            $booking = $refundService->ajukanRefund($booking, $validated['alasan'], $request->user()->id);

            return response()->json(['data' => $booking]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    /**
     * Admin menolak pengajuan refund user (tanpa memanggil Midtrans).
     */
    public function tolakRefund(Request $request, Booking $booking, RefundService $refundService)
    {
        $validated = $request->validate(['catatan' => 'nullable|string|max:255']);

        try {
            $booking = $refundService->tolakPermintaanRefund($booking, $validated['catatan'] ?? null, $request->user()->id);

            return response()->json(['data' => $booking]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }
}
