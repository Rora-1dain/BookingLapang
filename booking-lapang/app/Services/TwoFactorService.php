<?php

namespace App\Services;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use PragmaRX\Google2FA\Google2FA;

class TwoFactorService
{
    public function __construct(protected Google2FA $google2fa) {}

    public function mulaiAktivasi(User $user): string
    {
        if ($user->two_factor_aktif_pada) {
            throw new Exception('2FA sudah aktif. Nonaktifkan dulu sebelum mengaktifkan ulang.');
        }

        $secret = $this->google2fa->generateSecretKey();

        $user->update(['two_factor_secret' => $secret]);

        return $this->google2fa->getQRCodeUrl(config('app.name'), $user->email, $secret);
    }

    public function urlQr(User $user): string
    {
        return $this->google2fa->getQRCodeUrl(config('app.name'), $user->email, $user->two_factor_secret);
    }

    public function konfirmasiAktivasi(User $user, string $kode): array
    {
        if ($user->two_factor_aktif_pada) {
            throw new Exception('2FA sudah aktif.');
        }

        if (! $user->two_factor_secret || ! $this->verifikasi($user, $kode)) {
            throw new Exception('Kode tidak valid. Pastikan jam perangkat Anda akurat.');
        }

        $user->update(['two_factor_aktif_pada' => now()]);

        // Hanya status yang dicatat, tidak ada secret atau recovery code di log
        app(AuditService::class)->catat(
            '2fa.diaktifkan', $user,
            ['two_factor_aktif' => false],
            ['two_factor_aktif' => true]
        );

        return $this->buatRecoveryCodes($user);
    }

    public function verifikasi(User $user, string $kode): bool
    {
        if (! $user->two_factor_secret) {
            return false;
        }

        return (bool) $this->google2fa->verifyKey($user->two_factor_secret, $kode);
    }

    public function buatRecoveryCodes(User $user): array
    {
        $plain = collect(range(1, 8))
            ->map(fn () => Str::upper(Str::random(10)))
            ->all();

        $user->update([
            'two_factor_recovery_codes' => array_map(fn ($c) => Hash::make($c), $plain),
        ]);

        return $plain; // tampilkan SEKALI ke user, jangan disimpan dalam bentuk asli
    }

    public function pakaiRecoveryCode(User $user, string $kode): bool
    {
        $kode = Str::upper(trim($kode));
        $tersimpan = $user->two_factor_recovery_codes ?? [];
        $sisaSebelum = count($tersimpan);

        foreach ($tersimpan as $index => $hash) {
            if (Hash::check($kode, $hash)) {
                unset($tersimpan[$index]); // sekali pakai
                $user->update(['two_factor_recovery_codes' => array_values($tersimpan)]);

                app(AuditService::class)->catat(
                    '2fa.recovery_code_dipakai', $user,
                    ['sisa_recovery_code' => $sisaSebelum],
                    ['sisa_recovery_code' => count($tersimpan)]
                );

                return true;
            }
        }

        return false;
    }
}
