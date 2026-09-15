<?php

namespace App\Notifications;

use App\Models\Pesan;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PesanBaruDiterima extends Notification
{
    use Queueable;

    public function __construct(public Pesan $pesan)
    {
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'percakapan_id' => $this->pesan->percakapan_id,
            'pengirim_id'   => $this->pesan->pengirim_id,
            'isi'           => $this->pesan->isi,
        ];
    }
}