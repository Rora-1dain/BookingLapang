<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTwoFactorVerified
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Halaman keamanan & logout harus tetap bisa dibuka, kalau tidak akan terjadi redirect loop
        if ($request->routeIs('keamanan.*', 'logout')) {
            return $next($request);
        }

        if ($user && in_array($user->role, ['admin', 'pemilik_lapangan'])) {
            if ($user->role === 'admin' && ! $user->two_factor_aktif_pada) {
                return redirect()->route('keamanan.pengaturan')
                    ->with('error', 'Admin wajib mengaktifkan 2FA.');
            }

            if ($user->two_factor_aktif_pada && ! $request->session()->get('2fa_terverifikasi')) {
                return redirect()->route('keamanan.tantangan');
            }
        }

        return $next($request);
    }
}