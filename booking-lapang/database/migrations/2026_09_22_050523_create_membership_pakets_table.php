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
    }

    public function down(): void
    {
        Schema::dropIfExists('membership_pakets');
    }
};
