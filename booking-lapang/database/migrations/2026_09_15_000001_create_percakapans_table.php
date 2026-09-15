<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('percakapans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lapangan_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('pemilik_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['lapangan_id', 'user_id']);
        });

        Schema::create('pesans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('percakapan_id')->constrained()->cascadeOnDelete();
            $table->foreignId('pengirim_id')->constrained('users')->cascadeOnDelete();
            $table->text('isi');
            $table->timestamp('dibaca_pada')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pesans');
        Schema::dropIfExists('percakapans');
    }
};
