<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminOnly
{
    /**
     * Handle an incoming request.
     *
     * Mengizinkan user dengan role Spatie: admin, staf_approval, staf_keuangan.
     * Pengecekan permission granular tetap dilakukan di masing-masing Controller
     * melalui $this->authorize().
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! auth()->check()) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        $user = auth()->user();

        // Izinkan jika user punya salah satu role staf/admin (Spatie),
        // ATAU masih punya kolom legacy role = 'admin' (fallback migrasi).
        if (! $user->hasAnyRole(['admin', 'staf_approval', 'staf_keuangan']) && $user->role !== 'admin') {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}
