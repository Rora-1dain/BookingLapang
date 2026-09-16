<?php

namespace App\Policies;

use App\Models\Percakapan;
use App\Models\User;

class PercakapanPolicy
{
    public function view(User $user, Percakapan $percakapan): bool
    {
        return $user->id === $percakapan->user_id || $user->id === $percakapan->pemilik_id;
    }

    public function update(User $user, Percakapan $percakapan): bool
    {
        return $this->view($user, $percakapan);
    }
}