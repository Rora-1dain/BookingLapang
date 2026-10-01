<?php

// app/Notifications/UlasanBurukDiterima.php

namespace App\Notifications;

use App\Models\Ulasan;
use App\Notifications\Concerns\MenghormatiPreferensi;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UlasanBurukDiterima extends Notification implements ShouldQueue
{
    use Queueable, MenghormatiPreferensi;

    public function __construct(public Ulasan $ulasan) {}

    public function via($notifiable): array
    {
        return $this->channelSesuaiPreferensi($notifiable, 'ulasan_buruk');
    }

    public function toMail($notifiable): MailMessage
    {
        $booking = $this->ulasan->booking;

        return (new MailMessage)
            ->subject('Ulasan Buruk Diterima — Rating '.$this->ulasan->rating)
            ->line('Ada ulasan baru dgn rating rendah, mohon ditinjau.')
            ->line('Rating: '.$this->ulasan->rating.'/5')
            ->line('Komentar: '.($this->ulasan->komentar ?? '-'))
            ->line('Booking ID: '.$booking->id)
            ->action('Lihat Ulasan', url('/admin/ulasans/'.$this->ulasan->id))
            ->line('Segera ditindaklanjuti.');
    }

    public function toArray($notifiable): array
    {
        return [
            'ulasan_id' => $this->ulasan->id,
            'pesan' => 'Ulasan baru dengan rating '.$this->ulasan->rating.'/5 perlu ditinjau.',
        ];
    }
}