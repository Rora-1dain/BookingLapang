<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * PENTING — kenapa seeder ini perlu dibuat padahal tidak ada di soal:
 *
 * Soal secara eksplisit minta "migration data: user admin yang sudah ada
 * otomatis diberi role admin (super admin) di sistem permission baru, supaya
 * tidak ada yang mendadak kehilangan akses setelah migrasi" — tapi kodenya
 * TIDAK disertakan di dokumen, cuma instruksinya doang.
 *
 * Tanpa ini, begitu Controller/Policy diganti dari cek
 * `role === 'admin'` menjadi `$user->can('refund.proses')`, SEMUA admin
 * yang sudah ada akan langsung kehilangan akses ke seluruh fitur admin —
 * karena mereka belum punya role Spatie apa pun, cuma kolom `role` lama
 * di tabel users yang sudah tidak lagi dicek. Ini WAJIB dijalankan
 * sebelum (atau bersamaan dengan) mengganti pengecekan di Controller,
 * bukan setelahnya.
 *
 * Jalankan: php artisan db:seed --class=AssignAdminRoleSeeder
 * (pastikan PermissionSeeder sudah dijalankan duluan, supaya role 'admin'
 * sudah ada untuk di-assign)
 */
class AssignAdminRoleSeeder extends Seeder
{
    public function run(): void
    {
        User::where('role', 'admin')
            ->get()
            ->each(function (User $user) {
                if (! $user->hasRole('admin')) {
                    $user->assignRole('admin');
                }
            });
    }
}
