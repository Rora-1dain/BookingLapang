<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('status_verifikasi', ['belum_verifikasi', 'menunggu', 'terverifikasi', 'ditolak'])
                ->default('belum_verifikasi');
            $table->string('path_dokumen_identitas')->nullable();
            $table->text('catatan_verifikasi')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['status_verifikasi', 'path_dokumen_identitas', 'catatan_verifikasi']);
        });
    }
};