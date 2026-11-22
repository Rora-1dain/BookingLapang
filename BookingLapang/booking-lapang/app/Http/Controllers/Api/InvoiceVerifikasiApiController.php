<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;

class InvoiceVerifikasiApiController extends Controller
{
    public function show(string $nomor)
    {
        $booking = Booking::with(['lapangan:id,nama_lapangan,jenis,kota', 'user:id,name'])
            ->where('nomor_invoice', $nomor)
            ->first();

        if (! $booking) {
            return response()->json([
                'valid' => false,
                'message' => 'Invoice tidak ditemukan atau tidak valid.',
            ], 404);
        }

        return response()->json([
            'valid' => true,
            'nomor_invoice' => $booking->nomor_invoice,
            'payment_reference' => $booking->payment_reference,
            'lapangan' => $booking->lapangan?->nama_lapangan,
            'pemesan' => $booking->user?->name,
            'tanggal_booking' => $booking->tanggal_booking->format('Y-m-d'),
            'jam' => $booking->jam_mulai.' - '.$booking->jam_selesai,
            'total_harga' => (float) $booking->total_harga,
            'status' => $booking->status,
            'status_pembayaran' => $booking->status_pembayaran,
        ]);
    }
}
