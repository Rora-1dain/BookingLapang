<?php

namespace App\Notifications;

use App\Models\Lapangan;
use App\Traits\ChannelSesuaiPreferensi;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class LapanganDisetujui extends Notification
{
    use Queueable, ChannelSesuaiPreferensi;

    protected string $tipeNotifikasi = 'lapangan';
    protected bool $adalahKritikal = false;

    public function __construct(public Lapangan $lapangan) {}

    public function via(object $notifiable): array
    {
        return $this->channelSesuaiPreferensi($notifiable);
    }

    protected function defaultChannels(): array
    {
        return ['database'];
    }

    public function toMail(object $notifiable): \Illuminate\Notifications\Messages\MailMessage
    {
        return (new \Illuminate\Notifications\Messages\MailMessage)
            ->subject('Lapangan Anda Disetujui')
            ->greeting('Halo '.$notifiable->name.',')
            ->line("Lapangan {$this->lapangan->nama_lapangan} telah disetujui dan sekarang aktif.")
            ->action('Lihat Lapangan', url('/pemilik/lapangan'))
            ->line('Terima kasih telah bergabung dengan platform kami.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'lapangan_id' => $this->lapangan->id,
            'pesan' => "Lapangan {$this->lapangan->nama_lapangan} telah disetujui.",
        ];
    }
}