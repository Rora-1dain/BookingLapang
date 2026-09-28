<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

#[Fillable(['name', 'email', 'password', 'kode_referral', 'direferensikan_oleh', 'ip_terakhir', 'status_verifikasi', 'path_dokumen_identitas', 'catatan_verifikasi', 'two_factor_secret', 'two_factor_aktif_pada', 'two_factor_recovery_codes'])]
#[Hidden(['password', 'remember_token', 'two_factor_secret', 'two_factor_recovery_codes'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens ,HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'two_factor_secret' => 'encrypted',
            'two_factor_aktif_pada' => 'datetime',
            'two_factor_recovery_codes' => 'array',
        ];
    }

    public function poinHistories()
    {
        return $this->hasMany(PoinHistory::class);
    }

    public function referrals()
    {
        return $this->hasMany(User::class, 'direferensikan_oleh');
    }

    public function lapangans()
    {
        return $this->hasMany(Lapangan::class, 'pemilik_id');
    }

    public function langgananUsers()
    {
        return $this->hasMany(LanggananUser::class);
    }

    public function langgananAktif(): ?LanggananUser
    {
        return $this->langgananUsers()
            ->where('status', 'aktif')
            ->where('tanggal_berakhir', '>=', now())
            ->latest('tanggal_berakhir')
            ->first();
    }

    // --- Ditambah untuk fitur privasi data (Revano) ---

    public function bookings()
    {
        return $this->hasMany(Booking::class); // FK default: user_id
    }

    public function ulasans()
    {
        // ulasans tidak punya user_id, terhubung lewat booking_id
        return $this->hasManyThrough(Ulasan::class, Booking::class);
    }

    public function pesansDikirim()
    {
        return $this->hasMany(Pesan::class, 'pengirim_id');
    }
}