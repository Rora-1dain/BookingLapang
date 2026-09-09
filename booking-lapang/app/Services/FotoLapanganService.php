<?php

namespace App\Services;

use App\Models\FotoLapangan;
use App\Models\Lapangan;
use Illuminate\Support\Facades\Storage;
use Exception;

class FotoLapanganService
{
    public function unggahFoto(Lapangan $lapangan, array $files): void
    {
        $jumlahSaatIni = $lapangan->fotos()->count();

        if ($jumlahSaatIni + count($files) > 8) {
            throw new Exception('Maksimal 8 foto per lapangan.');
        }

        foreach ($files as $file) {
            if (! in_array($file->getClientOriginalExtension(), ['jpg', 'jpeg', 'png'])) {
                throw new Exception('Format file harus jpg/jpeg/png.');
            }

            if ($file->getSize() > 2 * 1024 * 1024) {
                throw new Exception('Ukuran file maksimal 2MB.');
            }

            $path = $file->store('lapangan-foto', 'public');

            FotoLapangan::create([
                'lapangan_id' => $lapangan->id,
                'path_file' => $path,
                'urutan' => $jumlahSaatIni,
                'is_utama' => $jumlahSaatIni === 0,
            ]);

            $jumlahSaatIni++;
        }
    }

    public function hapusFoto(FotoLapangan $foto): void
    {
        Storage::disk('public')->delete($foto->path_file);

        $lapanganId = $foto->lapangan_id;
        $foto->delete();

        if (! FotoLapangan::where('lapangan_id', $lapanganId)->where('is_utama', true)->exists()) {
            FotoLapangan::where('lapangan_id', $lapanganId)->oldest()->first()?->update(['is_utama' => true]);
        }
    }

    public function jadikanUtama(FotoLapangan $foto): void
    {
        FotoLapangan::where('lapangan_id', $foto->lapangan_id)->update(['is_utama' => false]);
        $foto->update(['is_utama' => true]);
    }
}
