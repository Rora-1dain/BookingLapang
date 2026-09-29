<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('backup:database')]
#[Description('Membuat backup database menggunakan mysqldump')]
class BackupDatabase extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $database = config('database.connections.mysql.database');
        $username = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');
        $host = config('database.connections.mysql.host');

        $timestamp = now()->format('Y_m_d_His');
        $filename = "backup-{$database}-{$timestamp}.sql";
        $path = storage_path("app/backups/{$filename}");

        if (! is_dir(storage_path('app/backups'))) {
            mkdir(storage_path('app/backups'), 0755, true);
        }

        $passwordPart = $password ? "-p{$password}" : '';

        $command = "mysqldump --user={$username} {$passwordPart} --host={$host} {$database} > \"{$path}\"";

        exec($command, $output, $resultCode);

        if ($resultCode === 0) {
            $this->info("Backup berhasil disimpan di: {$path}");

            return self::SUCCESS;
        }

        $this->error('Backup database gagal.');

        return self::FAILURE;
    }
}
