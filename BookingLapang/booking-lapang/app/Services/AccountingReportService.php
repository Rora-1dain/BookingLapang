<?php

namespace App\Services;

use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class AccountingReportService
{
    public function ledgerTransaksi(Carbon $mulai, Carbon $selesai): Collection
    {
        return Booking::with(['lapangan.pemilik'])
            ->where('status_pembayaran', 'paid')
            ->whereBetween('tanggal_booking', [
                $mulai->copy()->startOfDay(),
                $selesai->copy()->endOfDay(),
            ])
            ->orderBy('tanggal_booking')
            ->get()
            ->map(fn (Booking $b) => [
                'tanggal'            => $b->tanggal_booking->format('Y-m-d'),
                'no_invoice'         => $b->nomor_invoice,
                'pemilik'            => $b->lapangan->pemilik->name ?? '-',
                'gmv'                => (float) $b->total_harga,
                'komisi_platform'    => (float) $b->nominal_komisi,
                'pendapatan_pemilik' => (float) $b->pendapatan_pemilik,
                'status_refund'      => $b->status_refund ?? '-',
            ]);
    }

    public function ringkasan(Carbon $mulai, Carbon $selesai): array
    {
        $data = $this->ledgerTransaksi($mulai, $selesai);

        return [
            'total_gmv'     => $data->sum('gmv'),
            'total_komisi'  => $data->sum('komisi_platform'),
            'total_pemilik' => $data->sum('pendapatan_pemilik'),
            'jumlah_baris'  => $data->count(),
        ];
    }
}