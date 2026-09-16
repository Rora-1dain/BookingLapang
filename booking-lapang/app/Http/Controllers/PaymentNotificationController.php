<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\PaymentLog;
use App\Services\CommissionService;
use App\Services\LoyaltyService;
use App\Services\ReferralService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class PaymentNotificationController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->all();

        try {
            Log::info('Payment notification diterima', ['payload' => $payload]);

            if (! isset($payload['order_id'], $payload['status_code'], $payload['gross_amount'], $payload['signature_key'], $payload['transaction_status'])) {
                Log::error('Payment notification payload tidak lengkap', ['payload' => $payload]);

                return response()->json(['message' => 'Payload tidak lengkap.'], 400);
            }

            $signature = hash('sha512',
                $payload['order_id'].
                $payload['status_code'].
                $payload['gross_amount'].
                config('services.midtrans.server_key')
            );

            if ($signature !== $payload['signature_key']) {
                Log::warning('Signature Midtrans tidak valid', [
                    'expected' => $signature,
                    'received' => $payload['signature_key'],
                ]);

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

            $bookings = Booking::where('payment_reference', $payload['order_id'])->get();

        if ($bookings->isEmpty()) {
            Log::error('Booking tidak ditemukan untuk order_id ini', ['order_id' => $payload['order_id']]);

            return response()->json(['message' => 'Booking tidak ditemukan.'], 404);
            }

            $status = $payload['transaction_status'];

                foreach ($bookings as $booking) {
                if (in_array($status, ['settlement', 'capture'])) {
                    $booking->update([
                'status_pembayaran' => 'paid',
                'status' => 'confirmed',
        ]);

            $poinDidapat = intdiv((int) $booking->total_harga, 10000);
            app(LoyaltyService::class)->tambahPoin(
                $booking->user, $poinDidapat, "Booking #{$booking->id} berhasil dibayar"
        );

            app(ReferralService::class)->prosesRewardReferral(
                $booking, app(LoyaltyService::class)
        );

            app(CommissionService::class)->hitungKomisi($booking);
        }   elseif (in_array($status, ['expire', 'deny', 'cancel'])) {
            $booking->update(['status_pembayaran' => 'failed']);
        }
        }

        if(in_array($status, ['settlement', 'capture'])) {
            Cache::tags(['dashboard'])->flush();
        }
            return response()->json(['message' => 'Notifikasi berhasil diproses.']);

        } catch (\Throwable $e) {
            Log::error('Payment notification gagal diproses', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'payload' => $payload,
            ]);

            return response()->json(['message' => 'Terjadi kesalahan server.'], 500);
        }
    }
}