<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * PENTING — kenapa migration ini perlu dibuat padahal tidak ada di soal:
 *
 * Soal cuma bilang "tambahkan role baru pemilik_lapangan pada kolom role",
 * tapi tidak menyertakan migration-nya. Kalau kolom `role` di tabel `users`
 * dulu dibuat sebagai enum('user', 'admin'), di Laravel migration itu
 * diterjemahkan Postgres jadi CHECK CONSTRAINT (bukan native ENUM type
 * seperti di MySQL) — project ini sudah pernah punya catatan soal ini
 * waktu migrasi ke Supabase.
 *
 * Kalau constraint lama itu tidak diupdate, insert/update user dengan
 * role = 'pemilik_lapangan' akan gagal dengan error semacam:
 * "SQLSTATE[23514]: Check violation: new row violates check constraint users_role_check"
 *
 * GANTI 'users_role_check' di bawah dengan nama constraint yang SEBENARNYA
 * ada di database kalian. Cek dulu nama constraint aslinya dengan query ini
 * di Supabase SQL Editor atau psql:
 *
 *   SELECT conname FROM pg_constraint WHERE conrelid = 'users'::regclass;
 */
return new class extends Migration
{
    public function up(): void
    {
        // Hapus constraint lama (sesuaikan nama constraint-nya!)
        DB::statement('ALTER TABLE users DROP CONSTRAINT IF EXISTS users_role_check');

        // Buat constraint baru yang mengizinkan 'pemilik_lapangan'
        DB::statement("ALTER TABLE users ADD CONSTRAINT users_role_check CHECK (role IN ('user', 'admin', 'pemilik_lapangan'))");
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE users DROP CONSTRAINT IF EXISTS users_role_check');
        DB::statement("ALTER TABLE users ADD CONSTRAINT users_role_check CHECK (role IN ('user', 'admin'))");
    }
};
