<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payouts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pemilik_id')->constrained('users')->cascadeOnDelete();
            $table->date('periode_mulai');
            $table->date('periode_selesai');
            $table->decimal('total_nominal', 12, 2);
            $table->enum('status', ['menunggu', 'diproses', 'selesai'])->default('menunggu');
            $table->foreignId('diproses_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('selesai_pada')->nullable();
            $table->timestamps();
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->foreignId('payout_id')->nullable()->constrained()->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropConstrainedForeignId('payout_id');
        });

        Schema::dropIfExists('payouts');
    }
};