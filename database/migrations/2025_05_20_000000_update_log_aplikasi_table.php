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
        Schema::table('log_aplikasi', function (Blueprint $table) {
            // Hapus kolom keterangan
            $table->dropColumn('keterangan');
            
            // Tambah kolom baru
            $table->string('tabel')->after('aktivitas')->nullable();
            $table->unsignedBigInteger('id_data')->after('tabel')->nullable();
            $table->text('deskripsi')->after('id_data')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('log_aplikasi', function (Blueprint $table) {
            // Kembalikan kolom keterangan
            $table->text('keterangan')->nullable();
            
            // Hapus kolom baru
            $table->dropColumn(['tabel', 'id_data', 'deskripsi']);
        });
    }
};
