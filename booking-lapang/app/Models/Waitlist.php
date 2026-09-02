<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Waitlist extends Model
{
    use HasFactory;

    protected $fillable = [
        'lapangan_id',
        'user_id',
        'tanggal_booking',
        'jam_mulai',
        'jam_selesai',
        'status',
        'ditawarkan_pada',
    ];

    protected $casts = [
        'tanggal_booking' => 'date',
        'ditawarkan_pada' => 'datetime',
    ];

    public function lapangan()
    {
        return $this->belongsTo(Lapangan::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}