<?php

namespace App\Notifications;

use App\Models\Lapangan;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class LapanganMenungguPersetujuan extends Notification
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
            'nama_lapangan' => $this->lapangan->nama_lapangan,
            'pesan' => "Lapangan baru menunggu persetujuan: {$this->lapangan->nama_lapangan}",
        ];
    }
}