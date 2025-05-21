<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pemesanans', function (Blueprint $table) {
            $table->time('jam_pemesanan')->after('tanggal_pemesanan');
            $table->integer('durasi_pemesanan')->after('jam_pemesanan')->comment('Duration in hours');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pemesanans', function (Blueprint $table) {
            $table->dateTime('tanggal_mulai')->after('tanggal_pemesanan')->nullable();
            $table->dateTime('tanggal_selesai')->after('tanggal_mulai')->nullable();
            $table->dropColumn(['jam_pemesanan', 'durasi_pemesanan']);
        });
    }
};
