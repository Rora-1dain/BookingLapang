<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\NotificationPreference;
use Illuminate\Http\Request;

class NotificationPreferenceApiController extends Controller
{
    // Tipe notifikasi yang didukung (harus sinkron dengan $tipeNotifikasi di
    // masing-masing Notification class + trait ChannelSesuaiPreferensi).
    // 'refund', 'ulasan', 'verifikasi' bersifat kritikal (selalu email),
    // tapi tetap boleh ditampilkan agar user tahu.
    public const TIPE = [
        'booking' => 'Booking & pembayaran',
        'chat' => 'Pesan chat',
        'lapangan' => 'Persetujuan lapangan',
        'payout' => 'Pencairan dana (payout)',
        'waitlist' => 'Slot waitlist tersedia',
        'refund' => 'Refund',
        'ulasan' => 'Ulasan',
        'verifikasi' => 'Verifikasi identitas',
    ];

    /**
     * GET /api/notifikasi/preferensi
     * Mengembalikan preferensi dalam bentuk map tipe => {email, database}.
     * Tipe yang belum diatur dianggap aktif (default true).
     */
    public function show(Request $request)
    {
        $tersimpan = NotificationPreference::where('user_id', $request->user()->id)->get()
            ->keyBy('tipe_notifikasi');

        $data = collect(self::TIPE)->mapWithKeys(function ($label, $tipe) use ($tersimpan) {
            $p = $tersimpan->get($tipe);

            return [$tipe => [
                'label' => $label,
                'email' => $p ? (bool) $p->email_aktif : true,
                'database' => $p ? (bool) $p->database_aktif : true,
            ]];
        })->all();

        return response()->json(['data' => $data]);
    }

    /**
     * PUT /api/notifikasi/preferensi
     * Body: { preferensi: { booking: {email, database}, ... } }
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'preferensi' => 'required|array',
            'preferensi.*.email' => 'required|boolean',
            'preferensi.*.database' => 'required|boolean',
        ]);

        $userId = $request->user()->id;

        foreach ($validated['preferensi'] as $tipe => $setting) {
            NotificationPreference::updateOrCreate(
                ['user_id' => $userId, 'tipe_notifikasi' => $tipe],
                ['email_aktif' => $setting['email'], 'database_aktif' => $setting['database']]
            );
        }

        return response()->json(['message' => 'Preferensi notifikasi berhasil disimpan.']);
    }
}
