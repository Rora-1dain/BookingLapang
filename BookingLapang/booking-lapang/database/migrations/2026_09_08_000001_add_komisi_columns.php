<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lapangans', function (Blueprint $table) {
            $table->decimal('persentase_komisi', 5, 2)->default(10.00);
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->decimal('nominal_komisi', 10, 2)->default(0);
            $table->decimal('pendapatan_pemilik', 10, 2)->default(0);
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['nominal_komisi', 'pendapatan_pemilik']);
        });

        Schema::table('lapangans', function (Blueprint $table) {
            $table->dropColumn('persentase_komisi');
        });
    }
};
