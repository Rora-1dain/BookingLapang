<?php

namespace App\Services;

use App\Models\PoinHistory;
use App\Models\User;
use App\Models\Voucher;
use App\Services\VoucherService;
use Exception;

class LoyaltyService
{
    public function tambahPoin(User $user, int $jumlah, string $keterangan): void
    {
        $user->increment('poin', $jumlah);

        PoinHistory::create([
            'user_id' => $user->id,
            'jumlah' => $jumlah,
            'keterangan' => $keterangan,
        ]);
    }

    public function kurangiPoin(User $user, int $jumlah, string $keterangan): void
    {
        if ($user->poin < $jumlah) {
            throw new Exception('Poin tidak mencukupi.');
        }

        $user->decrement('poin', $jumlah);

        PoinHistory::create([
            'user_id' => $user->id,
            'jumlah' => -$jumlah,
            'keterangan' => $keterangan,
        ]);
    }

    public function tentukanTier(User $user): string
    {
        $totalLifetime = $user->poinHistories()->where('jumlah', '>', 0)->sum('jumlah');

        return match (true) {
            $totalLifetime >= 1500 => 'Platinum',
            $totalLifetime >= 500 => 'Gold',
            default => 'Silver',
        };
    }

    public function redeemPoin(User $user, int $jumlahPoin, VoucherService $voucherService): Voucher
    {
        $this->kurangiPoin($user, $jumlahPoin, 'Ditukar dengan voucher diskon');

        $nilaiDiskon = intdiv($jumlahPoin, 100) * 10000;

        return Voucher::create([
            'kode' => $voucherService->generateKodeUnik('POIN'),
            'jenis_diskon' => 'nominal',
            'nilai' => $nilaiDiskon,
            'kuota' => 1,
            'berlaku_sampai' => now()->addDays(30),
        ]);
    }
}
