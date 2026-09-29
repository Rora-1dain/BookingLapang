<?php

namespace App\Http\Controllers;

use App\Models\Ulasan;
use Illuminate\Http\Request;

class AdminUlasanController extends Controller
{
    public function index(Request $request)
    {
        $counts = [
            'dilaporkan' => Ulasan::where('dilaporkan', true)->where('disembunyikan', false)->count(),
            'disembunyikan' => Ulasan::where('disembunyikan', true)->count(),
        ];

        $filter = $request->query('filter', 'dilaporkan');

        $query = Ulasan::with('booking.user', 'booking.lapangan');
        if ($filter === 'disembunyikan') {
            $query->where('disembunyikan', true);
        } else {
            $query->where('dilaporkan', true)->where('disembunyikan', false);
        }

        $ulasan = $query->latest()->paginate(10)->withQueryString();

        return view('admin.ulasan.index', compact('ulasan', 'counts', 'filter'));
    }

    public function publikasikan(Ulasan $ulasan)
    {
        $ulasan->update(['dilaporkan' => false, 'disembunyikan' => false]);

        return back()->with('success', 'Ulasan dipertahankan dan tetap tampil ke publik.');
    }

    public function sembunyikan(Ulasan $ulasan)
    {
        $ulasan->update(['disembunyikan' => true, 'dilaporkan' => false]);

        return back()->with('success', 'Ulasan disembunyikan dari publik.');
    }
}
