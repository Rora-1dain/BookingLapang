<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MembershipTransaction extends Model
{
    protected $fillable = [
        'user_id',
        'membership_paket_id',
        'order_id',
        'gross_amount',
        'status',
        'paid_at',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'gross_amount' => 'float',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function paket(): BelongsTo
    {
        return $this->belongsTo(MembershipPaket::class, 'membership_paket_id');
    }

    public function sudahDibayar(): bool
    {
        return $this->paid_at !== null;
    }
}
