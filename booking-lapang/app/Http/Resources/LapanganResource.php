<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LapanganResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'nama_lapangan' => $this->nama_lapangan,
            'jenis' => $this->jenis,
            'harga_per_jam' => (float) $this->harga_per_jam,
            'status' => $this->status,
        ];
    }
}
