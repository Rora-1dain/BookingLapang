<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payment_logs', function (Blueprint $table) {
            $table->string('order_id')->after('id');
            $table->string('transaction_status')->after('order_id');
            $table->json('payload')->nullable()->after('transaction_status');
            $table->timestamp('diterima_pada')->nullable()->after('payload');
        });
    }

    public function down(): void
    {
        Schema::table('payment_logs', function (Blueprint $table) {
            $table->dropColumn(['order_id', 'transaction_status', 'payload', 'diterima_pada']);
        });
    }
};