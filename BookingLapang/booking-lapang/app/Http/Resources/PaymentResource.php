<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'booking_id' => $this->id,
            'payment_reference' => $this->payment_reference,
            'metode_pembayaran' => $this->metode_pembayaran,
            'status_pembayaran' => $this->status_pembayaran,
            'total_harga' => (float) $this->total_harga,
        ];
    }
}
