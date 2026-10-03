<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\LanggananUser;
use App\Models\MembershipPaket;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class MembershipService
{
    public const MASA_AKTIF_HARI = 30;

    /** Langganan yang masih berlaku hari ini (status aktif dan belum lewat tanggal berakhir). */
    public function langgananAktif(int $userId): ?LanggananUser
    {
        return LanggananUser::with('paket')
            ->where('user_id', $userId)
            ->where('status', 'aktif')
            ->whereDate('tanggal_berakhir', '>=', now()->toDateString())
            ->latest('tanggal_berakhir')
            ->first();
    }

    /**
     * Berlangganan paket selama 30 hari dari hari ini.
     * - Paket sama dengan yang aktif: diperpanjang 30 hari dari tanggal berakhir.
     * - Paket lain: paket lama dibatalkan, paket baru langsung berlaku (tanpa prorata).
     *
     * CATATAN: pembayaran belum tersambung ke Midtrans. Kalau nanti dipasang,
     * panggil method ini setelah pembayaran berstatus settlement.
     */
    public function berlangganan(User $user, MembershipPaket $paket): LanggananUser
    {
        return DB::transaction(function () use ($user, $paket) {
            $aktif = $this->langgananAktif($user->id);

            if ($aktif && $aktif->membership_paket_id === $paket->id) {
                $aktif->update([
                    'tanggal_berakhir' => $aktif->tanggal_berakhir->copy()->addDays(self::MASA_AKTIF_HARI),
                ]);

                return $aktif->fresh('paket');
            }

            $aktif?->update(['status' => 'dibatalkan']);

            return LanggananUser::create([
                'user_id' => $user->id,
                'membership_paket_id' => $paket->id,
                'tanggal_mulai' => today(),
                'tanggal_berakhir' => today()->addDays(self::MASA_AKTIF_HARI),
                'status' => 'aktif',
                'sisa_kuota_gratis' => $paket->kuota_booking_gratis,
            ])->load('paket');
        });
    }

    /**
     * Diskon membership untuk sebuah total harga.
     *
     * @return array{nominal: float, persen: float, paket: ?string}
     */
    public function diskonBooking(int $userId, float $total): array
    {
        $langganan = $this->langgananAktif($userId);

        if (! $langganan || ! $langganan->paket || $total <= 0) {
            return ['nominal' => 0.0, 'persen' => 0.0, 'paket' => null];
        }

        $persen = (float) $langganan->paket->persentase_diskon_booking;

        return [
            'nominal' => round($total * $persen / 100, 2),
            'persen' => $persen,
            'paket' => $langganan->paket->nama,
        ];
    }
}