<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('refund_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->foreignId('admin_id')->constrained('users')->cascadeOnDelete();
            $table->decimal('nominal', 10, 2);
            $table->decimal('persentase', 5, 2);
            $table->string('hasil');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('refund_logs');
    }
};
