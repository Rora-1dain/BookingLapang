<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('membership_pakets', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->decimal('harga_bulanan', 10, 2);
            $table->decimal('persentase_diskon_booking', 5, 2)->default(0);
            $table->unsignedInteger('kuota_booking_gratis')->default(0);
            $table->timestamps();
        });

        Schema::create('langganan_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('membership_paket_id')->constrained()->cascadeOnDelete();
            $table->date('tanggal_mulai');
            $table->date('tanggal_berakhir');
            $table->enum('status', ['aktif', 'berakhir', 'dibatalkan'])->default('aktif');
            $table->unsignedInteger('sisa_kuota_gratis')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('langganan_users');
        Schema::dropIfExists('membership_pakets');
    }
};
