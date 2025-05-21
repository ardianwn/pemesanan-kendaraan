<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Cek apakah tabel sudah memiliki constraint unique untuk kolom tertentu
     */
    private function hasUniqueConstraint($table, $column): bool
    {
        $indexName = $table . '_' . $column . '_unique';
        
        try {
            return DB::table('INFORMATION_SCHEMA.STATISTICS')
                ->where('TABLE_NAME', $table)
                ->where('INDEX_NAME', $indexName)
                ->where('NON_UNIQUE', 0)
                ->exists();
        } catch (\Exception $e) {
            try {
                $indexes = DB::select("SHOW INDEXES FROM {$table} WHERE Key_name = '{$indexName}'");
                return !empty($indexes);
            } catch (\Exception $e) {
                return false;
            }
        }
    }
    
    /**
     * Tambahkan kolom baru dengan aman (cek kolom referensi terlebih dahulu)
     */
    private function safeAddColumn(Blueprint $table, string $tableName, string $newColumn, string $type, ?string $afterColumn = null): void
    {
        if (!Schema::hasColumn($tableName, $newColumn)) {
            if ($afterColumn && Schema::hasColumn($tableName, $afterColumn)) {
                $table->{$type}($newColumn)->nullable()->after($afterColumn);
            } else {
                $table->{$type}($newColumn)->nullable();
            }
        }
    }

    /**
     * Jalankan migrasi.
     */
    public function up(): void
    {
        // Tabel users
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'uuid')) {
                $table->uuid('uuid')->after('id')->nullable();
            }
            $this->safeAddColumn($table, 'users', 'preferences', 'json', 'email');
            if (!Schema::hasColumn('users', 'deleted_at')) {
                $table->softDeletes();
            }
        });

        // Tabel kendaraan
        Schema::table('kendaraan', function (Blueprint $table) {
            if (!Schema::hasColumn('kendaraan', 'uuid')) {
                $table->uuid('uuid')->after('id')->nullable();
            }
            $this->safeAddColumn($table, 'kendaraan', 'specifications', 'json', 'kapasitas');
            if (!Schema::hasColumn('kendaraan', 'deleted_at')) {
                $table->softDeletes();
            }
        });

        // Tabel drivers
        Schema::table('drivers', function (Blueprint $table) {
            if (!Schema::hasColumn('drivers', 'uuid')) {
                $table->uuid('uuid')->after('id')->nullable();
            }
            $this->safeAddColumn($table, 'drivers', 'schedule', 'json', 'alamat');
            if (!Schema::hasColumn('drivers', 'deleted_at')) {
                $table->softDeletes();
            }
        });

        // Tabel pemesanans
        Schema::table('pemesanans', function (Blueprint $table) {
            if (!Schema::hasColumn('pemesanans', 'uuid')) {
                $table->uuid('uuid')->after('id')->nullable();
            }
            $this->safeAddColumn($table, 'pemesanans', 'additional_info', 'json', 'catatan');
            if (!Schema::hasColumn('pemesanans', 'deleted_at')) {
                $table->softDeletes();
            }
        });

        // Tabel persetujuans
        Schema::table('persetujuans', function (Blueprint $table) {
            if (!Schema::hasColumn('persetujuans', 'uuid')) {
                $table->uuid('uuid')->after('id')->nullable();
            }
            $this->safeAddColumn($table, 'persetujuans', 'metadata', 'json', 'catatan');
            if (!Schema::hasColumn('persetujuans', 'deleted_at')) {
                $table->softDeletes();
            }
        });

        // Generate UUID untuk semua tabel
        $tables = ['users', 'kendaraan', 'drivers', 'pemesanans', 'persetujuans'];
        foreach ($tables as $table) {
            DB::statement("UPDATE {$table} SET uuid = UUID() WHERE uuid IS NULL");
        }

        // Tambahkan constraint unique untuk UUID
        foreach ($tables as $table) {
            if (!$this->hasUniqueConstraint($table, 'uuid')) {
                Schema::table($table, function (Blueprint $table) {
                    $table->uuid('uuid')->nullable(false)->change();
                    $table->unique('uuid');
                });
            }
        }
    }

    /**
     * Rollback migrasi.
     */
    public function down(): void
    {
        $tables = [
            'users' => ['uuid', 'preferences'],
            'kendaraan' => ['uuid', 'specifications'],
            'drivers' => ['uuid', 'schedule'],
            'pemesanans' => ['uuid', 'additional_info'],
            'persetujuans' => ['uuid', 'metadata']
        ];

        foreach ($tables as $tableName => $columns) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName, $columns) {
                if (Schema::hasColumn($tableName, 'uuid') && $this->hasUniqueConstraint($tableName, 'uuid')) {
                    $table->dropUnique("{$tableName}_uuid_unique");
                }
                
                foreach ($columns as $column) {
                    if (Schema::hasColumn($tableName, $column)) {
                        $table->dropColumn($column);
                    }
                }
                
                if (Schema::hasColumn($tableName, 'deleted_at')) {
                    $table->dropSoftDeletes();
                }
            });
        }
    }
};