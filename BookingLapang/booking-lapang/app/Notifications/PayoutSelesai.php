<?php

namespace App\Notifications;

use App\Models\Payout;
use App\Traits\ChannelSesuaiPreferensi;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PayoutSelesai extends Notification
{
    use Queueable, ChannelSesuaiPreferensi;

    protected string $tipeNotifikasi = 'payout';
    protected bool $adalahKritikal = false;

    public function __construct(public Payout $payout) {}

    public function via(object $notifiable): array
    {
        return $this->channelSesuaiPreferensi($notifiable);
    }

    protected function defaultChannels(): array
    {
        return ['database'];
    }

    public function toMail(object $notifiable): \Illuminate\Notifications\Messages\MailMessage
    {
        return (new \Illuminate\Notifications\Messages\MailMessage)
            ->subject('Payout Anda Telah Selesai Diproses')
            ->greeting('Halo '.$notifiable->name.',')
            ->line("Payout periode {$this->payout->periode_mulai} - {$this->payout->periode_selesai} telah selesai.")
            ->line('Total: Rp'.number_format($this->payout->total_nominal, 0, ',', '.'))
            ->line('Dana akan segera masuk ke rekening Anda.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'payout_id' => $this->payout->id,
            'periode_mulai' => $this->payout->periode_mulai,
            'periode_selesai' => $this->payout->periode_selesai,
            'total_nominal' => $this->payout->total_nominal,
            'pesan' => "Payout periode {$this->payout->periode_mulai} - {$this->payout->periode_selesai} sebesar Rp"
                .number_format($this->payout->total_nominal, 0, ',', '.').' telah selesai diproses.',
        ];
    }
}