<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Exports\BookingExport;
use App\Models\Booking;
use App\Services\BookingService;
use App\Services\RefundService;
use Exception;
use Illuminate\Http\Request;
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

    public function refund(Request $request, Booking $booking, RefundService $refundService)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Hanya admin yang dapat mengajukan refund.');
        }

        $validated = $request->validate(['alasan' => 'required|string|max:255']);

        try {
            $refundService->ajukanrefund($booking, $validated['alasan'], auth()->id());

            return back()->with('success', 'Refund berhasil diproses.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function refundIndex(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $query = Booking::with(['lapangan', 'user'])
            ->where('status_refund', '!=', 'belum_refund');

        if ($request->filled('status')) {
            $query->where('status_refund', $request->query('status'));
        }

        return view('admin.refund.index', ['bookings' => $query->latest()->paginate(15)]);
    }
}