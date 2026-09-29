<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payout;
use Illuminate\Http\Request;

class PemilikPayoutApiController extends Controller
{
    public function index(Request $request)
    {
        $payouts = Payout::where('pemilik_id', $request->user()->id)
            ->latest()
            ->paginate(15);

        return response()->json($payouts);
    }
}
