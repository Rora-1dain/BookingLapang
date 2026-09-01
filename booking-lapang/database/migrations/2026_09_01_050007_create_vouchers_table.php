<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique();
            $table->enum('jenis_diskon', ['persen', 'nominal']);
            $table->decimal('nilai', 10, 2);
            $table->unsignedInteger('kuota');
            $table->decimal('minimal_transaksi', 10, 2)->default(0);
            $table->boolean('khusus_user_baru')->default(false);
            $table->date('berlaku_sampai');
            $table->timestamps();
        });

        Schema::create('voucher_usages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('voucher_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['voucher_id', 'user_id']);
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->foreignId('voucher_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('total_diskon', 10, 2)->default(0);
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropConstrainedForeignId('voucher_id');
            $table->dropColumn('total_diskon');
        });

        Schema::dropIfExists('voucher_usages');
        Schema::dropIfExists('vouchers');
    }
};