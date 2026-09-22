<?php

namespace App\Services;

use App\Models\LanggananUser;
use App\Models\MembershipPaket;
use App\Models\User;
use Exception;

class SubscriptionService
{
    public function berlangganan(User $user, MembershipPaket $paket): LanggananUser
    {
        if ($user->langgananAktif()) {
            throw new Exception('Anda masih memiliki langganan aktif.');
        }

        return LanggananUser::create([
            'user_id'             => $user->id,
            'membership_paket_id' => $paket->id,
            'tanggal_mulai'       => now(),
            'tanggal_berakhir'    => now()->addDays(30),
            'status'              => 'aktif',
            'sisa_kuota_gratis'   => $paket->kuota_booking_gratis,
        ]);
    }

    public function terapkanBenefit(User $user, float $totalHarga): array
    {
        $langganan = $user->langgananAktif();

        if (!$langganan) {
            return ['total_harga' => $totalHarga, 'gratis' => false, 'langganan_id' => null];
        }

        if ($langganan->sisa_kuota_gratis > 0) {
            $langganan->decrement('sisa_kuota_gratis');

            return ['total_harga' => 0, 'gratis' => true, 'langganan_id' => $langganan->id];
        }

        $diskon = $totalHarga * ($langganan->paket->persentase_diskon_booking / 100);

        return [
            'total_harga'  => $totalHarga - $diskon,
            'gratis'       => false,
            'langganan_id' => $langganan->id,
        ];
    }
}