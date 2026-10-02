<?php

namespace App\Notifications;

use App\Models\Booking;
use App\Notifications\Concerns\MenghormatiPreferensi;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/** Notifikasi KRITIKAL: selalu lewat mail, preferensi user diabaikan. */
class RefundGagal extends Notification
{
    use Queueable, MenghormatiPreferensi;

    public function __construct(public Booking $booking) {}

    public function via($notifiable): array
    {
        return $this->channelSesuaiPreferensi($notifiable, 'refund_gagal');
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Refund Gagal Diproses')
            ->greeting('Halo '.$notifiable->name.',')
            ->line('Refund untuk booking #'.$this->booking->id.' gagal diproses.')
            ->line('Tim kami akan menindaklanjuti. Hubungi admin bila perlu bantuan segera.');
    }
}