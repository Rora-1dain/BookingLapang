<?php

namespace App\Services;

use App\Models\Booking;
use Exception;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction;

class PaymentService
{
    public function __construct()
    {
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function buatTransaksi(Booking $booking): string
    {
        if ($booking->status_pembayaran === 'paid') {
            throw new Exception('Booking ini sudah dibayar.');
        }

        $orderId = 'BOOKING-'.$booking->id.'-'.time();

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) $booking->total_harga,
            ],
            'customer_details' => [
                'first_name' => $booking->user->name,
                'email' => $booking->user->email,
            ],
        ];

        $snapToken = Snap::getSnapToken($params);

        $booking->update(['payment_reference' => $orderId]);

        return $snapToken;
    }

    public function cekStatusTransaksi(Booking $booking): array
    {
        if (! $booking->payment_reference) {
            throw new Exception('Booking ini belum memiliki transaksi pembayaran.');
        }

        $status = Transaction::status($booking->payment_reference);
        $transactionStatus = $status->transaction_status;

        if (in_array($transactionStatus, ['settlement', 'capture']) && $booking->status_pembayaran !== 'paid') {
            $booking->update(['status_pembayaran' => 'paid', 'status' => 'confirmed']);
        } elseif (in_array($transactionStatus, ['expire', 'deny', 'cancel']) && $booking->status_pembayaran !== 'failed') {
            $booking->update(['status_pembayaran' => 'failed']);
        }

        return [
            'transaction_status' => $transactionStatus,
            'status_pembayaran' => $booking->fresh()->status_pembayaran,
        ];
    }
}
