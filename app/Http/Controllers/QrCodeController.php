<?php

namespace App\Http\Controllers;

use App\Models\Alat;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use PDF;
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

    // ✅ ADDED: Download single QR as PDF
    public function downloadQrPdf(Alat $alat)
    {
        if (!$alat->qr_code) {
            return redirect()->back()->with('error', 'QR Code belum digenerate');
        }

        $html = $this->generateQrHtml($alat->qr_code, $alat->nama_alat, $alat->nomor_unit);
        
        $pdf = PDF::loadHTML($html)
            ->setPaper('A6', 'portrait')
            ->setOptions([
                'margin_top' => 5,
                'margin_right' => 5,
                'margin_bottom' => 5,
                'margin_left' => 5,
            ]);

        return $pdf->download("QR-{$alat->nama_alat}-{$alat->alat_id}.pdf");
    }

    // ✅ ADDED: Download all QR codes as PDF
    public function downloadAllQrPdf()
    {
        $alats = Alat::whereNotNull('qr_code')->get();

        if ($alats->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada QR Code yang sudah digenerate');
        }

        $html = '<style>
            * { margin: 0; padding: 0; }
            body { font-family: Arial, sans-serif; }
            .page-break { page-break-after: always; }
            .qr-container {
                display: flex;
                justify-content: center;
                align-items: center;
                flex-direction: column;
                height: 100%;
                border: 2px dashed #000;
                padding: 10px;
                text-align: center;
            }
            .qr-container img { width: 150px; margin-bottom: 10px; }
            .qr-container p { font-size: 12px; font-weight: bold; margin: 5px 0; }
        </style>';

        foreach ($alats as $index => $alat) {
            $html .= '<div class="qr-container">';
            $html .= '<img src="' . $alat->qr_code . '" alt="QR Code" />';
            $html .= '<p>' . $alat->nama_alat . '</p>';
            $html .= '<p>' . ($alat->nomor_unit ?? '-') . '</p>';
            $html .= '</div>';
            
            if ($index < $alats->count() - 1) {
                $html .= '<div class="page-break"></div>';
            }
        }

        $pdf = PDF::loadHTML($html)
            ->setPaper('A6', 'portrait')
            ->setOptions([
                'margin_top' => 0,
                'margin_right' => 0,
                'margin_bottom' => 0,
                'margin_left' => 0,
            ]);

        return $pdf->download('QR-Codes-All-' . date('Y-m-d-His') . '.pdf');
    }

    // ✅ ADDED: Helper method untuk generate HTML
    private function generateQrHtml($qrBase64, $namaAlat, $nomorUnit)
    {
        return '
            <html>
            <head>
                <style>
                    * { margin: 0; padding: 0; }
                    body { 
                        font-family: Arial, sans-serif;
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        height: 100vh;
                    }
                    .sticker {
                        width: 200px;
                        text-align: center;
                        border: 2px dashed #000;
                        padding: 15px;
                    }
                    .sticker img { width: 150px; margin-bottom: 10px; display: block; }
                    .sticker p { 
                        margin: 5px 0; 
                        font-size: 12px; 
                        font-weight: bold;
                        word-wrap: break-word;
                    }
                </style>
            </head>
            <body>
                <div class="sticker">
                    <img src="' . $qrBase64 . '" alt="QR Code" />
                    <p>' . $namaAlat . '</p>
                    <p>' . ($nomorUnit ?? '-') . '</p>
                </div>
            </body>
            </html>
        ';
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