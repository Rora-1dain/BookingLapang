<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use LogicException;

class AuditLog extends Model
{
    // Tabel memakai kolom 'dicatat_pada' sendiri, bukan created_at/updated_at.
    // Sengaja TANPA SoftDeletes: audit log adalah catatan append-only.
    public $timestamps = false;

    protected $table = 'audit_logs';

    protected $fillable = [
        'user_id',
        'aksi',
        'objek_type',
        'objek_id',
        'data_sebelum',
        'data_sesudah',
        'ip_address',
        'user_agent',
        'dicatat_pada',
    ];

    protected $casts = [
        'data_sebelum' => 'array',
        'data_sesudah' => 'array',
        'dicatat_pada' => 'datetime',
    ];

    // Lapis pengaman kedua di level model: selain tidak ada route/method untuk
    // edit & hapus, Eloquent sendiri menolak update()/delete() pada baris log.
    protected static function booted(): void
    {
        static::updating(function () {
            throw new LogicException('Audit log bersifat append-only dan tidak boleh diubah.');
        });

        static::deleting(function () {
            throw new LogicException('Audit log bersifat append-only dan tidak boleh dihapus.');
        });
    }

    public function pelaku()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Filter dipakai halaman admin.audit.index: pelaku ('sistem' = aksi tanpa user),
    // jenis aksi, dan rentang tanggal (inklusif sampai akhir hari 'sampai').
    public function scopeFilter(Builder $query, array $filter): Builder
    {
        return $query
            ->when($filter['pelaku'] ?? null, function ($q, $pelaku) {
                return $pelaku === 'sistem'
                    ? $q->whereNull('user_id')
                    : $q->where('user_id', $pelaku);
            })
            ->when($filter['aksi'] ?? null, fn ($q, $aksi) => $q->where('aksi', $aksi))
            ->when($filter['dari'] ?? null, fn ($q, $dari) => $q->where('dicatat_pada', '>=', Carbon::parse($dari)->startOfDay()))
            ->when($filter['sampai'] ?? null, fn ($q, $sampai) => $q->where('dicatat_pada', '<=', Carbon::parse($sampai)->endOfDay()));
    }
}
