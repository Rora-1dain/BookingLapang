<?php

namespace App\Events;

use App\Models\Pesan;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PesanDikirim implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Pesan $pesan)
    {
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('percakapan.' . $this->pesan->percakapan_id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'PesanDikirim';
    }

    public function broadcastWith(): array
    {
        return [
            'id'             => $this->pesan->id,
            'percakapan_id'  => $this->pesan->percakapan_id,
            'pengirim_id'    => $this->pesan->pengirim_id,
            'isi'            => $this->pesan->isi,
            'dibaca_pada'    => $this->pesan->dibaca_pada,
            'created_at'     => $this->pesan->created_at,
        ];
    }
}