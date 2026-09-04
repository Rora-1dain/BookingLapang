<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('kode_referral')->unique()->nullable();
            $table->foreignId('direferensikan_oleh')->nullable()
                ->constrained('users')->nullOnDelete();
            $table->boolean('reward_referral_diberikan')->default(false);
            $table->string('ip_terakhir')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('direferensikan_oleh');
            $table->dropColumn(['kode_referral', 'reward_referral_diberikan', 'ip_terakhir']);
        });
    }
};