<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;

// Hanya method index(): audit log append-only, jadi controller ini sengaja
// tidak punya store/update/destroy.
class AdminAuditController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->validate([
            'pelaku' => 'nullable|string',
            'aksi' => 'nullable|string|max:100',
            'dari' => 'nullable|date',
            'sampai' => 'nullable|date|after_or_equal:dari',
        ]);

        $logs = AuditLog::with('pelaku')
            ->filter($filter)
            ->orderByDesc('dicatat_pada')
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        // Pilihan dropdown hanya dari pelaku & aksi yang benar-benar ada di log
        $daftarPelaku = User::whereIn(
            'id',
            AuditLog::query()->whereNotNull('user_id')->select('user_id')->distinct()
        )->orderBy('name')->get(['id', 'name']);

        $daftarAksi = AuditLog::query()->select('aksi')->distinct()->orderBy('aksi')->pluck('aksi');

        return view('admin.audit.index', compact('logs', 'filter', 'daftarPelaku', 'daftarAksi'));
    }
}
