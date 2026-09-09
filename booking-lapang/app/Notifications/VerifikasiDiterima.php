<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class VerifikasiDiterima extends Notification
{
    use Queueable;

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Verifikasi Identitas Anda Diterima')
            ->greeting('Selamat, ' . $notifiable->name . '!')
            ->line('Dokumen identitas Anda telah diverifikasi dan disetujui.')
            ->line('Anda sekarang bisa mengajukan lapangan baru.')
            ->action('Ajukan Lapangan', url('/pemilik/lapangan/create'))
            ->line('Terima kasih telah bergabung dengan platform kami.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'pesan' => 'Verifikasi identitas Anda telah diterima.',
        ];
    }
}