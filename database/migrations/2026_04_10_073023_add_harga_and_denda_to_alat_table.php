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
        Schema::table('alat', function (Blueprint $table) {
            // Harga Alat (untuk perhitungan denda)
            $table->decimal('harga_alat', 14, 2)
                ->default(0)
                ->after('deskripsi')
                ->comment('Harga alat untuk perhitungan denda');

            // Persentase Denda Rusak (default 30%)
            $table->integer('persen_denda_rusak')
                ->default(30)
                ->after('harga_alat')
                ->comment('Persentase denda jika alat rusak (0-100)');

            // Index untuk query lebih cepat
            $table->index('harga_alat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('alat', function (Blueprint $table) {
            $table->dropIndex(['harga_alat']);
            $table->dropColumn(['harga_alat', 'persen_denda_rusak']);
        });
    }
};