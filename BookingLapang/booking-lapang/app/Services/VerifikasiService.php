<?php

namespace App\Services;

use App\Models\User;
use App\Notifications\VerifikasiDiterima;
use App\Notifications\VerifikasiDitolak;
use Exception;

class VerifikasiService
{
    public function ajukanVerifikasi(User $pemilik, $fileDokumen): void
    {
        if ($pemilik->status_verifikasi === 'menunggu') {
            throw new Exception('Pengajuan verifikasi Anda masih dalam peninjauan.');
        }

        $base64 = base64_encode(file_get_contents($fileDokumen->getRealPath()));
        $mimeType = $fileDokumen->getMimeType();
        $dataUri = "data:{$mimeType};base64,{$base64}";

        $pemilik->update([
            'status_verifikasi' => 'menunggu',
            'path_dokumen_identitas' => $dataUri,
        ]);
    }

    public function tinjauVerifikasi(User $pemilik, bool $disetujui, ?string $catatan): void
    {
        $sebelum = ['status_verifikasi' => $pemilik->status_verifikasi]; // [AUDIT]

        $pemilik->update([
            'status_verifikasi' => $disetujui ? 'terverifikasi' : 'ditolak',
            'catatan_verifikasi' => $catatan,
        ]);

        // [AUDIT] hanya status & catatan admin. JANGAN pernah menyertakan
        // path_dokumen_identitas (isinya data URI dokumen identitas).
        app(AuditService::class)->catat(
            $disetujui ? 'verifikasi.disetujui' : 'verifikasi.ditolak',
            $pemilik,
            $sebelum,
            ['status_verifikasi' => $pemilik->status_verifikasi, 'catatan' => $catatan]
        );

        $disetujui
            ? $pemilik->notify(new VerifikasiDiterima)
            : $pemilik->notify(new VerifikasiDitolak($catatan));
    }
}
