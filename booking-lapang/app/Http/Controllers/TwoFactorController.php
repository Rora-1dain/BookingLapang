<?php

namespace App\Http\Controllers;

use App\Services\AuditService;
use App\Services\TwoFactorService;
use Exception;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class TwoFactorController extends Controller
{
    public function __construct(protected TwoFactorService $twoFactor) {}

    public function pengaturan(Request $request)
    {
        $user = $request->user();
        $sedangAktivasi = $user->two_factor_secret && ! $user->two_factor_aktif_pada;

        return view('keamanan.pengaturan', [
            'aktif' => (bool) $user->two_factor_aktif_pada,
            'sedangAktivasi' => $sedangAktivasi,
            'qrSvg' => $sedangAktivasi ? QrCode::size(200)->generate($this->twoFactor->urlQr($user)) : null,
            'secret' => $sedangAktivasi ? $user->two_factor_secret : null,
            'recoveryCodes' => session('recovery_codes'),
        ]);
    }

    public function mulai(Request $request)
    {
        try {
            $this->twoFactor->mulaiAktivasi($request->user());
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('keamanan.pengaturan');
    }

    public function konfirmasi(Request $request)
    {
        $request->validate(['kode' => ['required', 'digits:6']]);

        try {
            $codes = $this->twoFactor->konfirmasiAktivasi($request->user(), $request->input('kode'));
        } catch (Exception $e) {
            return back()->withErrors(['kode' => $e->getMessage()]);
        }

        $request->session()->put('2fa_terverifikasi', true);

        return redirect()->route('keamanan.pengaturan')
            ->with('recovery_codes', $codes)
            ->with('success', '2FA berhasil diaktifkan. Simpan recovery code di bawah, hanya ditampilkan sekali.');
    }

    public function tantangan(Request $request)
    {
        $user = $request->user();

        if (! $user->two_factor_aktif_pada || $request->session()->get('2fa_terverifikasi')) {
            return redirect()->route('home');
        }

        return view('keamanan.tantangan');
    }

    public function verifikasi(Request $request)
    {
        $request->validate(['kode' => ['required', 'string', 'max:20']]);

        $user = $request->user();
        $kode = str_replace(' ', '', $request->input('kode'));

        $berhasil = preg_match('/^\d{6}$/', $kode)
            ? $this->twoFactor->verifikasi($user, $kode)
            : $this->twoFactor->pakaiRecoveryCode($user, $kode);

        if (! $berhasil) {
            $gagal = (int) $request->session()->get('2fa_gagal', 0) + 1;
            $request->session()->put('2fa_gagal', $gagal);

            // Mulai dicatat sejak kegagalan ke-3 berturut-turut
            if ($gagal >= 3) {
                app(AuditService::class)->catat(
                    '2fa.verifikasi_gagal_berulang', $user,
                    null,
                    ['percobaan_gagal' => $gagal]
                );
            }

            return back()->withErrors(['kode' => 'Kode tidak valid.']);
        }

        $request->session()->forget('2fa_gagal');
        $request->session()->put('2fa_terverifikasi', true);
        $request->session()->regenerate();

        return redirect()->intended(
            $user->role === 'admin' ? route('admin.dashboard') : route('pemilik.dashboard')
        );
    }
}
