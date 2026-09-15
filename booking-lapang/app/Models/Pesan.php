<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pesan extends Model
{
    protected $fillable = ['percakapan_id', 'pengirim_id', 'isi', 'dibaca_pada'];

    protected $casts = [
        'dibaca_pada' => 'datetime',
    ];

    public function percakapan()
    {
        return $this->belongsTo(Percakapan::class);
    }
}