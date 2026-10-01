<?php

namespace App\Notifications;

use App\Models\Pesan;
use App\Notifications\Concerns\MenghormatiPreferensi;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PesanBaruDiterima extends Notification
{
    use Queueable, MenghormatiPreferensi;

    public function __construct(public Pesan $pesan) {}

    public function via($notifiable): array
    {
        return $this->channelSesuaiPreferensi($notifiable, 'pesan_chat_baru');
    }

    public function toArray($notifiable): array
    {
        return [
            'percakapan_id' => $this->pesan->percakapan_id,
            'pengirim_id' => $this->pesan->pengirim_id,
            'isi' => $this->pesan->isi,
        ];
    }
}