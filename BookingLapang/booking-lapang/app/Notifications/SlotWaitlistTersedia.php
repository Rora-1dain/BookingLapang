<?php

// app/Notifications/SlotWaitlistTersedia.php

namespace App\Notifications;

use App\Models\Waitlist;
use App\Traits\ChannelSesuaiPreferensi;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SlotWaitlistTersedia extends Notification implements ShouldQueue
{
    use Queueable, ChannelSesuaiPreferensi;

    protected string $tipeNotifikasi = 'waitlist';
    protected bool $adalahKritikal = false;

    public function __construct(public Waitlist $waitlist) {}

    public function via($notifiable): array
    {
        return $this->channelSesuaiPreferensi($notifiable);
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Slot Lapangan Tersedia — Segera Booking!')
            ->line('Slot yang Anda tunggu sekarang tersedia.')
            ->line('Tanggal: '.$this->waitlist->tanggal_booking)
            ->line('Jam: '.$this->waitlist->jam_mulai.' - '.$this->waitlist->jam_selesai)
            ->line('Anda punya waktu 15 menit untuk menyelesaikan booking sebelum slot ditawarkan ke antrian berikutnya.')
            ->action('Booking Sekarang', url('/booking/create'))
            ->line('Jangan sampai terlewat!');
    }

    public function toArray($notifiable): array
    {
        return [
            'waitlist_id' => $this->waitlist->id,
            'pesan' => "Slot lapangan yang Anda tunggu tersedia! Tanggal: {$this->waitlist->tanggal_booking}, Jam: {$this->waitlist->jam_mulai} - {$this->waitlist->jam_selesai}",
        ];
    }
}
