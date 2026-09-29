<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recurring_bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('lapangan_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('hari');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->date('tanggal_mulai');
            $table->unsignedTinyInteger('jumlah_sesi');
            $table->enum('status', ['diproses', 'selesai', 'gagal_sebagian'])->default('diproses');
            $table->timestamps();
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->foreignId('recurring_booking_id')->nullable()
                ->constrained()->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropConstrainedForeignId('recurring_booking_id');
        });

        Schema::dropIfExists('recurring_bookings');
    }
};
