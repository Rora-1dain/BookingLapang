<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\PaymentLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PaymentNotificationController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->all();

        $signature = hash('sha512',
            $payload['order_id'].
            $payload['status_code'].
            $payload['gross_amount'].
            config('services.midtrans.server_key')
        );

        if ($signature !== $payload['signature_key']) {
            return response()->json(['message' => 'Signature tidak valid.'], 403);
        }

        $sudahDiproses = PaymentLog::where('order_id', $payload['order_id'])
            ->where('transaction_status', $payload['transaction_status'])
            ->exists();

        if ($sudahDiproses) {
            return response()->json(['message' => 'Notifikasi sudah pernah diproses, diabaikan.']);
        }

        PaymentLog::create([
            'order_id' => $payload['order_id'],
            'transaction_status' => $payload['transaction_status'],
            'payload' => $payload,
            'diterima_pada' => now(),
        ]);

        $booking = Booking::where('payment_reference', $payload['order_id'])->firstOrFail();

        $status = $payload['transaction_status'];

        if (in_array($status, ['settlement', 'capture'])) {
            $booking->update([
                'status_pembayaran' => 'paid',
                'status' => 'confirmed',
            ]);

            Cache::forget('dashboard.total_pendapatan');
            Cache::forget('dashboard.lapangan_favorit.3');
        } elseif (in_array($status, ['expire', 'deny', 'cancel'])) {
            $booking->update(['status_pembayaran' => 'failed']);
        }

        return response()->json(['message' => 'Notifikasi berhasil diproses.']);
    }
}