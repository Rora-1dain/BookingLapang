<?php

namespace App\Notifications\Concerns;

use App\Support\PreferensiNotifikasi;

trait MenghormatiPreferensi
{
    /**
     * Tentukan channel dari preferensi user.
     * - Kritikal: selalu ['mail'], preferensi diabaikan.
     * - Transaksional: kalau user matikan semua channel, minimal 'database'.
     * - Selain itu: ikut preferensi (boleh kosong = tidak dikirim).
     */
    protected function channelSesuaiPreferensi($notifiable, string $kunci, ?array $default = null): array
    {
        if (PreferensiNotifikasi::kritikal($kunci)) {
            return ['mail'];
        }

        $default ??= PreferensiNotifikasi::defaultUntuk($kunci);
        $tersimpan = $notifiable->preferensi_notifikasi ?? [];

        $channel = array_key_exists($kunci, $tersimpan)
            ? PreferensiNotifikasi::bersihkan($tersimpan[$kunci])
            : PreferensiNotifikasi::bersihkan($default);

        if ($channel === [] && PreferensiNotifikasi::transaksional($kunci)) {
            return ['database'];
        }

        return $channel;
    }
}