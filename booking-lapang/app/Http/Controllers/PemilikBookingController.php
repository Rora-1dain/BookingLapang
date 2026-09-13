<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Lapangan;
use Illuminate\Http\Request;

class PemilikBookingController extends Controller
{
    public function index(Request $request)
    {
        $lapanganIds = Lapangan::where('pemilik_id', auth()->id())->pluck('id');
        $lapangans = Lapangan::where('pemilik_id', auth()->id())->get(['id', 'nama_lapangan']);

        $baseQuery = fn () => Booking::whereIn('lapangan_id', $lapanganIds);

        $counts = [
            'semua' => $baseQuery()->count(),
            'hari_ini' => $baseQuery()->whereDate('tanggal_booking', today())->count(),
            'akan_datang' => $baseQuery()->whereDate('tanggal_booking', '>', today())->count(),
            'selesai' => $baseQuery()->whereDate('tanggal_booking', '<', today())->where('status', '!=', 'cancelled')->count(),
            'dibatalkan' => $baseQuery()->where('status', 'cancelled')->count(),
        ];

        $filter = $request->query('filter', 'semua');

        $query = $baseQuery()->with(['lapangan', 'user']);

        match ($filter) {
            'hari_ini' => $query->whereDate('tanggal_booking', today()),
            'akan_datang' => $query->whereDate('tanggal_booking', '>', today()),
            'selesai' => $query->whereDate('tanggal_booking', '<', today())->where('status', '!=', 'cancelled'),
            'dibatalkan' => $query->where('status', 'cancelled'),
            default => null,
        };

        if ($request->filled('lapangan_id')) {
            $query->where('lapangan_id', $request->query('lapangan_id'));
        }

        $bookings = $query->orderBy('tanggal_booking')->orderBy('jam_mulai')
            ->paginate(10)
            ->withQueryString();

        return view('pemilik.booking.index', compact('bookings', 'lapangans', 'filter', 'counts'));
    }
}