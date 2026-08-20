<?php

namespace App\Services;

use App\Models\Booking;
use Midtrans\Config;
use Midtrans\Snap;
use Exception;

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

        $orderId = 'BOOKING-' . $booking->id . '-' . time();

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
}