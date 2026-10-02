<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class RingkasanSheetExport implements FromArray, WithHeadings, WithTitle, ShouldAutoSize
{
    public function __construct(protected array $ringkasan) {}

    public function array(): array
    {
        return [
            ['Total GMV', $this->ringkasan['total_gmv']],
            ['Total Komisi Platform', $this->ringkasan['total_komisi']],
            ['Total Pendapatan Pemilik', $this->ringkasan['total_pemilik']],
            ['Jumlah Transaksi', $this->ringkasan['jumlah_baris']],
        ];
    }

    public function headings(): array
    {
        return ['Keterangan', 'Nilai'];
    }

    public function title(): string
    {
        return 'Ringkasan';
    }
}
