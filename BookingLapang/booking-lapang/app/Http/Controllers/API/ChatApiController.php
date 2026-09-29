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

        $percakapans = Percakapan::where('user_id', $userId)
            ->orWhere('pemilik_id', $userId)
            ->with(['lapangan', 'pesanTerakhir'])
            ->latest('updated_at')
            ->get()
            ->map(function (Percakapan $p) use ($userId) {
                return [
                    'id' => $p->id,
                    'lapangan' => $p->lapangan?->nama_lapangan,
                    'pesan_terakhir' => $p->pesanTerakhir?->isi,
                    'belum_dibaca' => $p->jumlahBelumDibaca($userId),
                ];
            });

        return response()->json(['data' => $percakapans]);
    }

    public function store(Request $request, ChatService $chatService)
    {
        $validated = $request->validate([
            'lapangan_id' => 'required|exists:lapangans,id',
        ]);

        $percakapan = $chatService->mulaiAtauLanjutkan($request->user()->id, $validated['lapangan_id']);

        return response()->json(['data' => $percakapan], 201);
    }

    public function show(Request $request, Percakapan $percakapan)
    {
        $this->pastikanBagianDariPercakapan($request, $percakapan);

        return response()->json([
            'data' => $percakapan->load('pesans.pengirim'),
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

    protected function pastikanBagianDariPercakapan(Request $request, Percakapan $percakapan): void
    {
        $userId = $request->user()->id;

        if ($percakapan->user_id !== $userId && $percakapan->pemilik_id !== $userId) {
            abort(403, 'Anda bukan bagian dari percakapan ini.');
        }
    }
}
