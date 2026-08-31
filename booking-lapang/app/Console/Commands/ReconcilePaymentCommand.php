<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Services\PaymentService;
use Illuminate\Console\Command;

class ReconcilePaymentCommand extends Command
{
    protected $signature = 'booking:reconcile-payment';

    protected $description = 'Cek ulang status booking unpaid yang lebih dari 1 jam';

    public function handle(PaymentService $paymentService): void
    {
        $bookings = Booking::where('status_pembayaran', 'unpaid')
            ->whereNotNull('payment_reference')
            ->where('created_at', '<=', now()->subHour())
            ->get();

        foreach ($bookings as $booking) {
            $paymentService->cekStatusTransaksi($booking);
            $this->info("Booking #{$booking->id} dicek ulang.");
        }
    }
}
