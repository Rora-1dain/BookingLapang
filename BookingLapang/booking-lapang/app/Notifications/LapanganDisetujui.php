<?php

namespace App\Notifications;

use App\Models\Lapangan;
use App\Notifications\Concerns\MenghormatiPreferensi;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class LapanganDisetujui extends Notification
{
    use Queueable, MenghormatiPreferensi;

    public function __construct(public Lapangan $lapangan) {}

    public function via(object $notifiable): array
    {
        return $this->channelSesuaiPreferensi($notifiable, 'lapangan_status');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'lapangan_id' => $this->lapangan->id,
            'pesan' => "Lapangan {$this->lapangan->nama_lapangan} telah disetujui.",
        ];
    }
}