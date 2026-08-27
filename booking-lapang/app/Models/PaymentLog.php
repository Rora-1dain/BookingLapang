<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentLog extends Model
{
    protected $fillable = [
        'order_id',
        'transaction_status',
        'payload',
        'diterima_pada',
    ];

    protected $casts = [
        'payload' => 'array',
        'diterima_pada' => 'datetime',
    ];
}