<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Drop semua tabel dan ENUM types yang sudah ada.
     * Migration ini harus dijalankan PALING PERTAMA sebelum recreate.
     */
    public function up(): void
    {
        // Drop tabel dengan CASCADE (otomatis handle foreign key dependencies)
        $tables = [
            'log_aktivitas',
            'pengembalian',
            'peminjaman',
            'alat',
            'kategori',
            'users',
            'sessions',
            'cache_locks',
            'cache',
            'jobs',
            'migrations',
        ];

        foreach ($tables as $table) {
            DB::statement("DROP TABLE IF EXISTS \"{$table}\" CASCADE");
        }

        // Drop ENUM types
        DB::statement("DROP TYPE IF EXISTS user_level CASCADE");
        DB::statement("DROP TYPE IF EXISTS kondisi_alat CASCADE");
        DB::statement("DROP TYPE IF EXISTS status_peminjaman CASCADE");
        DB::statement("DROP TYPE IF EXISTS status_denda CASCADE");

        // Drop trigger function
        DB::statement("DROP FUNCTION IF EXISTS update_updated_at_column() CASCADE");
    }

    /**
     * Tidak ada rollback untuk migration ini.
     */
    public function down(): void
    {
        // Tidak bisa di-rollback karena data sudah dihapus
    }
};
