<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\User;

class ReferralService
{
    public function generateKodeReferral(): string
    {
        do {
            $kode = 'REF' . strtoupper(substr(str_shuffle('ABCDEFGHJKLMNPQRSTUVWXYZ23456789'), 0, 6));
        } while (User::where('kode_referral', $kode)->exists());

        return $kode;
    }

    public function daftarDenganReferral(array $data, ?string $kodeReferral, string $ipPendaftar): User
    {
        $pengundang = $kodeReferral ? User::where('kode_referral', $kodeReferral)->first() : null;

        // dicurigai akun ganda (IP sama persis dgn pengundang) -> referral diabaikan
        if ($pengundang && $pengundang->ip_terakhir === $ipPendaftar) {
            $pengundang = null;
        }

        return User::create([
            ...$data,
            'kode_referral' => $this->generateKodeReferral(),
            'direferensikan_oleh' => $pengundang?->id,
            'ip_terakhir' => $ipPendaftar,
        ]);
    }

    public function prosesRewardReferral(Booking $booking, LoyaltyService $loyaltyService): void
    {
        $user = $booking->user;

        // udah pernah dikasih reward, atau emang bukan hasil referral -> skip
        if (!$user->direferensikan_oleh || $user->reward_referral_diberikan) {
            return;
        }

        $pengundang = User::find($user->direferensikan_oleh);

        if (!$pengundang) {
            return;
        }

        $loyaltyService->tambahPoin(
            $pengundang, 50, "Referral berhasil: {$user->name} melakukan booking pertama"
        );

        $user->update(['reward_referral_diberikan' => true]);

        $totalReferralSukses = User::where('direferensikan_oleh', $pengundang->id)
            ->where('reward_referral_diberikan', true)
            ->count();

        if ($totalReferralSukses === 5) {
            $loyaltyService->tambahPoin($pengundang, 100, 'Bonus milestone 5 referral');
        } elseif ($totalReferralSukses === 10) {
            $loyaltyService->tambahPoin($pengundang, 250, 'Bonus milestone 10 referral');
        }
    }

    public function leaderboardBulanIni(int $limit = 10)
    {
        return User::withCount(['referrals' => function ($q) {
            $q->where('reward_referral_diberikan', true)
              ->whereMonth('created_at', now()->month);
        }])
            ->orderByDesc('referrals_count')
            ->limit($limit)
            ->get();
    }
}