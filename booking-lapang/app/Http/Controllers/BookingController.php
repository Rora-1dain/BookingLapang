<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Services\BookingService;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;

class BookingController extends Controller
{
    protected BookingService $bookingService;

    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    public function index()
    {
        $bookings = Booking::with('lapangan')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('booking.index', compact('bookings'));
    }

    public function create()
    {
        $lapangans = \App\Models\Lapangan::where('status', 'aktif')->get();
        return view('booking.create', compact('lapangans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'lapangan_id' => 'required|exists:lapangans,id',
            'tanggal_booking' => 'required|date|after_or_equal:today',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
        ]);

        $validated['user_id'] = Auth::id();

        try {
            $booking = $this->bookingService->buatBooking($validated);

            return redirect()->route('booking.index')
                ->with('success', 'Booking berhasil. Total: Rp' . number_format($booking->total_harga));
        } catch (Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function cancel(Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Anda tidak berhak membatalkan booking ini.');
        }

        $this->bookingService->batalkanBooking($booking);

        return back()->with('success', 'Booking berhasil dibatalkan.');
    }

    public function bayar(Booking $booking, PaymentService $paymentService)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        $snapToken = $paymentService->buatTransaksi($booking);

        return view('booking.bayar', compact('booking', 'snapToken'));
    }

    public function status(Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        return view('booking.status', compact('booking'));
    }

    public function cekStatus(Booking $booking, PaymentService $paymentService)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        $hasil = $paymentService->cekStatusTransaksi($booking);

        return back()->with('info', 'Status transaksi: ' . $hasil['transaction_status']);
    }
}