<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FotoLapangan extends Model
{
    protected $table = 'foto_lapangans';

    protected $fillable = ['lapangan_id', 'path_file', 'urutan', 'is_utama'];

    protected $casts = [
        'is_utama' => 'boolean',
    ];

    public function lapangan()
    {
        return $this->belongsTo(Lapangan::class);
    }
}
