<?php

namespace App\Notifications;

use App\Models\Lapangan;
use App\Traits\ChannelSesuaiPreferensi;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class LapanganMenungguPersetujuan extends Notification
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
            ->subject('Lapangan Baru Menunggu Persetujuan')
            ->line("Lapangan baru menunggu persetujuan: {$this->lapangan->nama_lapangan}")
            ->action('Tinjau Sekarang', url('/admin/lapangan/approval'));
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
