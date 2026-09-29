<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lapangans', function (Blueprint $table) {
            $table->foreignId('pemilik_id')->nullable()
                ->constrained('users')->nullOnDelete();
            $table->enum('status_approval', ['pending', 'disetujui', 'ditolak'])
                ->default('pending');
        });
    }

    public function down(): void
    {
        Schema::table('lapangans', function (Blueprint $table) {
            $table->dropConstrainedForeignId('pemilik_id');
            $table->dropColumn('status_approval');
        });
    }
};
