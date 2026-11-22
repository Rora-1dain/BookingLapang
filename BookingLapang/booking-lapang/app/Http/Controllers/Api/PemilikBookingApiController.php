<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Lapangan;
use Illuminate\Http\Request;

class PemilikBookingApiController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $lapanganIds = Lapangan::where('pemilik_id', $user->id)->pluck('id');

        $baseQuery = fn () => Booking::whereIn('lapangan_id', $lapanganIds);

        $counts = [
            'semua' => $baseQuery()->count(),
            'hari_ini' => $baseQuery()->whereDate('tanggal_booking', today())->count(),
            'akan_datang' => $baseQuery()->whereDate('tanggal_booking', '>', today())->count(),
            'selesai' => $baseQuery()->whereDate('tanggal_booking', '<', today())->where('status', '!=', 'cancelled')->count(),
            'dibatalkan' => $baseQuery()->where('status', 'cancelled')->count(),
        ];

        $filter = $request->query('filter', 'semua');
        $query = $baseQuery()->with(['lapangan:id,nama_lapangan,jenis', 'user:id,name,email']);

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

        $bookings = $query->orderBy('tanggal_booking', 'desc')
            ->orderBy('jam_mulai', 'desc')
            ->paginate(15);

        return response()->json([
            'data' => $bookings->items(),
            'meta' => [
                'current_page' => $bookings->currentPage(),
                'last_page' => $bookings->lastPage(),
                'total' => $bookings->total(),
            ],
            'counts' => $counts,
        ]);
    }
}
