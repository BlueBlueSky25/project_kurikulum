<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengembalian extends Model
{
    use HasFactory;

    protected $table = 'pengembalian';
    protected $primaryKey = 'pengembalian_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'peminjaman_id',
        'tanggal_kembali_aktual',
        'kondisi_alat',
        'keterlambatan_hari',
        'tarif_denda_per_hari',
        'denda_keterlambatan',     // ✅ NEW
        'denda_barang',            // ✅ NEW
        'total_denda',
        'status_denda',
        'keterangan',
    ];

    // ✅ NEW: Casting untuk tipe data
    protected $casts = [
        'tanggal_kembali_aktual' => 'date',
        'tarif_denda_per_hari' => 'decimal:2',
        'denda_keterlambatan' => 'decimal:2',
        'denda_barang' => 'decimal:2',
        'total_denda' => 'decimal:2',
    ];

    // Relationships
    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class, 'peminjaman_id', 'peminjaman_id');
    }

    public function getRouteKeyName()
    {
        return 'pengembalian_id';
    }

    // ✅ NEW: Helper untuk format currency
    public function getDendaKeterlambatanFormatAttribute()
    {
        return 'Rp ' . number_format($this->denda_keterlambatan, 0, ',', '.');
    }

    public function getDendaBarangFormatAttribute()
    {
        return 'Rp ' . number_format($this->denda_barang, 0, ',', '.');
    }

    public function getTotalDendaFormatAttribute()
    {
        return 'Rp ' . number_format($this->total_denda, 0, ',', '.');
    }
}