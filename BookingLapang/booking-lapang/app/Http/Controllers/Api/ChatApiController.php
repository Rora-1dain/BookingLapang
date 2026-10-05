<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Percakapan;
use App\Services\ChatService;
use Exception;
use Illuminate\Http\Request;

class ChatApiController extends Controller
{
    public function index(Request $request)
    {
        $userId = $request->user()->id;

        $percakapans = Percakapan::where(function ($q) use ($userId) {
            $q->where('user_id', $userId)->orWhere('pemilik_id', $userId);
        })
            ->with(['lapangan:id,nama_lapangan,jenis', 'user:id,name', 'pemilik:id,name', 'pesanTerakhir'])
            ->latest('updated_at')
            ->get()
            ->map(fn (Percakapan $p) => $this->ringkasan($p, $userId));

        return response()->json(['data' => $percakapans]);
    }

    public function store(Request $request, ChatService $chatService)
    {
        // Percakapan dengan admin (tombol "Hubungi Admin" di halaman membership).
        if ($request->input('tujuan') === 'admin') {
            try {
                $percakapan = $chatService->mulaiAtauLanjutkanAdmin($request->user()->id);
            } catch (\Exception $e) {
                return response()->json(['message' => $e->getMessage()], 422);
            }

            return response()->json(['data' => $percakapan], 201);
        }

        $validated = $request->validate([
            'lapangan_id' => 'required|exists:lapangans,id',
        ]);

        $percakapan = $chatService->mulaiAtauLanjutkan($request->user()->id, $validated['lapangan_id']);

        return response()->json(['data' => $percakapan], 201);
    }

    public function show(Request $request, Percakapan $percakapan)
    {
        $this->pastikanBagianDariPercakapan($request, $percakapan);

        $percakapan->load(['lapangan:id,nama_lapangan,jenis', 'user:id,name', 'pemilik:id,name', 'pesans.pengirim:id,name']);

        return response()->json([
            'data' => array_merge(
                $this->ringkasan($percakapan, $request->user()->id),
                ['pesans' => $percakapan->pesans]
            ),
        ]);
    }

    public function kirimPesan(Request $request, Percakapan $percakapan, ChatService $chatService)
    {
        $this->pastikanBagianDariPercakapan($request, $percakapan);

        $validated = $request->validate([
            'isi' => 'required|string|max:1000',
        ]);

        try {
            $pesan = $chatService->kirimPesan($percakapan, $request->user()->id, $validated['isi']);

            return response()->json(['data' => $pesan], 201);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 403);
        }
    }

    public function tandaiDibaca(Request $request, Percakapan $percakapan, ChatService $chatService)
    {
        $this->pastikanBagianDariPercakapan($request, $percakapan);

        $chatService->tandaiDibaca($percakapan, $request->user()->id);

        return response()->json(['message' => 'Pesan ditandai sudah dibaca.']);
    }

    /** Bentuk ringkas percakapan dari sudut pandang $userId (lawan bicara = pihak satunya). */
    protected function ringkasan(Percakapan $p, int $userId): array
    {
        $lawan = $p->user_id === $userId ? $p->pemilik : $p->user;
        $admin = $p->tipe === 'admin';

        return [
            'id' => $p->id,
            'tipe' => $p->tipe,
            'lapangan_id' => $p->lapangan_id,
            // Percakapan admin tidak punya lapangan — pakai label khusus sebagai judul.
            'lapangan' => $admin ? 'Admin Booking Lapang' : $p->lapangan?->nama_lapangan,
            'jenis' => $p->lapangan?->jenis,
            'lawan_bicara' => $lawan?->name,
            'peran_lawan' => $admin ? 'admin' : ($p->user_id === $userId ? 'pemilik' : 'pemesan'),
            'pesan_terakhir' => $p->pesanTerakhir?->isi,
            'waktu' => ($p->pesanTerakhir?->created_at ?? $p->updated_at)?->toIso8601String(),
            'belum_dibaca' => $p->jumlahBelumDibaca($userId),
        ];
    }

    protected function pastikanBagianDariPercakapan(Request $request, Percakapan $percakapan): void
    {
        $userId = $request->user()->id;

        if ($percakapan->user_id !== $userId && $percakapan->pemilik_id !== $userId) {
            abort(403, 'Anda bukan bagian dari percakapan ini.');
        }
    }
}