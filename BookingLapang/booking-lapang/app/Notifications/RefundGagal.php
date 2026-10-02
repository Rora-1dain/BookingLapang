<?php

namespace App\Notifications;

use App\Models\Booking;
use App\Traits\ChannelSesuaiPreferensi;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RefundGagal extends Notification
{
    use Queueable, ChannelSesuaiPreferensi;

    protected string $tipeNotifikasi = 'refund';

    /**
     * Refund gagal dianggap KRITIKAL — user harus tahu uangnya tertahan,
     * jadi selalu lewat email apa pun preferensinya.
     */
    protected bool $adalahKritikal = true;

    public function __construct(public Booking $booking) {}

    public function via($notifiable): array
    {
        return $this->channelSesuaiPreferensi($notifiable);
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Refund Gagal Diproses')
            ->greeting('Halo '.$notifiable->name.',')
            ->line('Refund untuk booking #'.$this->booking->id.' gagal diproses.')
            ->line('Tim kami akan menindaklanjuti. Hubungi admin bila perlu bantuan segera.');
    }

    // Wajib ada: kritikal ikut channel 'database'.
    public function toArray($notifiable): array
    {
        return [
            'booking_id' => $this->booking->id,
            'pesan' => 'Refund untuk booking #'.$this->booking->id.' gagal diproses.',
        ];
    }
}