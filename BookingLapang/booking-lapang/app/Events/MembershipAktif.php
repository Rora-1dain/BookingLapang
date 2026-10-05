<?php

namespace App\Events;

use App\Models\LanggananUser;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MembershipAktif implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public LanggananUser $langganan) {}

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('user.'.$this->langganan->user_id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'MembershipAktif';
    }

    public function broadcastWith(): array
    {
        $paket = $this->langganan->paket;

        return [
            'id' => $this->langganan->id,
            'paket' => $paket ? [
                'id' => $paket->id,
                'nama' => $paket->nama,
                'harga_bulanan' => (float) $paket->harga_bulanan,
                'diskon' => (float) $paket->persentase_diskon_booking,
            ] : null,
            'tanggal_berakhir' => $this->langganan->tanggal_berakhir?->toDateString(),
        ];
    }
}
