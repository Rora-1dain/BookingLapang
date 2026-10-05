<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Catatan transaksi pembayaran membership via Midtrans. Satu baris = satu
     * percobaan bayar (order_id unik). Membership baru diaktifkan setelah
     * transaksi berstatus settlement/capture (webhook atau cek-status).
     */
    public function up(): void
    {
        Schema::create('membership_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('membership_paket_id')->constrained()->cascadeOnDelete();
            $table->string('order_id')->unique();
            $table->decimal('gross_amount', 10, 2);
            $table->enum('status', ['pending', 'settlement', 'capture', 'expire', 'deny', 'cancel'])->default('pending');
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('membership_transactions');
    }
};
