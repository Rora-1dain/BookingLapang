<?php

namespace App\Notifications;

use App\Models\Booking;
use App\Notifications\Concerns\MenghormatiPreferensi;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingDikonfirmasi extends Notification implements ShouldQueue
{
    use Queueable, MenghormatiPreferensi;

    public function __construct(protected Booking $booking) {}

    public function via($notifiable): array
    {
        return $this->channelSesuaiPreferensi($notifiable, 'booking_confirmed');
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Booking Anda Telah Dikonfirmasi')
            ->greeting('Halo '.$notifiable->name.',')
            ->line('Booking lapang Anda telah dikonfirmasi oleh admin.')
            ->line('Lapangan: '.$this->booking->lapangan->nama_lapangan)
            ->line('Tanggal: '.$this->booking->tanggal_booking->format('d-m-Y'))
            ->line('Jam: '.$this->booking->jam_mulai.' - '.$this->booking->jam_selesai)
            ->line('Total: Rp'.number_format($this->booking->total_harga))
            ->line('Terima kasih telah menggunakan layanan kami.');
    }

    public function toArray($notifiable): array
    {
        return [
            'booking_id' => $this->booking->id,
            'pesan' => 'Booking '.$this->booking->lapangan->nama_lapangan.' tanggal '
                .$this->booking->tanggal_booking->format('d-m-Y').' telah dikonfirmasi.',
        ];
    }
}