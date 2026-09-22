<?php

use App\Models\Percakapan;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('percakapan.{id}', function ($user, $id) {
    $percakapan = Percakapan::find($id);

    if (! $percakapan) {
        return false;
    }

    return in_array($user->id, [$percakapan->user_id, $percakapan->pemilik_id]);
});
