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
        Schema::table('persetujuans', function (Blueprint $table) {
            $table->integer('level')->default(1)->after('approver_id');
            $table->unsignedBigInteger('next_approver_id')->nullable()->after('level');
            $table->foreign('next_approver_id')->references('id')->on('users');
            $table->boolean('is_final_approval')->default(false)->after('next_approver_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('persetujuans', function (Blueprint $table) {
            $table->dropForeign(['next_approver_id']);
            $table->dropColumn(['level', 'next_approver_id', 'is_final_approval']);
        });
    }
};
