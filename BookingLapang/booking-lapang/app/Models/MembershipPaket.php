<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MembershipPaket extends Model
{
    protected $fillable = [
        'nama', 'harga_bulanan', 'persentase_diskon_booking', 'kuota_booking_gratis',
    ];

    public function langganans()
    {
        return $this->hasMany(LanggananUser::class, 'membership_paket_id');
    }
}
