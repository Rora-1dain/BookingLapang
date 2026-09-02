<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingRequest;
use App\Models\Booking;
use App\Models\Lapangan;
use App\Services\BookingService;
use App\Services\PaymentService;
use App\Services\VoucherService;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Auth;

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

    public function cancel(Booking $booking)
    {
        $booking->pastikanMilikUser(Auth::id());

        $this->bookingService->batalkanBooking($booking);

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

    <?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\WaitlistService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WaitlistController extends Controller
{
    protected WaitlistService $waitlistService;

    public function __construct(WaitlistService $waitlistService)
    {
        $this->waitlistService = $waitlistService;
    }

    public function daftar(Request $request)
    {
        $data = $request->validate([
            'lapangan_id' => 'required|exists:lapangans,id',
            'tanggal_booking' => 'required|date',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
        ]);

        $data['user_id'] = Auth::id();

        try {
            $this->waitlistService->daftarTunggu($data);

            return response()->json(['success' => true]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    }
}