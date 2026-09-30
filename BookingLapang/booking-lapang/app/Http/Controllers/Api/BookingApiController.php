<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookingResource;
use App\Models\Booking;
use App\Services\BookingService;
use App\Services\InvoiceService;
use App\Services\PaymentService;
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

    // POST /api/booking/{booking}/bayar — versi API dari BookingController::bayar()
    // (web). Mengembalikan snap_token Midtrans + client_key supaya frontend bisa
    // muat snap.js sendiri dan panggil snap.pay() tanpa perlu render Blade.
    public function bayar(Request $request, Booking $booking, PaymentService $paymentService)
    {
        $booking->pastikanMilikUser($request->user()->id);

        try {
            $snapToken = $paymentService->buatTransaksi($booking);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json([
            'snap_token' => $snapToken,
            'client_key' => config('services.midtrans.client_key'),
            'is_production' => (bool) config('services.midtrans.is_production'),
        ]);
    }

    // POST /api/booking/{booking}/cek-status — versi API dari BookingController
    // ::cekStatus() (web). Dipanggil dari React setelah popup Midtrans
    // onSuccess/onPending, karena webhook /payment/notification butuh sedikit
    // waktu sampai — ini pengecekan aktif langsung ke Midtrans sebagai fallback.
    public function cekStatus(Request $request, Booking $booking, PaymentService $paymentService)
    {
        $booking->pastikanMilikUser($request->user()->id);

        try {
            $hasil = $paymentService->cekStatusTransaksi($booking);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json($hasil);
    }

    // GET /api/booking/{booking}/invoice — versi API dari BookingController
    // ::invoice() (web). Mengembalikan PDF mentah (bukan JSON) supaya di React
    // tinggal di-fetch dengan header Authorization lalu diunduh sebagai blob.
    public function invoice(Request $request, Booking $booking, InvoiceService $invoiceService)
    {
        $isPemilik = $booking->user_id === $request->user()->id;
        $isAdmin = $request->user()->role === 'admin';

        if (! $isPemilik && ! $isAdmin) {
            return response()->json(['message' => 'Anda tidak berhak mengunduh invoice ini.'], 403);
        }

        try {
            $pdf = $invoiceService->buatPdf($booking);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response($pdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="invoice-'.$booking->payment_reference.'.pdf"',
        ]);
    }
}
