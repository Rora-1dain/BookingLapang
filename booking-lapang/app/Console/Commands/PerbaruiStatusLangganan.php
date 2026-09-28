<?php

namespace App\Console\Commands;

use App\Models\LanggananUser;
use Illuminate\Console\Command;

class PerbaruiStatusLangganan extends Command
{
    protected $signature = 'langganan:perbarui-status';

    protected $description = 'Ubah status langganan yang sudah lewat masa berlaku menjadi berakhir';

    public function handle(): void
    {
        $jumlah = LanggananUser::where('status', 'aktif')
            ->where('tanggal_berakhir', '<', now())
            ->update(['status' => 'berakhir']);

        $this->info("{$jumlah} langganan diperbarui menjadi berakhir.");
    }
}
