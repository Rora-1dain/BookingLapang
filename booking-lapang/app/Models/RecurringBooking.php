<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecurringBooking extends Model
{
    protected $fillable = [
        'user_id', 'lapangan_id', 'hari', 'jam_mulai', 'jam_selesai',
        'tanggal_mulai', 'jumlah_sesi', 'status',
    ];

    protected $casts = ['tanggal_mulai' => 'date'];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function lapangan()
    {
        return $this->belongsTo(Lapangan::class);
    }
}