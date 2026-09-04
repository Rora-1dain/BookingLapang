<?php

namespace App\Http\Controllers;

use App\Services\ReferralService;
use Illuminate\Support\Facades\Auth;

class ReferralController extends Controller
{
    public function index(ReferralService $referralService)
    {
        $user = Auth::user();

        $jumlahTemanDaftar = $user->referrals()->count();
        $jumlahRewardDiterima = $user->referrals()->where('reward_referral_diberikan', true)->count();

        return view('profile.referral', [
            'kodeReferral' => $user->kode_referral,
            'linkReferral' => config('app.url') . '/register?ref=' . $user->kode_referral,
            'jumlahTemanDaftar' => $jumlahTemanDaftar,
            'jumlahRewardDiterima' => $jumlahRewardDiterima,
        ]);
    }

    public function leaderboard(ReferralService $referralService)
    {
        return view('referral.leaderboard', [
            'topReferrer' => $referralService->leaderboardBulanIni(),
        ]);
    }
}