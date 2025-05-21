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
            // Check if catatan exists before using 'after'
            $afterColumn = Schema::hasColumn('pemesanans', 'catatan') ? 'catatan' : null;
            
            if (!Schema::hasColumn('pemesanans', 'dokumen_pendukung')) {
                $afterColumn 
                    ? $table->string('dokumen_pendukung')->nullable()->after($afterColumn)
                    : $table->string('dokumen_pendukung')->nullable();
            }

            if (!Schema::hasColumn('pemesanans', 'dokumen_pendukung_name')) {
                $table->string('dokumen_pendukung_name')->nullable()->after('dokumen_pendukung');
            }

            if (!Schema::hasColumn('pemesanans', 'dokumen_pendukung_type')) {
                $table->string('dokumen_pendukung_type')->nullable()->after('dokumen_pendukung_name');
            }

            if (!Schema::hasColumn('pemesanans', 'dokumen_pendukung_size')) {
                $table->integer('dokumen_pendukung_size')->nullable()->after('dokumen_pendukung_type');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pemesanans', function (Blueprint $table) {
            $columnsToDrop = [];
            
            if (Schema::hasColumn('pemesanans', 'dokumen_pendukung')) {
                $columnsToDrop[] = 'dokumen_pendukung';
            }
            
            if (Schema::hasColumn('pemesanans', 'dokumen_pendukung_name')) {
                $columnsToDrop[] = 'dokumen_pendukung_name';
            }
            
            if (Schema::hasColumn('pemesanans', 'dokumen_pendukung_type')) {
                $columnsToDrop[] = 'dokumen_pendukung_type';
            }
            
            if (Schema::hasColumn('pemesanans', 'dokumen_pendukung_size')) {
                $columnsToDrop[] = 'dokumen_pendukung_size';
            }
            
            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};