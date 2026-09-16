<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Percakapan extends Model
{
    protected $fillable = ['lapangan_id', 'user_id', 'pemilik_id'];

    public function pesans()
    {
        return $this->hasMany(Pesan::class);
    }

    public function pesanTerakhir()
    {
        return $this->hasOne(Pesan::class)->latestOfMany();
    }
    public function jumlahBelumDibaca(int $userId): int
    {
        return $this->pesans()
            ->where('pengirim_id', '!=', $userId)
            ->whereNull('dibaca_pada')
            ->count();
    }

    public function lapangan()
    {
        return $this->belongsTo(Lapangan::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function pemilik()
    {
        return $this->belongsTo(User::class, 'pemilik_id');
    }
}