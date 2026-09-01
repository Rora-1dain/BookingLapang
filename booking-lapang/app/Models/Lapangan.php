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

    public function ulasans()
{
    return $this->hasManyThrough(\App\Models\Ulasan::class, \App\Models\Booking::class);
}

public function rataRataRating(): float
{
    return round($this->ulasans()->avg('rating') ?? 0, 1);
}

public function ulasanTerbaru(int $limit = 5)
{
    return $this->ulasans()
        ->with('booking.user:id,name')
        ->latest('ulasans.created_at')
        ->limit($limit)
        ->get();
}
}
