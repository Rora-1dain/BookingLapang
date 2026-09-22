<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payout;
use App\Models\User;
use App\Notifications\PayoutSelesai;
use App\Services\PayoutService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Exception;

class AdminPayoutApiController extends Controller
{
    public function index()
    {
        return response()->json(Payout::with('pemilik')->latest()->paginate(20));
    }

    public function store(Request $request, PayoutService $payoutService)
    {
        $validated = $request->validate([
            'pemilik_id' => 'required|exists:users,id',
            'periode_mulai' => 'required|date',
            'periode_selesai' => 'required|date|after_or_equal:periode_mulai',
        ]);

        try {
            $pemilik = User::findOrFail($validated['pemilik_id']);
            $payout = $payoutService->buatPayout(
                $pemilik, Carbon::parse($validated['periode_mulai']), Carbon::parse($validated['periode_selesai'])
            );

            return response()->json(['data' => $payout], 201);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    public function selesai(Payout $payout, PayoutService $payoutService, Request $request)
    {
        $payout = $payoutService->tandaiSelesai($payout, $request->user()->id);
        $payout->pemilik->notify(new PayoutSelesai($payout));

        return response()->json(['message' => 'Payout ditandai selesai.', 'data' => $payout]);
    }
}
