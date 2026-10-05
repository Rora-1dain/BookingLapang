<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;

class AdminAuditApiController extends Controller
{
    // GET /api/admin/audit — versi JSON dari AdminAuditController (web).
    // Audit log append-only: hanya ada endpoint baca.
    public function index(Request $request)
    {
        $filter = $request->validate([
            'pelaku' => 'nullable|string',
            'aksi' => 'nullable|string|max:100',
            'dari' => 'nullable|date',
            'sampai' => 'nullable|date|after_or_equal:dari',
        ]);

        $logs = AuditLog::with('pelaku:id,name')
            ->filter($filter)
            ->orderByDesc('dicatat_pada')
            ->orderByDesc('id')
            ->paginate(20)
            ->through(fn (AuditLog $l) => [
                'id' => $l->id,
                'aksi' => $l->aksi,
                'pelaku' => $l->pelaku?->name,
                'objek_type' => $l->objek_type,
                'objek_id' => $l->objek_id,
                'data_sebelum' => $l->data_sebelum,
                'data_sesudah' => $l->data_sesudah,
                'dicatat_pada' => $l->dicatat_pada?->toIso8601String(),
            ]);

        $daftarAksi = AuditLog::query()->select('aksi')->distinct()->orderBy('aksi')->pluck('aksi');

        return response()->json([
            'data' => $logs->items(),
            'meta' => [
                'current_page' => $logs->currentPage(),
                'last_page' => $logs->lastPage(),
                'total' => $logs->total(),
            ],
            'daftar_aksi' => $daftarAksi,
        ]);
    }
}
