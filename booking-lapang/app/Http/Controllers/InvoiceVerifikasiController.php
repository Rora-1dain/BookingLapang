<?php

namespace App\Http\Controllers;

use App\Models\Booking;

class InvoiceVerifikasiController extends Controller
{
    public function show(string $nomor)
    {
        $booking = Booking::where('nomor_invoice', $nomor)->first();

        return view('invoice.verifikasi', [
            'booking' => $booking,
            'valid' => $booking !== null,
        ]);
    }
}