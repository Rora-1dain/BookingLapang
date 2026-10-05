<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Cors
{
    public function handle(Request $request, Closure $next): Response
    {
        // Echo balik Origin request, bukan '*'. Kombinasi '*' + Allow-Credentials
        // itu tidak valid menurut spec CORS dan ditolak browser (inilah salah satu
        // penyebab error "tidak nyambung ke server" saat memanggil API lintas origin).
        // Kalau tidak ada header Origin (mis. request same-origin/server-to-server),
        // pakai '*' sebagai fallback aman.
        $origin = $request->headers->get('Origin') ?: '*';

        $headers = [
            'Access-Control-Allow-Origin' => $origin,
            'Access-Control-Allow-Methods' => 'GET, POST, PUT, PATCH, DELETE, OPTIONS',
            'Access-Control-Allow-Headers' => 'Content-Type, Authorization, X-Requested-With, X-XSRF-TOKEN, Accept',
            'Access-Control-Allow-Credentials' => 'true',
            // Supaya cache (proxy/CDN) tidak menyajikan respons satu origin ke origin lain.
            'Vary' => 'Origin',
        ];

        // Tangani preflight OPTIONS lebih awal.
        if ($request->getMethod() === 'OPTIONS') {
            return response('', 204, $headers);
        }

        return $next($request)->withHeaders($headers);
    }
}
