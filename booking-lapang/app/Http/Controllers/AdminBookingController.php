<?php

namespace App\Http\Controllers;

use App\Exports\BookingExport;
use App\Models\Booking;
use App\Services\BookingService;
use Exception;
use Maatwebsite\Excel\Facades\Excel;

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

    public function export()
    {
        return Excel::download(new BookingExport, 'laporan-booking.xlsx');
    }

    public function cancel(Booking $booking)
    {
        try {
            $this->bookingService->batalkanBooking($booking);

            return back()->with('success', 'Booking berhasil dibatalkan.');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
