<?php
// app/Notifications/SlotWaitlistTersedia.php

namespace App\Notifications;

use App\Models\Waitlist;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Contracts\Queue\ShouldQueue;

class SlotWaitlistTersedia extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Waitlist $waitlist)
    {
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Slot Lapangan Tersedia — Segera Booking!')
            ->line('Slot yang Anda tunggu sekarang tersedia.')
            ->line('Tanggal: ' . $this->waitlist->tanggal_booking)
            ->line('Jam: ' . $this->waitlist->jam_mulai . ' - ' . $this->waitlist->jam_selesai)
            ->line('Anda punya waktu 15 menit untuk menyelesaikan booking sebelum slot ditawarkan ke antrian berikutnya.')
            ->action('Booking Sekarang', url('/booking/create'))
            ->line('Jangan sampai terlewat!');
    }
}