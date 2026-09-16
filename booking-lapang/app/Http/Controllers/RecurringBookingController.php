<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Lapangan;
use App\Models\RecurringBooking;
use App\Services\PaymentService;
use App\Services\RecurringBookingService;
use Illuminate\Http\Request;

class RecurringBookingController extends Controller
{
   
    public function create(Lapangan $lapangan)
    {
        return view('booking.berulang.create', compact('lapangan'));
    }

    public function store(Request $request, RecurringBookingService $recurringService)
    {
        $validated = $request->validate([
            'lapangan_id'    => 'required|exists:lapangans,id',
            'hari'           => 'required|integer|between:0,6',
            'jam_mulai'      => 'required|date_format:H:i',
            'jam_selesai'    => 'required|date_format:H:i|after:jam_mulai',
            'tanggal_mulai'  => 'required|date|after_or_equal:today',
            'jumlah_sesi'    => 'required|integer|min:2|max:12',
        ]);
        $validated['user_id'] = auth()->id();

        $hasil = $recurringService->buatPaket($validated);

        $lapangan = Lapangan::find($validated['lapangan_id']);

        return view('booking.berulang.ringkasan', array_merge($hasil, ['lapangan' => $lapangan]));
    }

   
    public function bayar(Request $request, PaymentService $paymentService, $recurringBookingId)
    {
        
        $bookings = Booking::where('recurring_booking_id', $recurringBookingId)
            ->where('status', 'pending') 
            ->where('status_pembayaran', '!=', 'paid')
            ->get();

        if ($bookings->isEmpty()) {
            return response()->json(['message' => 'Tidak ada sesi yang perlu dibayar.'], 422);
        }

        $snapToken = $paymentService->buatTransaksiGabungan($bookings->all(), auth()->id());

        return response()->json(['snap_token' => $snapToken]);
    }
}