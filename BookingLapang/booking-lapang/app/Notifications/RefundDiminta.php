<?php

namespace App\Notifications;

use App\Models\Booking;
use App\Traits\ChannelSesuaiPreferensi;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RefundDiminta extends Notification
{
    use Queueable, ChannelSesuaiPreferensi;

    protected string $tipeNotifikasi = 'refund';

    /**
     * Bukan kritikal — cukup notifikasi in-app (database) untuk admin,
     * supaya tidak membanjiri email admin tiap ada pengajuan refund.
     */
    protected bool $adalahKritikal = false;

    public function __construct(public Booking $booking) {}

    public function via(object $notifiable): array
    {
        return $this->channelSesuaiPreferensi($notifiable);
    }

    protected function defaultChannels(): array
    {
        return ['database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Pengajuan Refund Baru')
            ->line('User '.$this->booking->user?->name.' mengajukan refund untuk booking #'.$this->booking->id.'.')
            ->line('Alasan: '.$this->booking->alasan_pembatalan)
            ->action('Tinjau Sekarang', url('/admin/refund'));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'booking_id' => $this->booking->id,
            'pesan' => 'Pengajuan refund baru untuk booking #'.$this->booking->id.'.',
        ];
    }
}
