<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->enum('status_refund', ['belum_refund', 'diproses', 'selesai', 'ditolak'])
                ->default('belum_refund');
            $table->string('alasan_pembatalan')->nullable();
            $table->text('catatan_refund')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['status_refund', 'alasan_pembatalan', 'catatan_refund']);
        });
    }
};
