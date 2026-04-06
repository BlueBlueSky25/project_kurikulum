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
        // ✅ UPDATE TABEL ALAT
        Schema::table('alat', function (Blueprint $table) {
            if (!Schema::hasColumn('alat', 'harga_alat')) {
                $table->decimal('harga_alat', 14, 2)
                    ->default(0)
                    ->after('deskripsi')
                    ->comment('Harga alat untuk perhitungan denda');
            }

            if (!Schema::hasColumn('alat', 'persen_denda_rusak')) {
                $table->integer('persen_denda_rusak')
                    ->default(30)
                    ->after('harga_alat')
                    ->comment('Persentase denda jika alat rusak (0-100)');
            }

            if (!Schema::hasIndex('alat', 'harga_alat')) {
                $table->index('harga_alat');
            }
        });

        // ✅ UPDATE TABEL PENGEMBALIAN - TAMBAH KOLOM DENDA
        Schema::table('pengembalian', function (Blueprint $table) {
            if (!Schema::hasColumn('pengembalian', 'denda_keterlambatan')) {
                $table->decimal('denda_keterlambatan', 14, 2)
                    ->default(0)
                    ->after('tarif_denda_per_hari')
                    ->comment('Denda keterlambatan per hari × jumlah hari');
            }

            if (!Schema::hasColumn('pengembalian', 'denda_barang')) {
                $table->decimal('denda_barang', 14, 2)
                    ->default(0)
                    ->after('denda_keterlambatan')
                    ->comment('Denda kerusakan atau hilang');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('alat', function (Blueprint $table) {
            if (Schema::hasIndex('alat', 'harga_alat')) {
                $table->dropIndex('harga_alat');
            }
            $table->dropColumnIfExists('harga_alat');
            $table->dropColumnIfExists('persen_denda_rusak');
        });

        Schema::table('pengembalian', function (Blueprint $table) {
            $table->dropColumnIfExists('denda_keterlambatan');
            $table->dropColumnIfExists('denda_barang');
        });
    }
};