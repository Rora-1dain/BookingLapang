<?php

namespace App\Exports;

use App\Services\AccountingReportService;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\Export;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class LedgerExport implements Export, WithMultipleSheets
{
    public function __construct(protected Carbon $mulai, protected Carbon $selesai) {}

    public function sheets(): array
    {
        $service = app(AccountingReportService::class);

        return [
            new TransaksiSheetExport($service->ledgerTransaksi($this->mulai, $this->selesai)),
            new RingkasanSheetExport($service->ringkasan($this->mulai, $this->selesai)),
        ];
    }
}