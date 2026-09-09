<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lapangans', function (Blueprint $table) {
            $table->string('kota')->nullable();
            $table->index(['jenis', 'harga_per_jam']);
        });
    }

    public function down(): void
    {
        Schema::table('lapangans', function (Blueprint $table) {
            $table->dropIndex(['jenis', 'harga_per_jam']);
            $table->dropColumn('kota');
        });
    }
};