<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('metode_pembayaran')->nullable()->after('status');
            $table->enum('status_pembayaran', ['unpaid', 'paid', 'failed'])
                ->default('unpaid')->after('metode_pembayaran');
            $table->string('payment_reference')->unique()->nullable()->after('status_pembayaran');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['metode_pembayaran', 'status_pembayaran', 'payment_reference']);
        });
    }
};
