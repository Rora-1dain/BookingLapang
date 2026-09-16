<?php

declare(strict_types=1);

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


    /**
 * Membuat transaksi pembayaran baru di Midtrans dan mengembalikan Snap Token
 * untuk ditampilkan di frontend.
 *
 * @param Booking $booking Booking yang akan dibayar
 * @return string Snap Token dari Midtrans
 * @throws \Exception Jika booking sudah berstatus 'paid'
 */

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


    /**
 * Mengecek status transaksi terkini langsung ke Midtrans (tidak hanya
 * mengandalkan webhook), lalu menyinkronkan status_pembayaran di database
 * jika ada perbedaan. Midtrans dianggap sebagai source of truth.
 *
 * @param Booking $booking Booking yang akan dicek statusnya
 * @return array{transaction_status: string, status_pembayaran: string}
 * @throws \Exception Jika booking belum memiliki payment_reference
 */

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

    /**
     * Menggabungkan total harga dari beberapa booking (anak paket booking
     * berulang) menjadi SATU transaksi Snap, satu order_id, satu popup bayar.
     * Dipakai oleh RecurringBookingController::bayar().
     *
     * @param  Booking[]  $bookings  Booking-booking anak yang berhasil dijadwalkan & belum dibayar
     * @return string Snap Token dari Midtrans
     * @throws \Exception Jika array booking kosong
     */
    public function buatTransaksiGabungan(array $bookings, int $userId): string
    {
        if (empty($bookings)) {
            throw new Exception('Tidak ada booking untuk digabungkan.');
        }

        $totalHarga = collect($bookings)->sum('total_harga');
        $recurringBookingId = $bookings[0]->recurring_booking_id;
        $orderId = 'PAKET-'.$recurringBookingId.'-'.time();

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) $totalHarga,
            ],
            'customer_details' => [
                'first_name' => $bookings[0]->user->name,
                'email' => $bookings[0]->user->email,
            ],
        ];

        $snapToken = Snap::getSnapToken($params);

        foreach ($bookings as $booking) {
            $booking->update(['payment_reference' => $orderId]);
        }

        return $snapToken;
    }
}