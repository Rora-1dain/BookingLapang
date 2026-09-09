<?php

namespace App\Services;

use App\Models\User;
use App\Notifications\VerifikasiDiterima;
use App\Notifications\VerifikasiDitolak;
use Illuminate\Support\Facades\Storage;
use Exception;

class VerifikasiService
{
    public function ajukanVerifikasi(User $pemilik, $fileDokumen): void
    {
        if ($pemilik->status_verifikasi === 'menunggu') {
            throw new Exception('Pengajuan verifikasi Anda masih dalam peninjauan.');
        }

        $path = $fileDokumen->store('dokumen-identitas', 'local');

        $pemilik->update([
            'status_verifikasi' => 'menunggu',
            'path_dokumen_identitas' => $path,
        ]);
    }

    public function tinjauVerifikasi(User $pemilik, bool $disetujui, ?string $catatan): void
    {
        $pemilik->update([
            'status_verifikasi' => $disetujui ? 'terverifikasi' : 'ditolak',
            'catatan_verifikasi' => $catatan,
        ]);

        $disetujui
            ? $pemilik->notify(new VerifikasiDiterima())
            : $pemilik->notify(new VerifikasiDitolak($catatan));
    }
}