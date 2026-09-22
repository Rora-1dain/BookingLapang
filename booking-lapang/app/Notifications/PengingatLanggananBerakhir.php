<?php

namespace App\Notifications;

use App\Models\LanggananUser;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PengingatLanggananBerakhir extends Notification
{
    use Queueable;

    public function __construct(public LanggananUser $langganan) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'judul' => 'Langganan Anda Akan Berakhir',
            'pesan' => "Paket {$this->langganan->paket->nama} Anda berakhir pada "
                .$this->langganan->tanggal_berakhir->format('d M Y')
                .'. Perpanjang sekarang biar benefit tetap aktif.',
            'langganan_id' => $this->langganan->id,
        ];
    }
}
