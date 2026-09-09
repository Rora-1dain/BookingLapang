<?php

namespace App\Notifications;

use App\Models\Payout;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PayoutSelesai extends Notification
{
    use Queueable;

    public function __construct(public Payout $payout) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'payout_id' => $this->payout->id,
            'periode_mulai' => $this->payout->periode_mulai,
            'periode_selesai' => $this->payout->periode_selesai,
            'total_nominal' => $this->payout->total_nominal,
            'pesan' => "Payout periode {$this->payout->periode_mulai} - {$this->payout->periode_selesai} sebesar Rp"
                . number_format($this->payout->total_nominal, 0, ',', '.') . " telah selesai diproses.",
        ];
    }
}