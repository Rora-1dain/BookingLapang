<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jadwal_operasionals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lapangan_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('hari');
            $table->time('jam_buka')->nullable();
            $table->time('jam_tutup')->nullable();
            $table->boolean('is_tutup')->default(false);
            $table->timestamps();
            $table->unique(['lapangan_id', 'hari']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwal_operasionals');
    }
};