<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ReferralService;
use Illuminate\Http\Request;

class ReferralApiController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $totalTemanDaftar = $user->referrals()->count();
        $totalReferralSukses = $user->referrals()->where('reward_referral_diberikan', true)->count();

        return response()->json([
            'kode_referral' => $user->kode_referral,
            'link_referral' => rtrim(config('app.url'), '/').'/register?ref='.$user->kode_referral,
            'total_teman_daftar' => $totalTemanDaftar,
            'total_referral_sukses' => $totalReferralSukses,
        ]);
    }

    public function leaderboard(ReferralService $referralService)
    {
        $top = $referralService->leaderboardBulanIni(10);

        return response()->json([
            'data' => $top->map(fn ($user) => [
                'name' => $user->name,
                'jumlah_referral' => $user->referrals_count,
            ]),
        ]);
    }
}
