<?php

namespace App\Http\Controllers;

use App\Models\Payout;
use App\Models\User;
use App\Notifications\PayoutSelesai;
use App\Services\PayoutService;
use Illuminate\Http\Request;
use Exception;

class AdminPayoutController extends Controller
{
    public function create()
    {
        $pemilikList = User::where('role', 'pemilik_lapangan')->get();

        return view('admin.payout.create', compact('pemilikList'));
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
           $payoutService->buatPayout(
            $pemilik,
            \Carbon\Carbon::parse($validated['periode_mulai']),
            \Carbon\Carbon::parse($validated['periode_selesai'])
            );

            return back()->with('success', 'Payout berhasil dibuat.');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function selesai($payoutId, PayoutService $payoutService)
    {
        $payout = Payout::findOrFail($payoutId);
        $payoutService->tandaiSelesai($payout, auth()->id());
        $payout->pemilik->notify(new PayoutSelesai($payout));

        return back()->with('success', 'Payout ditandai selesai.');
    }

    public function index()
    {
        $payouts = \App\Models\Payout::with('pemilik')->latest()->get();
        return view('admin.payout.index', compact('payouts'));
    }
}