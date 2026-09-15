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
}