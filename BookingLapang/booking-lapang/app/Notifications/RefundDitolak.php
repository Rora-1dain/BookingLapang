<?php

namespace App\Notifications;

use App\Models\Booking;
use App\Traits\ChannelSesuaiPreferensi;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RefundDitolak extends Notification
{
    use Queueable, ChannelSesuaiPreferensi;

    protected string $tipeNotifikasi = 'refund';

    protected bool $adalahKritikal = false;

    public function __construct(public Booking $booking) {}

    public function via(object $notifiable): array
    {
        return $this->channelSesuaiPreferensi($notifiable);
    }

    protected function defaultChannels(): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Pengajuan Refund Ditolak')
            ->greeting('Halo '.$notifiable->name.',')
            ->line('Pengajuan refund untuk booking #'.$this->booking->id.' ditolak.')
            ->line($this->booking->catatan_refund ? 'Catatan admin: '.$this->booking->catatan_refund : 'Hubungi admin bila perlu penjelasan.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'booking_id' => $this->booking->id,
            'pesan' => 'Pengajuan refund untuk booking #'.$this->booking->id.' ditolak.',
        ];
    }
}
