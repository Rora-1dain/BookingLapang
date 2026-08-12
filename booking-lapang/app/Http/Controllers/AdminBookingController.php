<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Services\BookingService;
use Exception;

class AdminBookingController extends Controller
{
    protected BookingService $bookingService;

    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    public function index()
    {
        $bookings = Booking::with(['lapangan', 'user'])->latest()->get();

        return view('admin.booking.index', compact('bookings'));
    }

    public function confirm(Booking $booking)
    {
        try {
            $this->bookingService->konfirmasiBooking($booking);

            return back()->with('success', 'Booking berhasil dikonfirmasi.');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}