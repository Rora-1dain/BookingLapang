<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Mendukung percakapan user <-> admin (tanpa lapangan). lapangan_id dibuat
     * nullable, dan ditambah kolom 'tipe' ('lapangan' | 'admin').
     */
    public function up(): void
    {
        Schema::table('percakapans', function (Blueprint $table) {
            $table->dropForeign(['lapangan_id']);
        });

        Schema::table('percakapans', function (Blueprint $table) {
            $table->foreignId('lapangan_id')->nullable()->change();
            $table->string('tipe', 20)->default('lapangan')->after('lapangan_id');
            $table->foreign('lapangan_id')->references('id')->on('lapangans')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('percakapans', function (Blueprint $table) {
            $table->dropForeign(['lapangan_id']);
            $table->dropColumn('tipe');
        });

        Schema::table('percakapans', function (Blueprint $table) {
            $table->foreignId('lapangan_id')->nullable(false)->change();
            $table->foreign('lapangan_id')->references('id')->on('lapangans')->cascadeOnDelete();
        });
    }
};
