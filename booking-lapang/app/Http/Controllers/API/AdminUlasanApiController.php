<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ulasan;

class AdminUlasanApiController extends Controller
{
    public function dilaporkan()
    {
        $ulasans = Ulasan::with('booking.user')
            ->where('dilaporkan', true)
            ->latest()
            ->get();

        return response()->json(['data' => $ulasans]);
    }
}
