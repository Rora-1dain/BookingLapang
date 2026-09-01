<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    protected $fillable = [
        'kode',
        'jenis_diskon',
        'nilai',
        'kuota',
        'minimal_transaksi',
        'khusus_user_baru',
        'berlaku_sampai',
    ];

    protected $casts = [
        'khusus_user_baru' => 'boolean',
        'berlaku_sampai' => 'date',
        'nilai' => 'decimal:2',
        'minimal_transaksi' => 'decimal:2',
    ];
}