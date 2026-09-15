<?php

namespace App\Http\Controllers;

use App\Models\Percakapan;
use App\Services\ChatService;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function __construct(protected ChatService $chatService) {}

    // Sisi pemesan
    public function bukaChatPemesan(int $lapanganId)
    {
        $percakapan = $this->chatService->mulaiAtauLanjutkan(auth()->id(), $lapanganId);
        $this->chatService->tandaiDibaca($percakapan, auth()->id());

        $lawanNama = $percakapan->pemilik->name;

        return view('booking.chat', compact('percakapan', 'lawanNama'));
    }

    // Sisi pemilik
    public function bukaChatPemilik(Percakapan $percakapan)
    {
        $this->authorize('view', $percakapan); // opsional, kalo ada policy
        $this->chatService->tandaiDibaca($percakapan, auth()->id());

        $lawanNama = $percakapan->user->name;

        return view('pemilik.chat.index', compact('percakapan', 'lawanNama'));
    }

    // Kirim pesan (dipake 2 sisi)
    public function kirim(Request $request, Percakapan $percakapan)
{
    $request->validate(['isi' => 'required|string|max:1000']);

    $pesan = $this->chatService->kirimPesan($percakapan, auth()->id(), $request->isi);

    return response()->json($pesan);
}
}