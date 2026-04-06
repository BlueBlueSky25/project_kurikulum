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
        'keterlambatan_hari',
        'tarif_denda_per_hari',
        'denda_keterlambatan',
        'total_denda',
        'status_denda',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_kembali_aktual' => 'date',
        'tarif_denda_per_hari' => 'decimal:2',
        'denda_keterlambatan' => 'decimal:2',
        'total_denda' => 'decimal:2',
    ];

    // Relationships
    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class, 'peminjaman_id', 'peminjaman_id');
    }

    public function details()
    {
        return $this->hasMany(PengembalianDetail::class, 'pengembalian_id', 'pengembalian_id');
    }

    public function getRouteKeyName()
    {
        return 'pengembalian_id';
    }

    public function getDendaKeterlambatanFormatAttribute()
    {
        return 'Rp ' . number_format($this->denda_keterlambatan, 0, ',', '.');
    }

    public function getTotalDendaFormatAttribute()
    {
        return 'Rp ' . number_format($this->total_denda, 0, ',', '.');
    }
}