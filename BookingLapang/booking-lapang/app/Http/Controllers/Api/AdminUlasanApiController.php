<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ulasan;
use Illuminate\Http\Request;

class AdminUlasanApiController extends Controller
{
    public function index(Request $request)
    {
        $counts = [
            'dilaporkan' => Ulasan::where('dilaporkan', true)->where('disembunyikan', false)->count(),
            'disembunyikan' => Ulasan::where('disembunyikan', true)->count(),
            'semua' => Ulasan::count(),
        ];

        $filter = $request->query('filter', 'dilaporkan');
        $query = Ulasan::with(['booking.user:id,name,email', 'booking.lapangan:id,nama_lapangan']);

        if ($filter === 'disembunyikan') {
            $query->where('disembunyikan', true);
        } elseif ($filter === 'dilaporkan') {
            $query->where('dilaporkan', true)->where('disembunyikan', false);
        }

        $ulasans = $query->latest()->paginate(15);

        return response()->json([
            'data' => $ulasans->items(),
            'meta' => [
                'current_page' => $ulasans->currentPage(),
                'last_page' => $ulasans->lastPage(),
                'total' => $ulasans->total(),
            ],
            'counts' => $counts,
        ]);
    }

    public function dilaporkan()
    {
        $ulasans = Ulasan::with(['booking.user:id,name,email', 'booking.lapangan:id,nama_lapangan'])
            ->where('dilaporkan', true)
            ->latest()
            ->get();

        return response()->json(['data' => $ulasans]);
    }

    public function publikasikan(Ulasan $ulasan)
    {
        $ulasan->update(['dilaporkan' => false, 'disembunyikan' => false]);

        return response()->json([
            'message' => 'Ulasan dipertahankan dan tetap tampil ke publik.',
            'data' => $ulasan,
        ]);
    }

    public function sembunyikan(Ulasan $ulasan)
    {
        $ulasan->update(['disembunyikan' => true, 'dilaporkan' => false]);

        return response()->json([
            'message' => 'Ulasan berhasil disembunyikan dari publik.',
            'data' => $ulasan,
        ]);
    }
}
