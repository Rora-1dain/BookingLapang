<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotificationPreference extends Model
{
    protected $fillable = [
        'user_id',
        'tipe_notifikasi',
        'email_aktif',
        'database_aktif',
    ];

    protected function casts(): array
    {
        return [
            'email_aktif' => 'boolean',
            'database_aktif' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
