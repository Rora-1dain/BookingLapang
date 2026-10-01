<?php

namespace App\Notifications;

use App\Models\Lapangan;
use App\Notifications\Concerns\MenghormatiPreferensi;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class LapanganMenungguPersetujuan extends Notification
{
    use Queueable, MenghormatiPreferensi;

    public function __construct(public Lapangan $lapangan) {}

    public function via(object $notifiable): array
    {
        return $this->channelSesuaiPreferensi($notifiable, 'lapangan_menunggu');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'lapangan_id' => $this->lapangan->id,
            'nama_lapangan' => $this->lapangan->nama_lapangan,
            'pesan' => "Lapangan baru menunggu persetujuan: {$this->lapangan->nama_lapangan}",
        ];
    }
}