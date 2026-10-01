<?php

namespace App\Support;

use App\Models\User;

/**
 * Sumber kebenaran tunggal soal jenis notifikasi: label, default channel,
 * mana yang transaksional (minimal tetap masuk in-app), mana yang kritikal
 * (selalu lewat mail, tidak bisa diatur user).
 */
class PreferensiNotifikasi
{
    public const CHANNEL = ['mail', 'database'];

    /** kunci => [label, default channel, transaksional?] */
    public const JENIS = [
        'booking_confirmed' => ['Booking dikonfirmasi', ['mail', 'database'], true],
        'verifikasi_hasil' => ['Hasil verifikasi identitas', ['mail', 'database'], true],
        'payout_selesai' => ['Payout selesai', ['database'], true],
        'lapangan_status' => ['Status persetujuan lapangan', ['database'], true],
        'lapangan_menunggu' => ['Lapangan menunggu persetujuan (admin)', ['database'], false],
        'pesan_chat_baru' => ['Pesan chat baru', ['database'], false],
        'slot_waitlist' => ['Slot waitlist tersedia', ['mail'], false],
        'ulasan_buruk' => ['Ulasan rating rendah', ['mail'], false],
        'promo' => ['Promo & penawaran', [], false], // opt-in
    ];

    /** Selalu terkirim lewat mail, tidak muncul di halaman pengaturan. */
    public const KRITIKAL = ['refund_gagal', 'keamanan_2fa'];

    public static function kritikal(string $kunci): bool
    {
        return in_array($kunci, self::KRITIKAL, true);
    }

    public static function transaksional(string $kunci): bool
    {
        return self::JENIS[$kunci][2] ?? false;
    }

    public static function defaultUntuk(string $kunci): array
    {
        return self::JENIS[$kunci][1] ?? ['mail'];
    }

    /** Preferensi lengkap user: nilai tersimpan menimpa default per jenis. */
    public static function untuk(User $user): array
    {
        $tersimpan = $user->preferensi_notifikasi ?? [];
        $hasil = [];
        foreach (self::JENIS as $kunci => [, $default]) {
            $hasil[$kunci] = array_key_exists($kunci, $tersimpan)
                ? self::bersihkan($tersimpan[$kunci])
                : $default;
        }

        return $hasil;
    }

    /** Buang channel asing, hilangkan duplikat. */
    public static function bersihkan(mixed $channel): array
    {
        return array_values(array_unique(array_intersect((array) $channel, self::CHANNEL)));
    }
}