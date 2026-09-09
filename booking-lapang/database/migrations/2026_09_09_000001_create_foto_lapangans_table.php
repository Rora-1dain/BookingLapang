<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('foto_lapangans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lapangan_id')->constrained()->cascadeOnDelete();
            $table->string('path_file');
            $table->unsignedInteger('urutan')->default(0);
            $table->boolean('is_utama')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('foto_lapangans');
    }
};
