<?php
// app/Notifications/UlasanBurukDiterima.php

namespace App\Notifications;

use App\Models\Ulasan;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Contracts\Queue\ShouldQueue;

class UlasanBurukDiterima extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Ulasan $ulasan)
    {
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $booking = $this->ulasan->booking;

        return (new MailMessage)
            ->subject('Ulasan Buruk Diterima — Rating ' . $this->ulasan->rating)
            ->line('Ada ulasan baru dgn rating rendah, mohon ditinjau.')
            ->line('Rating: ' . $this->ulasan->rating . '/5')
            ->line('Komentar: ' . ($this->ulasan->komentar ?? '-'))
            ->line('Booking ID: ' . $booking->id)
            ->action('Lihat Ulasan', url('/admin/ulasans/' . $this->ulasan->id))
            ->line('Segera ditindaklanjuti.');
    }
}