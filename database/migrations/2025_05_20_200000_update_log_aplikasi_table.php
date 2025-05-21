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
            // Hapus kolom keterangan jika ada
            if (Schema::hasColumn('log_aplikasi', 'keterangan')) {
                $table->dropColumn('keterangan');
            }
            
            // Tambah kolom baru dengan pengecekan
            if (!Schema::hasColumn('log_aplikasi', 'tabel')) {
                $table->string('tabel')->nullable()->after('aktivitas');
            }
            
            if (!Schema::hasColumn('log_aplikasi', 'id_data')) {
                $table->unsignedBigInteger('id_data')->nullable()->after('tabel');
            }
            
            if (!Schema::hasColumn('log_aplikasi', 'deskripsi')) {
                $table->text('deskripsi')->nullable()->after('id_data');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('log_aplikasi', function (Blueprint $table) {
            // Kembalikan kolom keterangan jika belum ada
            if (!Schema::hasColumn('log_aplikasi', 'keterangan')) {
                $table->text('keterangan')->nullable();
            }
            
            // Hapus kolom baru jika ada
            if (Schema::hasColumn('log_aplikasi', 'tabel')) {
                $table->dropColumn('tabel');
            }
            
            if (Schema::hasColumn('log_aplikasi', 'id_data')) {
                $table->dropColumn('id_data');
            }
            
            if (Schema::hasColumn('log_aplikasi', 'deskripsi')) {
                $table->dropColumn('deskripsi');
            }
        });
    }
};