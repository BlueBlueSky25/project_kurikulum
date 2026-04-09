<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AlatUnit extends Model
{
    protected $table = 'alat_units';
    protected $guarded = [];

    public function alat()
    {
        return $this->belongsTo(Alat::class, 'alat_id', 'alat_id');
    }

    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class, 'alat_unit_id');
    }

    // Check apakah unit sedang dipinjam
    public function isPeminjam()
    {
        return $this->peminjaman()
            ->where('status', 'dipinjam')
            ->exists();
    }

    // Get peminjam saat ini
    public function getPeminjamSekarang()
    {
        return $this->peminjaman()
            ->where('status', 'dipinjam')
            ->latest()
            ->first();
    }
}