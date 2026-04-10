<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengembalianDetail extends Model
{
    use HasFactory;

    protected $table = 'pengembalian_detail';
    protected $primaryKey = 'detail_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'pengembalian_id',
        'kondisi_alat',
        'jumlah',
        'harga_alat',
        'persen_denda',
        'denda_barang',
    ];

    protected $casts = [
        'harga_alat' => 'decimal:2',
        'denda_barang' => 'decimal:2',
    ];

    public function pengembalian()
    {
        return $this->belongsTo(Pengembalian::class, 'pengembalian_id', 'pengembalian_id');
    }
}