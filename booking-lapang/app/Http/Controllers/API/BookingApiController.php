<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookingResource;
use App\Models\Booking;
use App\Services\BookingService;
use Exception;
use Illuminate\Http\Request;

class BookingApiController extends Controller
{
    public function __construct(protected BookingService $bookingService) {}

    public function index(Request $request)
    {
        $query = Booking::with('lapangan')
            ->where('user_id', $request->user()->id)
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->query('status'));
        }

        $bookings = $query->paginate(10);

        return BookingResource::collection($bookings);
    }

    public function show(Request $request, Booking $booking)
    {
        if ($booking->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Tidak berhak melihat booking ini.'], 403);
        }

        return new BookingResource($booking->load('lapangan'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'lapangan_id' => 'required|exists:lapangans,id',
            'tanggal_booking' => 'required|date|after_or_equal:today',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
        ]);

        $validated['user_id'] = $request->user()->id;

        try {
            $booking = $this->bookingService->buatBooking($validated);

            return (new BookingResource($booking->load('lapangan')))
                ->response()
                ->setStatusCode(201);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    public function cancel(Request $request, Booking $booking)
    {
        if ($booking->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Tidak berhak.'], 403);
        }

        $this->bookingService->batalkanBooking($booking);

        return new BookingResource($booking);
    }
}
