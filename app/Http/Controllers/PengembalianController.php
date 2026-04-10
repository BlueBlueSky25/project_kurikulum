<?php

namespace App\Http\Controllers;

use App\Models\Pengembalian;
use App\Models\Peminjaman;
use App\Models\Alat;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PengembalianController extends Controller
{
    public function index()
    {
        $pengembalian = Pengembalian::with('peminjaman.user', 'peminjaman.alat')->latest()->get();
        
        // ✅ NEW: Summary untuk dashboard
        $totalDenda = $pengembalian->sum('total_denda');
        $dendaBelumLunas = $pengembalian->where('status_denda', 'belum_lunas')->sum('total_denda');
        $alatRusak = Pengembalian::where('kondisi_alat', 'rusak')->count();
        $alatHilang = Pengembalian::where('kondisi_alat', 'hilang')->count();
        
        return view('pages.pengembalian.index', compact(
            'pengembalian',
            'totalDenda',
            'dendaBelumLunas',
            'alatRusak',
            'alatHilang'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'peminjaman_id' => 'required|exists:peminjaman,peminjaman_id',
            'tanggal_kembali_aktual' => 'required|date',
            'kondisi_alat' => 'required|in:baik,rusak,hilang',
            'keterangan' => 'nullable|string',
        ]);

        $peminjaman = Peminjaman::findOrFail($request->peminjaman_id);
        $alat = $peminjaman->alat;

        // ═════════════════════════════════════════════════════════════
        // HITUNG KETERLAMBATAN
        // ═════════════════════════════════════════════════════════════
        $tanggalKembali = Carbon::parse($request->tanggal_kembali_aktual);
        $jatuhTempo = Carbon::parse($peminjaman->tanggal_kembali_rencana);
        $keterlambatan = max(0, $tanggalKembali->diffInDays($jatuhTempo, false) * -1);

        // ═════════════════════════════════════════════════════════════
        // HITUNG DENDA KETERLAMBATAN (Fixed: Rp 50.000/hari)
        // ═════════════════════════════════════════════════════════════
        $tarifDendaHarian = 50000;
        $dendaKeterlambatan = $keterlambatan * $tarifDendaHarian;

        // ═════════════════════════════════════════════════════════════
        // HITUNG DENDA BARANG (Berdasarkan Kondisi & Harga Alat)
        // ═════════════════════════════════════════════════════════════
        $dendaBarang = 0;
        $persenDendaRusak = $alat->persen_denda_rusak ?? 30;

        if ($request->kondisi_alat == 'baik') {
            // Tidak ada denda barang
            $dendaBarang = 0;
        } elseif ($request->kondisi_alat == 'rusak') {
            // Denda = Harga Alat × Persentase × Jumlah
            $dendaBarang = ($alat->harga_alat * ($persenDendaRusak / 100)) * $peminjaman->jumlah;
        } elseif ($request->kondisi_alat == 'hilang') {
            // Denda = Harga Alat × 100% × Jumlah (Ganti Rugi Penuh)
            $dendaBarang = ($alat->harga_alat * 1) * $peminjaman->jumlah;
        }

        // ═════════════════════════════════════════════════════════════
        // HITUNG TOTAL DENDA = Keterlambatan + Barang
        // ═════════════════════════════════════════════════════════════
        $totalDenda = $dendaKeterlambatan + $dendaBarang;

        // ═════════════════════════════════════════════════════════════
        // TRANSACTION: Simpan Pengembalian + Update Status
        // ═════════════════════════════════════════════════════════════
        DB::transaction(function () use (
            $validated,
            $peminjaman,
            $keterlambatan,
            $tarifDendaHarian,
            $dendaKeterlambatan,
            $dendaBarang,
            $totalDenda,
            $request
        ) {
            // Buat record pengembalian
            Pengembalian::create([
                'peminjaman_id' => $validated['peminjaman_id'],
                'tanggal_kembali_aktual' => $validated['tanggal_kembali_aktual'],
                'kondisi_alat' => $validated['kondisi_alat'],
                'keterlambatan_hari' => $keterlambatan,
                'tarif_denda_per_hari' => $tarifDendaHarian,
                'denda_keterlambatan' => $dendaKeterlambatan,        // ✅ NEW
                'denda_barang' => $dendaBarang,                      // ✅ NEW
                'total_denda' => $totalDenda,
                'status_denda' => $totalDenda > 0 ? 'belum_lunas' : 'lunas',
                'keterangan' => $validated['keterangan'],
            ]);

            // Update status peminjaman
            $peminjaman->update(['status' => 'dikembalikan']);

            // Kembalikan stok (hanya kalau tidak hilang)
            if ($validated['kondisi_alat'] != 'hilang') {
                $peminjaman->alat->increment('stok_tersedia', $peminjaman->jumlah);
            }

            // Update kondisi alat (jika rusak/hilang)
            if ($validated['kondisi_alat'] != 'baik') {
                $peminjaman->alat->update(['kondisi' => $validated['kondisi_alat']]);
            }
        });

        // Log aktivitas
        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Proses Pengembalian - ' . strtoupper($request->kondisi_alat),
            'modul' => 'Pengembalian',
            'timestamp' => now(),
        ]);

        return redirect()->route('pengembalian.index')->with('success', 'Pengembalian berhasil diproses!');
    }

    public function destroy(Pengembalian $pengembalian)
    {
        $pengembalian->delete();

        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Hapus Pengembalian',
            'modul' => 'Pengembalian',
            'timestamp' => now(),
        ]);

        return redirect()->route('pengembalian.index')->with('success', 'Data pengembalian berhasil dihapus!');
    }
}