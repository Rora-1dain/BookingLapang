<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Booking;
use App\Models\Voucher;
use App\Models\VoucherUsage;
use Exception;

class VoucherService
{
    /**
     * Validasi kode voucher: harus ada, belum kadaluarsa, kuota masih ada,
     * memenuhi minimal transaksi, sesuai syarat user baru, dan belum pernah dipakai user ini.
     */
    public function validasiVoucher(string $kode, int $userId, float $totalHarga): Voucher
    {
        $voucher = Voucher::where('kode', $kode)->first();

        if (! $voucher) {
            throw new Exception('Kode voucher tidak ditemukan.');
        }

        if ($voucher->berlaku_sampai->isPast()) {
            throw new Exception('Voucher sudah kadaluarsa.');
        }

        if ($voucher->kuota <= 0) {
            throw new Exception('Kuota voucher sudah habis.');
        }

        if ($totalHarga < $voucher->minimal_transaksi) {
            throw new Exception('Transaksi belum memenuhi minimal untuk voucher ini.');
        }

        if ($voucher->khusus_user_baru && Booking::where('user_id', $userId)->exists()) {
            throw new Exception('Voucher ini khusus untuk user yang belum pernah booking.');
        }

        $sudahDipakai = VoucherUsage::where('voucher_id', $voucher->id)
            ->where('user_id', $userId)
            ->exists();

        if ($sudahDipakai) {
            throw new Exception('Anda sudah pernah memakai voucher ini.');
        }

        return $voucher;
    }

    /**
     * Hitung nominal diskon sesuai jenis voucher.
     * Diskon persen dibatasi maksimal Rp50.000.
     */
    public function hitungDiskon(Voucher $voucher, float $totalHarga): float
    {
        if ($voucher->jenis_diskon === 'persen') {
            $diskon = $totalHarga * ((float) $voucher->nilai / 100);

            return min($diskon, 50000);
        }

        return min((float) $voucher->nilai, $totalHarga);
    }

    /**
     * Catat pemakaian voucher oleh user, lalu kurangi kuota.
     */
    public function catatPemakaian(Voucher $voucher, int $userId): void
    {
        VoucherUsage::create([
            'voucher_id' => $voucher->id,
            'user_id' => $userId,
        ]);

        $voucher->decrement('kuota');
    }

    /**
     * Generate kode voucher unik, misal PROMO-X7K2A9.
     */
    public function generateKodeUnik(string $prefix): string
    {
        do {
            $kode = strtoupper($prefix.'-'.substr(str_shuffle('ABCDEFGHJKLMNPQRSTUVWXYZ23456789'), 0, 6));
        } while (Voucher::where('kode', $kode)->exists());

        return $kode;
    }
}