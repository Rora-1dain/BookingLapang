<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingRequest;
use App\Mail\InvoiceMail;
use App\Models\Booking;
use App\Models\Lapangan;
use App\Services\BookingService;
use App\Services\InvoiceService;
use App\Services\PaymentService;
use App\Services\VoucherService;
use App\Services\WaitlistService;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

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
        $lapangans = Lapangan::where('status', 'aktif')->get();

        return view('booking.create', compact('lapangans'));
    }

    public function store(StoreBookingRequest $request, VoucherService $voucherService)
    {
        $validated = $request->validated();
        $validated['user_id'] = $request->user()->id;

        try {
            $booking = $this->bookingService->buatBooking($validated, $voucherService);

            $pesan = 'Booking berhasil. Total: Rp'.number_format($booking->total_harga);

            if ($booking->total_diskon > 0) {
                $pesan .= ' (hemat Rp'.number_format($booking->total_diskon).')';
            }

            return redirect()->route('booking.index')->with('success', $pesan);
        } catch (Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function cancel(Booking $booking, WaitlistService $waitlistService)
    {
        $booking->pastikanMilikUser(Auth::id());

        $this->bookingService->batalkanBooking($booking);

        $ditawarkan = $waitlistService->prosesAntrian(
            $booking->lapangan_id,
            $booking->tanggal_booking->format('Y-m-d'),
            $booking->jam_mulai,
            $booking->jam_selesai
        );

        if ($ditawarkan) {
            $ditawarkan->user->notify(new \App\Notifications\SlotWaitlistTersedia($ditawarkan));
        }

        return back()->with('success', 'Booking berhasil dibatalkan.');
    }

    public function bayar(Booking $booking, PaymentService $paymentService)
    {
        $booking->pastikanMilikUser(Auth::id());

        $snapToken = $paymentService->buatTransaksi($booking);

        return view('booking.bayar', compact('booking', 'snapToken'));
    }

    public function status(Booking $booking)
    {
        $booking->pastikanMilikUser(Auth::id());

        return view('booking.status', compact('booking'));
    }

    public function cekStatus(Booking $booking, PaymentService $paymentService)
    {
        $booking->pastikanMilikUser(Auth::id());

        $hasil = $paymentService->cekStatusTransaksi($booking);

        return back()->with('info', 'Status transaksi: '.$hasil['transaction_status']);
    }

    public function invoice(Booking $booking, InvoiceService $invoiceService)
    {
        $isPemilik = $booking->user_id === Auth::id();
        $isAdmin = Auth::user()->role === 'admin';

        if (! $isPemilik && ! $isAdmin) {
            abort(403, 'Anda tidak berhak mengunduh invoice ini.');
        }

        $pdf = $invoiceService->buatPdf($booking);

        return $pdf->download("invoice-{$booking->payment_reference}.pdf");
    }

    public function kirimUlangInvoice(Booking $booking, InvoiceService $invoiceService)
    {
        $isPemilik = $booking->user_id === Auth::id();
        $isAdmin = Auth::user()->role === 'admin';

        if (! $isPemilik && ! $isAdmin) {
            abort(403);
        }

        $pdf = $invoiceService->buatPdf($booking);

        Mail::to($booking->user->email)->send(
            new InvoiceMail($booking, $pdf->output())
        );

        return back()->with('success', 'Invoice telah dikirim ulang ke email Anda.');
    public function cekKetersediaanAjax(Request $request)
    {
        $data = $request->validate([
            'lapangan_id' => 'required|exists:lapangans,id',
            'tanggal_booking' => 'required|date',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
        ]);

        $tersedia = $this->bookingService->cekKetersediaan(
            $data['lapangan_id'], $data['tanggal_booking'], $data['jam_mulai'], $data['jam_selesai']
        );

        return response()->json(['tersedia' => $tersedia]);
    }
}