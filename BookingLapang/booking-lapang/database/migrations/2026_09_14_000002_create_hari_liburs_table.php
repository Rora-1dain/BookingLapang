<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hari_liburs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lapangan_id')->constrained()->cascadeOnDelete();
            $table->date('tanggal');
            $table->string('keterangan')->nullable();
            $table->timestamps();
            $table->unique(['lapangan_id', 'tanggal']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hari_liburs');
    }
};
