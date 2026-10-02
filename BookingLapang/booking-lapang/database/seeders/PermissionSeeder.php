<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'lapangan.approve', 'lapangan.tolak', 'verifikasi.tinjau',
            'refund.proses', 'payout.buat', 'payout.selesaikan', 'komisi.ubah',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        Role::firstOrCreate(['name' => 'admin']);

        Role::firstOrCreate(['name' => 'staf_approval'])
            ->syncPermissions(['lapangan.approve', 'lapangan.tolak', 'verifikasi.tinjau']);

        Role::firstOrCreate(['name' => 'staf_keuangan'])
            ->syncPermissions(['refund.proses', 'payout.buat', 'payout.selesaikan']);
    }
}
