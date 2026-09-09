<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class VerifikasiDitolak extends Notification
{
    use Queueable;

    public function __construct(public ?string $catatan) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $message = (new MailMessage)
            ->subject('Verifikasi Identitas Anda Ditolak')
            ->greeting('Halo, ' . $notifiable->name)
            ->line('Mohon maaf, pengajuan verifikasi identitas Anda belum bisa kami setujui.');

        if ($this->catatan) {
            $message->line('Alasan: ' . $this->catatan);
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