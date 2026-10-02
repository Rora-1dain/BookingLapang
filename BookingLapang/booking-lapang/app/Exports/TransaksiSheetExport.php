<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class TransaksiSheetExport implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize
{
    public function __construct(protected Collection $data) {}

    public function collection(): Collection
    {
        return $this->data;
    }

    public function headings(): array
    {
        return ['Tanggal', 'No. Invoice', 'Pemilik', 'GMV', 'Komisi Platform', 'Pendapatan Pemilik', 'Status Refund'];
    }

    public function title(): string
    {
        return 'Transaksi';
    }
}
