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
        'metode_pembayaran',
        'status_pembayaran',
        'payment_reference',
        'voucher_id',
        'total_diskon',
        'nomor_invoice',
    ];

    protected $casts = [
        'tanggal_booking' => 'date',
    ];

    /**
     * Booking dimiliki oleh satu lapangan.
     */
    public function lapangan()
    {
        return $this->belongsTo(Lapangan::class);
    }

    /**
     * Booking dimiliki oleh satu user.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Booking bisa memakai satu voucher (opsional).
     */
    public function voucher()
    {
        return $this->belongsTo(Voucher::class);
    }

    /**
     * Pastikan booking ini milik user yang sedang login,
     * hentikan request dengan 403 kalau bukan.
     */
    public function pastikanMilikUser(int $userId): void
    {
        if ($this->user_id !== $userId) {
            abort(403, 'Anda tidak berhak mengakses booking ini.');
        }
    }
}