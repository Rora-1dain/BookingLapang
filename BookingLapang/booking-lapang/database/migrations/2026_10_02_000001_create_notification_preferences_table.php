<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notification_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('tipe_notifikasi');          // e.g. 'booking', 'chat', 'lapangan', 'payout', 'verifikasi', 'waitlist', 'ulasan', 'promo'
            $table->boolean('email_aktif')->default(true);
            $table->boolean('database_aktif')->default(true);
            $table->timestamps();

            $table->unique(['user_id', 'tipe_notifikasi']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notification_preferences');
    }
};
