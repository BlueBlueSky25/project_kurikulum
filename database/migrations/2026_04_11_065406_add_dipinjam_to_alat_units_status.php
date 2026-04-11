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
    DB::statement("ALTER TABLE alat_units DROP CONSTRAINT IF EXISTS alat_units_status_check");
    DB::statement("ALTER TABLE alat_units ADD CONSTRAINT alat_units_status_check CHECK (status::text = ANY (ARRAY['baik', 'rusak', 'hilang', 'dipinjam', 'tersedia']::text[]))");
}

public function down(): void
{
    DB::statement("ALTER TABLE alat_units DROP CONSTRAINT IF EXISTS alat_units_status_check");
    DB::statement("ALTER TABLE alat_units ADD CONSTRAINT alat_units_status_check CHECK (status::text = ANY (ARRAY['baik', 'rusak', 'hilang']::text[]))");
}
};
