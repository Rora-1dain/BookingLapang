<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $table = 'bookings';

    protected $fillable = [
        'user_id',
        'lapangan_id',
        'tanggal_booking',
        'jam_mulai',
        'jam_selesai',
        'total_harga',
        'status',
        'status_refund',
        'alasan_pembatalan',
        'catatan_refund',
        'metode_pembayaran',
        'status_pembayaran',
        'payment_reference',
        'voucher_id',
        'total_diskon',
        'nomor_invoice',
        'nominal_komisi',
        'pendapatan_pemilik',
        'payout_id',
    ];

    protected $casts = [
        'tanggal_booking' => 'date',
    ];

    public function lapangan()
    {
        return $this->belongsTo(Lapangan::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function voucher()
    {
        return $this->belongsTo(Voucher::class);
    }

    public function ulasan()
    {
        return $this->hasOne(\App\Models\Ulasan::class);
    }

    public function payout()
    {
        return $this->belongsTo(Payout::class);
    }

    public function refundLogs()
    {
        return $this->hasMany(RefundLog::class);
    }

    public function pastikanMilikUser(int $userId): void
    {
        if ($this->user_id !== $userId) {
            abort(403, 'Booking ini bukan milik Anda.');
        }
    }
}