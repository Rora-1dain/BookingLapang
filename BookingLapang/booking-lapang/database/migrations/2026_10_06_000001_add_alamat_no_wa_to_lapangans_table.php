<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menambah alamat lapangan (wajib di level validasi) & nomor WhatsApp pemilik.
     * Kolom 'kota' sengaja DIPERTAHANKAN karena masih dipakai filter pencarian
     * (LapanganSearchService & Hero). Alamat = lokasi lengkap yang tampil di
     * halaman pesanan; kota = label ringkas untuk filter.
     */
    public function up(): void
    {
        Schema::table('lapangans', function (Blueprint $table) {
            // nullable di DB demi data lama, tapi WAJIB diisi di validasi request.
            $table->string('alamat')->nullable()->after('kota');
            $table->string('no_wa', 30)->nullable()->after('alamat');
        });
    }

    public function down(): void
    {
        Schema::table('lapangans', function (Blueprint $table) {
            $table->dropColumn(['alamat', 'no_wa']);
        });
    }
};
