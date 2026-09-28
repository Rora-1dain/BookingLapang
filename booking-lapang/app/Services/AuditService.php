<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;

// Satu-satunya pintu untuk menulis audit log, supaya formatnya seragam di
// seluruh aplikasi (web maupun API). Jangan memanggil AuditLog::create() langsung.
class AuditService
{
    // Kunci yang tidak boleh masuk log dalam kondisi apa pun. Log yang berisi
    // rahasia justru menjadi celah keamanan baru — dibuang otomatis di sini
    // sebagai jaring pengaman, walaupun pemanggil sudah seharusnya tidak mengirimnya.
    private const KUNCI_RAHASIA = [
        'password',
        'password_confirmation',
        'token',
        'remember_token',
        'secret',
        'api_key',
        'card_number',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'path_dokumen_identitas',
        'dokumen',
    ];

    public function catat(string $aksi, ?Model $objek = null, ?array $sebelum = null, ?array $sesudah = null): AuditLog
    {
        return AuditLog::create([
            'user_id' => auth()->id(),
            'aksi' => $aksi,
            'objek_type' => $objek ? get_class($objek) : null,
            'objek_id' => $objek?->getKey(),
            'data_sebelum' => $this->bersihkan($sebelum),
            'data_sesudah' => $this->bersihkan($sesudah),
            'ip_address' => request()->ip(),
            'user_agent' => substr((string) request()->userAgent(), 0, 255),
            // diisi dari Laravel (bukan default DB) supaya zona waktunya sama
            // dengan created_at di tabel lain, dan tampil benar di halaman admin.
            'dicatat_pada' => now(),
        ]);
    }

    private function bersihkan(?array $data): ?array
    {
        if ($data === null) {
            return null;
        }

        $hasil = [];
        foreach ($data as $kunci => $nilai) {
            if (in_array(strtolower((string) $kunci), self::KUNCI_RAHASIA, true)) {
                continue;
            }
            $hasil[$kunci] = is_array($nilai) ? $this->bersihkan($nilai) : $nilai;
        }

        return $hasil;
    }
}
