<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengembalian', function (Blueprint $table) {
            // Hapus kolom yang tidak perlu lagi
            $table->dropColumn(['keterlambatan_hari', 'tarif_denda_per_hari', 'denda_keterlambatan']);
        });
    }

    public function down(): void
    {
        Schema::table('pengembalian', function (Blueprint $table) {
            $table->integer('keterlambatan_hari')->nullable();
            $table->decimal('tarif_denda_per_hari', 12, 2)->nullable();
            $table->decimal('denda_keterlambatan', 12, 2)->nullable();
        });
    }
};