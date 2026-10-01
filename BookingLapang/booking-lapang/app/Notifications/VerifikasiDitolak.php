<?php

namespace App\Notifications;

use App\Traits\ChannelSesuaiPreferensi;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VerifikasiDitolak extends Notification
{
    use Queueable, ChannelSesuaiPreferensi;

    protected string $tipeNotifikasi = 'verifikasi';

    /**
     * Verifikasi ditolak dianggap KRITIKAL — user harus tahu
     * agar bisa memperbaiki dan mengajukan ulang.
     */
    protected bool $adalahKritikal = true;

    public function __construct(public ?string $catatan) {}

    public function via(object $notifiable): array
    {
        return $this->channelSesuaiPreferensi($notifiable);
    }

    public function toMail(object $notifiable): MailMessage
    {
        $message = (new MailMessage)
            ->subject('Verifikasi Identitas Anda Ditolak')
            ->greeting('Halo, '.$notifiable->name)
            ->line('Mohon maaf, pengajuan verifikasi identitas Anda belum bisa kami setujui.');

        if ($this->catatan) {
            $message->line('Alasan: '.$this->catatan);
        }

        return $message
            ->line('Anda bisa mengajukan ulang dokumen yang lebih jelas/sesuai melalui halaman profil.')
            ->action('Ajukan Ulang', url('/profile'));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'pesan' => 'Verifikasi identitas Anda ditolak.',
            'catatan' => $this->catatan,
        ];
    }
}
