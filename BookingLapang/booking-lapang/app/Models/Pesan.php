<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pesan extends Model
{
    protected $table = 'pesans';

    protected $fillable = ['percakapan_id', 'pengirim_id', 'isi', 'dibaca_pada'];

    // Pesan baru ikut menyentuh updated_at percakapan, supaya daftar chat
    // terurut berdasarkan aktivitas terakhir.
    protected $touches = ['percakapan'];

    protected $casts = [
        'dibaca_pada' => 'datetime',
    ];

    public function percakapan()
    {
        return $this->belongsTo(Percakapan::class);
    }

    public function pengirim()
    {
        return $this->belongsTo(User::class, 'pengirim_id');
    }
}