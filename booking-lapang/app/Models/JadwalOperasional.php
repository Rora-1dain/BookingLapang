<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalOperasional extends Model
{
    protected $fillable = ['lapangan_id', 'hari', 'jam_buka', 'jam_tutup', 'is_tutup'];

    protected $casts = [
        'is_tutup' => 'boolean',
    ];

    public function lapangan()
    {
        return $this->belongsTo(Lapangan::class);
    }
}