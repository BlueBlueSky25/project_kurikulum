@extends('layouts.app')

@section('title', 'Manajemen QR Code')

@section('content')

    <div class="mb-8">
        <p class="font-sans text-[0.58rem] font-semibold tracking-[0.35em] uppercase text-label mb-1">
            Aset
        </p>
        <h2 class="font-serif text-ink text-3xl font-normal leading-none">
            Manajemen QR Code Barang
        </h2>
        <div class="mt-3 h-px w-10 bg-rule"></div>
    </div>

    {{-- Alert Messages --}}
    @if(session('success'))
        <div class="mb-6 px-4 py-3 bg-green-100 border border-green-400 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 px-4 py-3 bg-red-100 border border-red-400 text-red-700 rounded">
            {{ session('error') }}
        </div>
    @endif

    {{-- Generate All Button --}}
    <div class="mb-8">
        <a href="{{ route('qr-generate-all') }}"
            class="relative overflow-hidden inline-flex items-center gap-2 bg-espresso px-5 py-3
                   font-sans text-[0.62rem] font-semibold tracking-[0.2em] uppercase text-paper
                   transition-colors duration-200 hover:bg-ink active:scale-[0.99]"
        >
            <i class="fas fa-qrcode text-xs"></i>
            <span>Generate Semua QR Code</span>
        </a>
    </div>

    {{-- Daftar Barang & QR --}}
    <div class="bg-paper border border-rule">
        <table class="w-full">
            <thead>
                <tr class="border-b border-rule bg-cream">
                    <th class="px-4 py-3.5 text-left font-sans text-[0.55rem] font-semibold tracking-[0.25em] uppercase text-label">Nama Alat</th>
                    <th class="px-4 py-3.5 text-left font-sans text-[0.55rem] font-semibold tracking-[0.25em] uppercase text-label">Unit</th>
                    <th class="px-4 py-3.5 text-center font-sans text-[0.55rem] font-semibold tracking-[0.25em] uppercase text-label">QR Code</th>
                    <th class="px-4 py-3.5 text-left font-sans text-[0.55rem] font-semibold tracking-[0.25em] uppercase text-label">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-rule" id="tableBody">
                @foreach($alats as $alat)
                    <tr class="hover:bg-cream/40" id="row-{{ $alat->alat_id }}">
                        <td class="px-4 py-4 font-sans text-[0.78rem] font-medium text-ink">
                            {{ $alat->nama_alat }}
                        </td>
                        <td class="px-4 py-4 font-sans text-[0.78rem] text-label">
                            {{ $alat->nomor_unit ?? '—' }}
                        </td>
                        <td class="px-4 py-4 text-center">
                            <img id="qr-{{ $alat->alat_id }}" 
                                 src="{{ $alat->qr_code ?? '' }}" 
                                 alt="QR" 
                                 style="width: 80px; height: 80px; {{ !$alat->qr_code ? 'display: none;' : '' }}">
                            <span id="qr-empty-{{ $alat->alat_id }}" 
                                  class="font-sans text-[0.65rem] text-ghost"
                                  style="{{ $alat->qr_code ? 'display: none;' : '' }}">
                                Belum ada
                            </span>
                        </td>
                        <td class="px-4 py-4">
                            <div class="flex gap-2">
                                {{-- Generate QR dengan AJAX --}}
                                <button onclick="generateQr({{ $alat->alat_id }})"
                                    id="btn-generate-{{ $alat->alat_id }}"
                                    class="px-3 py-2 bg-espresso text-paper border border-espresso font-sans text-[0.55rem] font-semibold tracking-[0.1em] uppercase hover:bg-ink transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                                    <i class="fas fa-sync text-xs"></i> Generate
                                </button>

                                {{-- Print --}}
                                <button onclick="printQr('{{ $alat->qr_code ?? '' }}', '{{ $alat->nama_alat }}', '{{ $alat->nomor_unit ?? '' }}')"
                                    id="btn-print-{{ $alat->alat_id }}"
                                    class="px-3 py-2 border border-rule text-label font-sans text-[0.55rem] font-semibold tracking-[0.1em] uppercase hover:border-espresso hover:text-espresso transition-all"
                                    style="{{ !$alat->qr_code ? 'display: none;' : '' }}">
                                    <i class="fas fa-print text-xs"></i> Print
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

        <script>
        function generateQr(alatId) {
            const btn = document.getElementById(`btn-generate-${alatId}`);
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin text-xs"></i> Loading...';

            fetch(`{{ url('/qr-generate') }}/${alatId}`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update QR image
                    const img = document.getElementById(`qr-${alatId}`);
                    const empty = document.getElementById(`qr-empty-${alatId}`);
                    const printBtn = document.getElementById(`btn-print-${alatId}`);

                    img.src = data.qr_code;
                    img.style.display = 'block';
                    empty.style.display = 'none';
                    printBtn.style.display = 'block';

                    // Show success message
                    showAlert('success', data.message);
                } else {
                    showAlert('error', data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('error', 'Terjadi kesalahan saat generate QR');
            })
            .finally(() => {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-sync text-xs"></i> Generate';
            });
        }

        function printQr(qrBase64, namaAlat, nomorUnit) {
            if (!qrBase64) {
                alert('QR Code belum tersedia');
                return;
            }

            const printWindow = window.open('', '', 'width=400,height=500');
            const htmlContent = `
                <html>
                    <head>
                        <title>Print QR Code</title>
                        <style>
                            body { 
                                font-family: Arial, sans-serif;
                                display: flex;
                                flex-direction: column;
                                align-items: center;
                                padding: 20px;
                            }
                            .sticker {
                                width: 200px;
                                text-align: center;
                                border: 2px dashed #000;
                                padding: 10px;
                                margin-bottom: 10px;
                            }
                            img { width: 150px; }
                            p { margin: 5px 0; font-size: 12px; font-weight: bold; }
                        </style>
                    </head>
                    <body>
                        <div class="sticker">
                            <img src="${qrBase64}" alt="QR Code" />
                            <p>${namaAlat}</p>
                            <p>${nomorUnit}</p>
                        </div>
                    </body>
                </html>
            `;
            
            printWindow.document.write(htmlContent);
            printWindow.document.close();
            
            setTimeout(() => {
                printWindow.print();
                printWindow.close();
            }, 250);
        }

        function showAlert(type, message) {
            const bgColor = type === 'success' 
                ? 'bg-green-100 border-green-400 text-green-700' 
                : 'bg-red-100 border-red-400 text-red-700';
            
            const alertHtml = `<div class="mb-6 px-4 py-3 ${bgColor} border rounded">${message}</div>`;
            
            const alertDiv = document.createElement('div');
            alertDiv.innerHTML = alertHtml;
            
            const container = document.querySelector('.mb-8');
            if (container) {
                container.insertAdjacentElement('afterend', alertDiv);
            }

            setTimeout(() => alertDiv.remove(), 4000);
        }
    </script>

@endsection