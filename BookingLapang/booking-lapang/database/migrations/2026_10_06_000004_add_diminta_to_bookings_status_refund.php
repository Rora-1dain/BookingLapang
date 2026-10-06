<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambah nilai 'diminta' pada enum status_refund: user mengajukan refund
     * sendiri, menunggu diproses/ditolak admin (dua langkah).
     *
     * CATATAN PENTING: $table->enum(...)->change() TIDAK BISA dipakai di
     * PostgreSQL. Grammar Postgres membuat tipe enum sebagai
     * "varchar(255) check (...)" yang sah saat CREATE TABLE, tapi tidak sah
     * di dalam ALTER COLUMN ... TYPE — Postgres menolak dengan
     *   SQLSTATE[42601] syntax error at or near "check".
     * Jadi di Postgres kita lepas & pasang ulang CHECK constraint-nya langsung.
     * SQLite (dipakai phpunit) tetap pakai ->change() karena Laravel membangun
     * ulang tabelnya.
     */
    private const NILAI = ['belum_refund', 'diminta', 'diproses', 'selesai', 'ditolak'];

    public function up(): void
    {
        $this->ubahConstraint(self::NILAI);
    }

    public function down(): void
    {
        // 'diminta' tidak ada di nilai lama — pindahkan dulu supaya constraint
        // lama bisa dipasang kembali tanpa error.
        DB::table('bookings')->where('status_refund', 'diminta')->update(['status_refund' => 'belum_refund']);

        $this->ubahConstraint(['belum_refund', 'diproses', 'selesai', 'ditolak']);
    }

    private function ubahConstraint(array $nilai): void
    {
        if (Schema::getConnection()->getDriverName() === 'pgsql') {
            // Laravel membuat kolom enum di Postgres sebagai varchar(255) +
            // CHECK constraint. Cari nama aslinya dari katalog (lebih aman
            // daripada menebak), lepas, lalu pasang ulang dengan nilai baru.
            $nama = DB::table('pg_constraint')
                ->whereRaw("conrelid = 'bookings'::regclass")
                ->where('contype', 'c')
                ->whereRaw("pg_get_constraintdef(oid) like '%status_refund%'")
                ->value('conname') ?? 'bookings_status_refund_check';

            DB::statement('alter table "bookings" drop constraint if exists "'.$nama.'"');

            $daftar = implode(', ', array_map(fn ($v) => "'".$v."'", $nilai));
            DB::statement('alter table "bookings" add constraint "bookings_status_refund_check" check ("status_refund" in ('.$daftar.'))');

            return;
        }

        Schema::table('bookings', function (Blueprint $table) use ($nilai) {
            $table->enum('status_refund', $nilai)->default('belum_refund')->change();
        });
    }
};
