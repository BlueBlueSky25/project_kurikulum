<?php

namespace App\Http\Controllers;

use App\Models\Alat;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Http\Request;

class QrCodeController extends Controller
{
    public function indexManagement()
    {
        return view('pages.admin.qr-management', [
            'alats' => Alat::all()
        ]);
    }

    // Generate QR untuk satu barang
    public function generateQr(Alat $alat)
    {
        try {
            // Data yang disimpan di QR: ID alat + nomor unit
            $qrData = json_encode([
                'alat_id' => $alat->alat_id,
                'nama_alat' => $alat->nama_alat,
                'nomor_unit' => $alat->nomor_unit,
            ]);

            // Generate QR Code - Syntax untuk v5.x
            $qrCode = new QrCode($qrData);
            $qrCode->setSize(300);
            $qrCode->setMargin(10);

            $writer = new PngWriter();
            $result = $writer->write($qrCode);

            // Save ke database sebagai base64
            $base64 = 'data:image/png;base64,' . base64_encode($result->getString());
            $alat->update(['qr_code' => $base64]);

            return response()->json([
                'success' => true,
                'message' => 'QR Code berhasil digenerate',
                'qr_code' => $base64
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    // Generate QR untuk semua barang
    public function generateAllQr()
    {
        try {
            $alats = Alat::all();
            $successCount = 0;

            foreach ($alats as $alat) {
                try {
                    $qrData = json_encode([
                        'alat_id' => $alat->alat_id,
                        'nama_alat' => $alat->nama_alat,
                        'nomor_unit' => $alat->nomor_unit,
                    ]);

                    $qrCode = new QrCode($qrData);
                    $qrCode->setSize(300);
                    $qrCode->setMargin(10);

                    $writer = new PngWriter();
                    $result = $writer->write($qrCode);

                    $base64 = 'data:image/png;base64,' . base64_encode($result->getString());
                    $alat->update(['qr_code' => $base64]);
                    
                    $successCount++;
                } catch (\Exception $e) {
                    \Log::error("QR generate error for {$alat->nama_alat}: " . $e->getMessage());
                }
            }

            return redirect()->route('qr-management')
                ->with('success', "✅ Berhasil generate {$successCount} QR Code!");
        } catch (\Exception $e) {
            return redirect()->route('qr-management')
                ->with('error', 'Error: ' . $e->getMessage());
        }
    }

    // API: Scan QR dan return alat data
    public function scanQr(Request $request)
    {
        $validated = $request->validate([
            'qr_data' => 'required|json'
        ]);

        $data = json_decode($validated['qr_data'], true);

        $alat = Alat::find($data['alat_id']);

        if (!$alat) {
            return response()->json([
                'success' => false,
                'message' => 'Alat tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'alat' => [
                'alat_id' => $alat->alat_id,
                'nama_alat' => $alat->nama_alat,
                'nomor_unit' => $alat->nomor_unit,
                'stok_tersedia' => $alat->stok_tersedia,
                'harga_alat' => $alat->harga_alat,
            ]
        ]);
    }
}