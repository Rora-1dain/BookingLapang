<?php

namespace App\Http\Controllers;

use App\Models\NotificationPreference;
use Illuminate\View\View;

/**
 * Halaman pengaturan notifikasi. Hanya menampilkan form (GET).
 * Penyimpanan memakai route master: PUT notifikasi.preferensi.update
 * (NotificationPreferenceController@update).
 */
class PengaturanNotifikasiController extends Controller
{
    /**
     * Tipe yang boleh diatur user: [label, deskripsi, default email, default in-app].
     * Default HARUS sama dengan defaultChannels() di Notification terkait.
     * Tipe kritikal (verifikasi, ulasan buruk, refund gagal) sengaja tidak ada di sini.
     */
    public const TIPE = [
        'booking' => ['Booking', 'Konfirmasi booking kamu', true, true],
        'lapangan' => ['Lapangan', 'Lapangan disetujui/ditolak (pemilik), lapangan baru menunggu persetujuan (admin)', false, true],
        'chat' => ['Chat', 'Pesan baru dari pemilik lapangan atau pemesan', false, true],
        'payout' => ['Payout', 'Payout selesai diproses (pemilik)', false, true],
        'waitlist' => ['Waitlist', 'Slot yang kamu tunggu sudah tersedia', true, true],
    ];

    public function edit(): View
    {
        $tersimpan = NotificationPreference::where('user_id', auth()->id())
            ->get()
            ->keyBy('tipe_notifikasi');

        $preferensi = [];
        foreach (self::TIPE as $kunci => [, , $emailDefault, $databaseDefault]) {
            $baris = $tersimpan->get($kunci);
            $preferensi[$kunci] = [
                'email' => $baris ? $baris->email_aktif : $emailDefault,
                'database' => $baris ? $baris->database_aktif : $databaseDefault,
            ];
        }

        return view('pengaturan.notifikasi', [
            'tipe' => self::TIPE,
            'preferensi' => $preferensi,
        ]);
    }
}