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
        Schema::create('persetujuans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pemesanans_id')->constrained('pemesanans')->onDelete('cascade');
            $table->foreignId('approver_id')->constrained('users')->onDelete('cascade');
            $table->integer('level_approval'); // level 1, 2, dst
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('persetujuans');
    }
};
