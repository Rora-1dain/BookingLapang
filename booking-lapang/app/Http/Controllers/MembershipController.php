<?php

namespace App\Http\Controllers;

use App\Models\MembershipPaket;
use App\Services\SubscriptionService;
use Exception;
use Illuminate\Http\Request;

class MembershipController extends Controller
{
    public function index()
    {
        return response()->json(MembershipPaket::all());
    }

    
    public function berlangganan(MembershipPaket $paket, SubscriptionService $subscriptionService, Request $request)
    {
        try {
            $langganan = $subscriptionService->berlangganan($request->user(), $paket);

            return response()->json([
                'message' => 'Langganan berhasil dibuat',
                'data' => $langganan->load('paket'),
            ], 201);
        } catch (Exception $e) {
            // ditolak kalau user masih punya langganan aktif
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    
    public function status(Request $request)
    {
        $langganan = $request->user()->langgananAktif();

        if (! $langganan) {
            return response()->json(['punya_langganan_aktif' => false]);
        }

        return response()->json([
            'punya_langganan_aktif' => true,
            'paket' => $langganan->paket->nama,
            'sisa_kuota_gratis' => $langganan->sisa_kuota_gratis,
            'tanggal_berakhir' => $langganan->tanggal_berakhir->format('Y-m-d'),
            'hari_tersisa' => now()->diffInDays($langganan->tanggal_berakhir, false),
        ]);
    }
}