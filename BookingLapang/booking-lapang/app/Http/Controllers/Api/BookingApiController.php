<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookingResource;
use App\Models\Booking;
use App\Services\BookingService;
use App\Services\InvoiceService;
use App\Services\PaymentService;
use App\Services\RefundService;
use App\Services\VoucherService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class BookingApiController extends Controller
{
    public function __construct(protected BookingService $bookingService) {}

    public function index(Request $request)
    {
        $query = Booking::with(['lapangan', 'ulasan'])
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

        return new BookingResource($booking->load(['lapangan', 'ulasan']));
    }

    public function store(Request $request, VoucherService $voucherService)
    {
        $validated = $request->validate([
            'lapangan_id' => 'required|exists:lapangans,id',
            'tanggal_booking' => 'required|date|after_or_equal:today',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'kode_voucher' => 'nullable|string',
        ]);

        $validated['user_id'] = $request->user()->id;

        try {
            $booking = $this->bookingService->buatBooking($validated, $voucherService);

            return (new BookingResource($booking->load(['lapangan', 'ulasan'])))
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

        return new BookingResource($booking->load('lapangan'));
    }

    public function mintaRefund(Request $request, Booking $booking, RefundService $refundService)
    {
        $booking->pastikanMilikUser($request->user()->id);

        $validated = $request->validate(['alasan' => 'required|string|max:255']);

        try {
            $booking = $refundService->mintaRefund($booking, $validated['alasan'], $request->user()->id);

            return new BookingResource($booking->load('lapangan'));
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

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

    public function kirimUlangInvoice(Request $request, Booking $booking, InvoiceService $invoiceService)
    {
        $isPemilik = $booking->user_id === $request->user()->id;
        $isAdmin = $request->user()->role === 'admin';

        if (! $isPemilik && ! $isAdmin) {
            return response()->json(['message' => 'Tidak berhak.'], 403);
        }

        $pdf = $invoiceService->buatPdf($booking);

        Mail::to($booking->user->email)->send(
            new \App\Mail\InvoiceMail($booking, $pdf->output())
        );

        return response()->json(['message' => 'Invoice telah dikirim ulang ke email Anda.']);
    }

    public function cekKetersediaan(Request $request)
    {
        $data = $request->validate([
            'lapangan_id' => 'required|exists:lapangans,id',
            'tanggal_booking' => 'required|date',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
        ]);

        $tersedia = $this->bookingService->cekKetersediaan(
            (int) $data['lapangan_id'],
            $data['tanggal_booking'],
            $data['jam_mulai'],
            $data['jam_selesai']
        );

        return response()->json(['tersedia' => $tersedia]);
    }
}
