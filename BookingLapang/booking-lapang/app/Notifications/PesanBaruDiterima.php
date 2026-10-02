<?php

namespace App\Notifications;

use App\Models\Pesan;
use App\Traits\ChannelSesuaiPreferensi;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PesanBaruDiterima extends Notification
{
    use Queueable, ChannelSesuaiPreferensi;

    protected string $tipeNotifikasi = 'chat';
    protected bool $adalahKritikal = false;

    public function __construct(public Pesan $pesan) {}

    public function via($notifiable): array
    {
        return $this->channelSesuaiPreferensi($notifiable);
    }

    protected function defaultChannels(): array
    {
        return ['database'];
    }

    public function toMail($notifiable): \Illuminate\Notifications\Messages\MailMessage
    {
        return (new \Illuminate\Notifications\Messages\MailMessage)
            ->subject('Anda Menerima Pesan Baru')
            ->greeting('Halo '.$notifiable->name.',')
            ->line('Anda mendapat pesan baru di chat.')
            ->line('Isi: '.\Illuminate\Support\Str::limit($this->pesan->isi, 100))
            ->action('Buka Chat', url('/chat/'.$this->pesan->percakapan_id));
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
