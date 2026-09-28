<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            // nullable: aksi yang dilakukan sistem (bukan user) tetap bisa dicatat,
            // dan log tetap ada meski akun pelakunya dihapus (nullOnDelete).
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('aksi');
            $table->string('objek_type')->nullable();
            $table->unsignedBigInteger('objek_id')->nullable();
            $table->json('data_sebelum')->nullable();
            $table->json('data_sesudah')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            // sengaja tanpa created_at/updated_at/deleted_at: log append-only,
            // tidak pernah diubah maupun di-soft-delete.
            $table->timestamp('dicatat_pada')->useCurrent();

            $table->index(['aksi', 'dicatat_pada']);
            $table->index(['objek_type', 'objek_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
