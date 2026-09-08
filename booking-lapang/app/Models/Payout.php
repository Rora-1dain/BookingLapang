<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payout extends Model
{
    use HasFactory;

    protected $fillable = [
        'pemilik_id',
        'periode_mulai',
        'periode_selesai',
        'total_nominal',
        'status',
        'diproses_oleh',
        'selesai_pada',
    ];

    protected $casts = [
        'periode_mulai' => 'date',
        'periode_selesai' => 'date',
        'selesai_pada' => 'datetime',
        'total_nominal' => 'decimal:2',
    ];

    public function pemilik()
    {
        return $this->belongsTo(User::class, 'pemilik_id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'diproses_oleh');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'payout_id');
    }
}