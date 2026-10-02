<?php

namespace App\Http\Controllers\Api;

use App\Exports\LedgerExport;
use App\Http\Controllers\Controller;
use App\Services\AuditService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class AdminLedgerExportController extends Controller
{
    public function export(Request $request, AuditService $audit)
    {
        // TODO: setelah bagian Ardan digabung, ganti dengan:
        // $this->authorize('laporan.export');

        $data = $request->validate([
            'mulai'   => ['required', 'date'],
            'selesai' => ['required', 'date', 'after_or_equal:mulai'],
        ]);

        $mulai = Carbon::parse($data['mulai']);
        $selesai = Carbon::parse($data['selesai']);

        $audit->catat('export_ledger', null, null, [
            'mulai'   => $mulai->toDateString(),
            'selesai' => $selesai->toDateString(),
        ]);

        $nama = 'ledger_' . $mulai->format('Ymd') . '_' . $selesai->format('Ymd') . '.xlsx';

        return Excel::download(new LedgerExport($mulai, $selesai), $nama);
    }
}