<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class LapanganCollection extends ResourceCollection
{
    public function toArray($request): array
    {
        return [
            'data' => $this->collection,
            'meta' => [
                'total_tersedia' => $this->collection
                    ->where('status', 'aktif')
                    ->count(),
            ],
        ];
    }
}
