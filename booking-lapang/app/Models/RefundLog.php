<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RefundLog extends Model
{
    protected $table = 'refund_logs';

    protected $fillable = ['booking_id', 'admin_id', 'nominal', 'persentase', 'hasil'];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
