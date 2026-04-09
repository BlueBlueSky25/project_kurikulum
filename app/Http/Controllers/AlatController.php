<?php

namespace App\Http\Controllers;

use App\Models\Alat;
use App\Models\AlatUnit;
use App\Models\Kategori;
use App\Models\PengembalianDetail;
use App\Models\Peminjaman;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AlatController extends Controller
{
    public function index()
    {
        $alats = Alat::with('kategori', 'units')->get();
        
        // ✅ Count barang kondisi per alat dari pengembalian_detail
        $alatStats = [];
        // ✅ Count barang yang sedang dipinjam (status = disetujui, belum dikembalikan)
        $alatDipinjam = [];
        
        foreach ($alats as $alat) {
            // Status barang dari pengembalian
            $alatStats[$alat->alat_id] = [
                'baik' => PengembalianDetail::whereHas('pengembalian.peminjaman', function($q) use ($alat) {
                    $q->where('alat_id', $alat->alat_id);
                })->where('kondisi_alat', 'baik')->sum('jumlah'),
                'rusak' => PengembalianDetail::whereHas('pengembalian.peminjaman', function($q) use ($alat) {
                    $q->where('alat_id', $alat->alat_id);
                })->where('kondisi_alat', 'rusak')->sum('jumlah'),
                'hilang' => PengembalianDetail::whereHas('pengembalian.peminjaman', function($q) use ($alat) {
                    $q->where('alat_id', $alat->alat_id);
                })->where('kondisi_alat', 'hilang')->sum('jumlah'),
            ];
            
            // ✅ FIXED: Hitung barang yang sedang dipinjam (status = disetujui, belum ada pengembalian)
            $alatDipinjam[$alat->alat_id] = Peminjaman::where('alat_id', $alat->alat_id)
                ->where('status', 'disetujui')
                ->whereDoesntHave('pengembalian') // Belum ada pengembalian
                ->sum('jumlah');
        }
        
        $userLevel = Auth::user()->level;
        return view('pages.alat.index', compact('alats', 'userLevel', 'alatStats', 'alatDipinjam'));
    }

    public function create()
    {
        $kategoris = Kategori::all();
        return view('pages.alat.create', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kategori_id' => 'required|exists:kategori,kategori_id',
            'nama_alat' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'kode_alat' => 'required|string|unique:alat,kode_alat',
            'stok_total' => 'required|integer|min:1',
            'kondisi' => 'required|in:baik,rusak,hilang',
            'lokasi' => 'nullable|string',
            'harga_alat' => 'required|numeric|min:0',
            'persen_denda_rusak' => 'required|integer|min:0|max:100',
        ]);

        $validated['stok_tersedia'] = $validated['stok_total'];

        // ✅ UPDATED: Buat alat + auto-create units
        DB::transaction(function () use ($validated) {
            $alat = Alat::create($validated);

            // Auto-create units untuk setiap stok_total
            for ($i = 1; $i <= $validated['stok_total']; $i++) {
                AlatUnit::create([
                    'alat_id' => $alat->alat_id,
                    'unit_number' => $i,
                    'qr_code' => null,
                    'status' => 'baik',
                ]);
            }

            LogAktivitas::create([
                'user_id' => Auth::id(),
                'aktivitas' => 'Tambah Alat',
                'modul' => 'Alat',
                'timestamp' => now(),
            ]);
        });

        return redirect()->route('alat.index')->with('success', 'Alat berhasil ditambahkan!');
    }

    public function edit(Alat $alat)
    {
        $kategoris = Kategori::all();
        return view('pages.alat.edit', compact('alat', 'kategoris'));
    }

    public function update(Request $request, Alat $alat)
    {
        $validated = $request->validate([
            'kategori_id' => 'required|exists:kategori,kategori_id',
            'nama_alat' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'kode_alat' => 'required|string|unique:alat,kode_alat,' . $alat->alat_id . ',alat_id',
            'stok_total' => 'required|integer|min:1',
            'kondisi' => 'required|in:baik,rusak,hilang',
            'lokasi' => 'nullable|string',
            'harga_alat' => 'required|numeric|min:0',
            'persen_denda_rusak' => 'required|integer|min:0|max:100',
        ]);

        // ✅ UPDATED: Handle perubahan stok_total dengan manage units
        DB::transaction(function () use ($alat, $validated) {
            $oldStokTotal = $alat->stok_total;
            $newStokTotal = $validated['stok_total'];

            $alat->update($validated);

            // Jika stok_total berubah, update units
            if ($newStokTotal > $oldStokTotal) {
                // Tambah unit baru
                for ($i = $oldStokTotal + 1; $i <= $newStokTotal; $i++) {
                    AlatUnit::create([
                        'alat_id' => $alat->alat_id,
                        'unit_number' => $i,
                        'qr_code' => null,
                        'status' => 'baik',
                    ]);
                }
            } else if ($newStokTotal < $oldStokTotal) {
                // Hapus unit dari yang terakhir (yang nomor unitnya lebih tinggi)
                AlatUnit::where('alat_id', $alat->alat_id)
                    ->where('unit_number', '>', $newStokTotal)
                    ->delete();
            }

            LogAktivitas::create([
                'user_id' => Auth::id(),
                'aktivitas' => 'Update Alat',
                'modul' => 'Alat',
                'timestamp' => now(),
            ]);
        });

        return redirect()->route('alat.index')->with('success', 'Alat berhasil diupdate!');
    }

    public function destroy(Alat $alat)
    {
        // ✅ UPDATED: Cascade delete units juga (handled by DB constraint)
        DB::transaction(function () use ($alat) {
            $alat->delete();
            // Units auto-delete karena foreign key ON DELETE CASCADE

            LogAktivitas::create([
                'user_id' => Auth::id(),
                'aktivitas' => 'Hapus Alat',
                'modul' => 'Alat',
                'timestamp' => now(),
            ]);
        });

        return redirect()->route('alat.index')->with('success', 'Alat berhasil dihapus!');
    }
}