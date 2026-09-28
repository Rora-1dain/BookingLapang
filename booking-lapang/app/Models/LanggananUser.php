<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LanggananUser extends Model
{
    protected $fillable = [
        'user_id', 'membership_paket_id', 'tanggal_mulai',
        'tanggal_berakhir', 'status', 'sisa_kuota_gratis',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_berakhir' => 'date',
    ];

    public function paket()
    {
        return $this->belongsTo(MembershipPaket::class, 'membership_paket_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function aktif(): bool
    {
        return $this->status === 'aktif' && $this->tanggal_berakhir->isFuture();
    }
}
