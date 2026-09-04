<?php

namespace App\Services;

use App\Models\Booking;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Exception;

class InvoiceService
{
    public function buatNomorInvoice(): string
    {
        $prefix = 'INV/' . now()->format('Y/m') . '/';

        $urutan = DB::table('bookings')
            ->where('nomor_invoice', 'like', $prefix . '%')
            ->count() + 1;

        return $prefix . str_pad($urutan, 4, '0', STR_PAD_LEFT);
    }

    public function buatPdf(Booking $booking): \Barryvdh\DomPDF\PDF
    {
        if ($booking->status_pembayaran !== 'paid') {
            throw new Exception('Invoice hanya tersedia untuk booking yang sudah dibayar.');
        }

        if (! $booking->nomor_invoice) {
            $booking->update(['nomor_invoice' => $this->buatNomorInvoice()]);
        }

        $urlVerifikasi = route('invoice.verifikasi', $booking->nomor_invoice);

        $svg = QrCode::size(150)->generate($urlVerifikasi);
        $namaFile = 'qrcodes/' . str_replace('/', '-', $booking->nomor_invoice) . '.svg';
        Storage::disk('public')->put($namaFile, $svg);
        $qrCodePath = Storage::disk('public')->path($namaFile);

        return Pdf::loadView('invoice.booking', [
            'booking' => $booking->load('lapangan', 'user', 'voucher'),
            'subtotal' => $booking->total_harga + $booking->total_diskon,
            'qrCodePath' => $qrCodePath,
        ]);
    }
}