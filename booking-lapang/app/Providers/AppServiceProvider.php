<?php

namespace App\Providers;

use App\Models\Lapangan;
use App\Observers\LapanganObserver;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Midtrans\Config;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;
        Lapangan::observe(LapanganObserver::class);

        // Privasi data: ekspor maksimal 3x per hari per user
        RateLimiter::for('ekspor-data', fn ($request) =>
            Limit::perDay(3)->by($request->user()?->id ?: $request->ip())
        );
    }
}