<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;

class PaymentNotificationController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->all();

        $signature = hash('sha512',
            $payload['order_id'] .
            $payload['status_code'] .
            $payload['gross_amount'] .
            config('services.midtrans.server_key')
        );

        if ($signature !== $payload['signature_key']) {
            return response()->json(['message' => 'Signature tidak valid.'], 403);
        }

        $booking = Booking::where('payment_reference', $payload['order_id'])->firstOrFail();

        $status = $payload['transaction_status'];

        if (in_array($status, ['settlement', 'capture'])) {
            $booking->update([
                'status_pembayaran' => 'paid',
                'status' => 'confirmed',
            ]);
        } elseif (in_array($status, ['expire', 'deny', 'cancel'])) {
            $booking->update(['status_pembayaran' => 'failed']);
        }

        return response()->json(['message' => 'Notifikasi berhasil diproses.']);
    }
}