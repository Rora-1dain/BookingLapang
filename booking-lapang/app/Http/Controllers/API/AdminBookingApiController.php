<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Services\RefundService;
use Illuminate\Http\Request;
use Exception;

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
}
