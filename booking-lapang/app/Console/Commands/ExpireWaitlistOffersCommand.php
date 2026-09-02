<?php

namespace App\Console\Commands;

use App\Models\Waitlist;
use App\Services\WaitlistService;
use Illuminate\Console\Command;

class ExpireWaitlistOffersCommand extends Command
{
    protected $signature = 'waitlist:expire-offers';

    protected $description = 'Kadaluarsakan tawaran waitlist yang lewat 15 menit';

    public function handle(WaitlistService $waitlistService): void
    {
        $kadaluarsa = Waitlist::where('status', 'ditawarkan')
            ->where('ditawarkan_pada', '<=', now()->subMinutes(15))
            ->get();

        foreach ($kadaluarsa as $item) {
            $item->update(['status' => 'kadaluarsa']);

            $waitlistService->prosesAntrian(
                $item->lapangan_id,
                $item->tanggal_booking,
                $item->jam_mulai,
                $item->jam_selesai
            );
        }
    }
}