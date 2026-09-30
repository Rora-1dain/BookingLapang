<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lapangan extends Model
{
    use HasFactory;

    protected $table = 'lapangans';

    protected $fillable = [
        'nama_lapangan',
        'jenis',
        'harga_per_jam',
        'status',
    ];

    /**
     * Satu lapangan bisa punya banyak booking.
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
