<?php

namespace App\Notifications;

use App\Models\Lapangan;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class LapanganDisetujui extends Notification
{
    use Queueable;

    public function __construct(public Lapangan $lapangan) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'lapangan_id' => $this->lapangan->id,
            'pesan' => "Lapangan {$this->lapangan->nama_lapangan} telah disetujui.",
        ];
    }
}