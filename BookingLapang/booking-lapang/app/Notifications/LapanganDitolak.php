<?php

namespace App\Notifications;

use App\Models\Lapangan;
use App\Traits\ChannelSesuaiPreferensi;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class LapanganDitolak extends Notification
{
    use Queueable, ChannelSesuaiPreferensi;

    protected string $tipeNotifikasi = 'lapangan';
    protected bool $adalahKritikal = false;

    public function __construct(public Lapangan $lapangan, public string $alasan) {}

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
            ->subject('Lapangan Anda Ditolak')
            ->greeting('Halo '.$notifiable->name.',')
            ->line("Lapangan {$this->lapangan->nama_lapangan} ditolak.")
            ->line("Alasan: {$this->alasan}")
            ->action('Ajukan Ulang', url('/pemilik/lapangan'))
            ->line('Anda bisa memperbaiki dan mengajukan ulang.');
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