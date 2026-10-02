<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;

/** Dikirim saat salah satu pihak membuka chat: pengirim bisa langsung lihat status "Dibaca". */
class PesanDibaca implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets;

    public function __construct(public int $percakapanId, public int $pembacaId) {}

    public function broadcastOn(): array
    {
        return [new PrivateChannel('percakapan.'.$this->percakapanId)];
    }

    public function broadcastAs(): string
    {
        return 'PesanDibaca';
    }

    public function broadcastWith(): array
    {
        return [
            'percakapan_id' => $this->percakapanId,
            'pembaca_id' => $this->pembacaId,
            'dibaca_pada' => now()->toIso8601String(),
        ];
    }
}