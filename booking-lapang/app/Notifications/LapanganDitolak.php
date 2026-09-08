<?php

namespace App\Notifications;

use App\Models\Lapangan;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class LapanganDitolak extends Notification
{
    use Queueable;

    public function __construct(public Lapangan $lapangan, public string $alasan) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'lapangan_id' => $this->lapangan->id,
            'alasan' => $this->alasan,
            'pesan' => "Lapangan {$this->lapangan->nama_lapangan} ditolak: {$this->alasan}",
        ];
    }
}