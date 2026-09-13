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
    public function cancel(Waitlist $waitlist)
    {
        abort_unless($waitlist->user_id === Auth::id(), 403);

        if ($waitlist->status === 'menunggu') {
            $waitlist->update(['status' => 'dibatalkan']);
        }

        return back()->with('success', 'Antrian berhasil dibatalkan.');
    }
}
