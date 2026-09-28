<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'lapangan' => new LapanganResource($this->whenLoaded('lapangan')),
            'tanggal_booking' => $this->tanggal_booking->format('Y-m-d'),
            'jam_mulai' => $this->jam_mulai,
            'jam_selesai' => $this->jam_selesai,
            'total_harga' => (float) $this->total_harga,
            'status' => $this->status,
            'status_pembayaran' => $this->status_pembayaran,
            'bisa_dibatalkan' => $this->status === 'pending',
            'bisa_dibayar' => $this->status_pembayaran !== 'paid',
        ];
    }
}