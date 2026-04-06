<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Raw SQL - Bypass semua issue
        DB::statement('ALTER TABLE pengembalian ADD COLUMN denda_keterlambatan NUMERIC(14,2) DEFAULT 0');
        DB::statement('ALTER TABLE pengembalian ADD COLUMN denda_barang NUMERIC(14,2) DEFAULT 0');
        DB::statement('ALTER TABLE alat ADD COLUMN harga_alat NUMERIC(14,2) DEFAULT 0');
        DB::statement('ALTER TABLE alat ADD COLUMN persen_denda_rusak INTEGER DEFAULT 30');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE pengembalian DROP COLUMN IF EXISTS denda_keterlambatan');
        DB::statement('ALTER TABLE pengembalian DROP COLUMN IF EXISTS denda_barang');
        DB::statement('ALTER TABLE alat DROP COLUMN IF EXISTS harga_alat');
        DB::statement('ALTER TABLE alat DROP COLUMN IF EXISTS persen_denda_rusak');
    }
};