<?php

namespace Database\Seeders;

use App\Models\Alat;
use App\Models\AlatUnit;
use Illuminate\Database\Seeder;

class CreateAlatUnitsSeeder extends Seeder
{
    public function run(): void
    {
        $alats = Alat::all();

        foreach ($alats as $alat) {
            // Gunakan stok_total sebagai jumlah unit
            $jumlahUnit = $alat->stok_total ?? 1;
            
            // Buat unit untuk setiap stok
            for ($i = 1; $i <= $jumlahUnit; $i++) {
                AlatUnit::create([
                    'alat_id' => $alat->alat_id,
                    'unit_number' => $i,
                    'qr_code' => null,
                    'status' => $alat->kondisi ?? 'baik', // Ambil dari kondisi alat
                ]);
            }
        }

        echo "✅ {$alats->count()} alat dan units-nya berhasil dibuat!\n";
    }
}