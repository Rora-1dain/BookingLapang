<?php

namespace Tests\Unit;

use App\Models\Booking;
use App\Services\AccountingReportService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccountingReportServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_total_gmv_sama_dengan_komisi_ditambah_pendapatan_pemilik()
    {
        Booking::factory()->count(5)->create([
            'status_pembayaran' => 'paid',
            'tanggal_booking' => now()->subDay(),
            'total_harga' => 100000,
            'nominal_komisi' => 10000,
            'pendapatan_pemilik' => 90000,
        ]);

        $ringkasan = app(AccountingReportService::class)
            ->ringkasan(now()->subMonth(), now());

        $this->assertEquals(5, $ringkasan['jumlah_baris']);
        $this->assertEquals(500000, $ringkasan['total_gmv']);
        $this->assertEquals(
            $ringkasan['total_gmv'],
            $ringkasan['total_komisi'] + $ringkasan['total_pemilik']
        );
    }

    public function test_booking_belum_dibayar_tidak_ikut_dihitung()
    {
        Booking::factory()->create([
            'status_pembayaran' => 'pending',
            'tanggal_booking' => now()->subDay(),
            'total_harga' => 100000,
            'nominal_komisi' => 10000,
            'pendapatan_pemilik' => 90000,
        ]);

        $ringkasan = app(AccountingReportService::class)
            ->ringkasan(now()->subMonth(), now());

        $this->assertEquals(0, $ringkasan['jumlah_baris']);
    }
}
