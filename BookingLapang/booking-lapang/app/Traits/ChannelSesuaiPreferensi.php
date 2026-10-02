<?php

namespace App\Traits;

use App\Models\NotificationPreference;

/**
 * Trait ChannelSesuaiPreferensi
 *
 * Digunakan di semua Notification class agar channel pengiriman
 * (mail/database) mengikuti preferensi user, KECUALI untuk notifikasi
 * bertipe kritikal yang selalu dikirim via email.
 *
 * Cara pakai di Notification class:
 *   use \App\Traits\ChannelSesuaiPreferensi;
 *
 *   // Override property untuk konfigurasi:
 *   protected string $tipeNotifikasi = 'booking';  // tipe preferensi
 *   protected bool   $adalahKritikal = false;       // true = bypass preferensi, selalu kirim email
 *
 *   public function via($notifiable): array
 *   {
 *       return $this->channelSesuaiPreferensi($notifiable);
 *   }
 */
trait ChannelSesuaiPreferensi
{
    /**
     * Tentukan channel pengiriman berdasarkan preferensi user.
     *
     * - Jika notifikasi bersifat kritikal, email SELALU dikirim + database.
     * - Jika user belum punya preferensi, gunakan default (semua aktif).
     * - Jika user punya preferensi, ikuti setting-nya.
     */
    protected function channelSesuaiPreferensi(object $notifiable): array
    {
        $tipe = property_exists($this, 'tipeNotifikasi') ? $this->tipeNotifikasi : 'umum';
        $kritikal = property_exists($this, 'adalahKritikal') ? $this->adalahKritikal : false;

        // Notifikasi kritikal: SELALU kirim email + database, abaikan preferensi
        if ($kritikal) {
            return ['mail', 'database'];
        }

        // Ambil preferensi user (jika ada)
        $preferensi = null;
        if (method_exists($notifiable, 'getKey')) {
            $preferensi = NotificationPreference::where('user_id', $notifiable->getKey())
                ->where('tipe_notifikasi', $tipe)
                ->first();
        }

        // Jika belum ada preferensi tersimpan, gunakan default channels
        if (! $preferensi) {
            return $this->defaultChannels();
        }

        // Bangun channel list berdasarkan preferensi
        $channels = [];

        if ($preferensi->email_aktif) {
            $channels[] = 'mail';
        }

        if ($preferensi->database_aktif) {
            $channels[] = 'database';
        }

        // Fallback: jika user matikan semua, minimal tetap kirim database (in-app)
        return $channels ?: ['database'];
    }

    /**
     * Default channels jika user belum punya preferensi.
     * Override di masing-masing notification class jika default berbeda.
     */
    protected function defaultChannels(): array
    {
        return ['mail', 'database'];
    }
}
