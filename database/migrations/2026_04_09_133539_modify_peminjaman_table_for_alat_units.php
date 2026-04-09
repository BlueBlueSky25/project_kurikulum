<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            // Add alat_unit_id (reference ke unit spesifik)
            $table->foreignId('alat_unit_id')
                ->nullable()
                ->after('alat_id')
                ->constrained('alat_units')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            $table->dropForeignKeyConstraints();
            $table->dropColumn('alat_unit_id');
        });
    }
};