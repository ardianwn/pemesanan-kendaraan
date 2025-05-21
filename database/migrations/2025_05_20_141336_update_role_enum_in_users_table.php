<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // First, drop the old enum constraint
        DB::statement('ALTER TABLE users MODIFY COLUMN role VARCHAR(50)');
        
        // Then add the new enum values
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'approver', 'user') DEFAULT 'user'");
    }

    public function down(): void
    {
        // Revert to original enum values
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'approver') DEFAULT 'approver'");
    }
};
