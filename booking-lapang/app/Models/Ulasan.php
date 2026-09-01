<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ulasan extends Model
{
    // 'dilaporkan' ditambahkan di fillable karena UlasanService::laporkanUlasan()
    // memanggil update(['dilaporkan' => true]) — tanpa ini, mass assignment akan
    // ditolak oleh Eloquent (MassAssignmentException) walaupun nilainya boolean sederhana.
    protected $fillable = ['booking_id', 'rating', 'komentar', 'dilaporkan'];

    protected $casts = [
        'dilaporkan' => 'boolean',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
