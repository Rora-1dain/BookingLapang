<?php

namespace App\Notifications;

use App\Traits\ChannelSesuaiPreferensi;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VerifikasiDiterima extends Notification
{
    use Queueable, ChannelSesuaiPreferensi;

    protected string $tipeNotifikasi = 'verifikasi';

    /**
     * Verifikasi diterima dianggap KRITIKAL — user harus tahu
     * bahwa akun mereka sekarang bisa mengajukan lapangan.
     */
    protected bool $adalahKritikal = true;

    public function via(object $notifiable): array
    {
        return $this->channelSesuaiPreferensi($notifiable);
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Verifikasi Identitas Anda Diterima')
            ->greeting('Selamat, '.$notifiable->name.'!')
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