<?php

namespace App\Http\Controllers;

use App\Models\NotificationPreference;
use Illuminate\Http\Request;

class NotificationPreferenceController extends Controller
{
    /**
     * Simpan/update preferensi notifikasi user.
     *
     * Request body diharapkan berisi array 'preferensi' dengan format:
     * [
     *     'booking'    => ['email' => true,  'database' => true],
     *     'chat'       => ['email' => false, 'database' => true],
     *     'lapangan'   => ['email' => true,  'database' => true],
     *     ...
     * ]
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'preferensi' => 'required|array',
            'preferensi.*.email' => 'required|boolean',
            'preferensi.*.database' => 'required|boolean',
        ]);

        $userId = auth()->id();

        foreach ($validated['preferensi'] as $tipe => $setting) {
            NotificationPreference::updateOrCreate(
                [
                    'user_id' => $userId,
                    'tipe_notifikasi' => $tipe,
                ],
                [
                    'email_aktif' => $setting['email'],
                    'database_aktif' => $setting['database'],
                ]
            );
        }

        return back()->with('success', 'Preferensi notifikasi berhasil disimpan.');
    }

    /**
     * Ambil preferensi notifikasi user saat ini (untuk API / AJAX).
     */
    public function show()
    {
        $preferensi = NotificationPreference::where('user_id', auth()->id())
            ->pluck('email_aktif', 'tipe_notifikasi')
            ->toArray();

        return response()->json($preferensi);
    }
}
