<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Lapangan;
use App\Observers\LapanganObserver;

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
    \Midtrans\Config::$serverKey = config('midtrans.server_key');
    \Midtrans\Config::$isProduction = config('midtrans.is_production');
    \Midtrans\Config::$isSanitized = true;
    \Midtrans\Config::$is3ds = true;
    Lapangan::observe(LapanganObserver::class);
}
}
