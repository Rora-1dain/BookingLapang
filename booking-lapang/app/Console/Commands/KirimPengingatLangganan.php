<?php

namespace App\Console\Commands;

use App\Models\LanggananUser;
use App\Notifications\PengingatLanggananBerakhir;
use Illuminate\Console\Command;

class KirimPengingatLangganan extends Command
{
    protected $signature = 'langganan:kirim-pengingat';

    protected $description = 'Kirim notifikasi H-3 sebelum langganan aktif berakhir';

    public function handle(): void
    {
        $target = now()->addDays(3)->toDateString();

        $langgananList = LanggananUser::where('status', 'aktif')
            ->whereDate('tanggal_berakhir', $target)
            ->with(['paket', 'user'])
            ->get();

        foreach ($langgananList as $langganan) {
            $langganan->user->notify(new PengingatLanggananBerakhir($langganan));
        }

        $this->info("{$langgananList->count()} pengingat terkirim.");
    }
}