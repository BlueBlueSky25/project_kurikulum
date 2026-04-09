<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alat_units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alat_id')->constrained('alat', 'alat_id')->onDelete('cascade');
            $table->integer('unit_number');
            $table->longText('qr_code')->nullable();
            $table->enum('status', ['baik', 'rusak', 'maintenance'])->default('baik');
            $table->timestamps();
            
            // Setiap alat + unit number harus unique
            $table->unique(['alat_id', 'unit_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alat_units');
    }
};