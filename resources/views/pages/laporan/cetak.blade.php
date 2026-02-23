<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Peminjaman Alat — {{ date('d/m/Y', strtotime($tanggal)) }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&family=Montserrat:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        @media print {
            @page { margin: 1.8cm 2cm; }
            body { margin: 0; padding: 0; }
            .no-print { display: none !important; }
            .page-break { page-break-before: always; }
            tr { page-break-inside: avoid; }
        }

        *, *::before, *::after { box-sizing: border-box; }

        :root {
            --espresso: #1c1917;
            --ink:      #1a1714;
            --dim:      #4a4540;
            --label:    #6e665e;
            --rule:     #c8bfb0;
            --ghost:    #a89f94;
            --paper:    #fffdf9;
            --cream:    #f5f0e8;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            background: var(--paper);
            color: var(--ink);
            padding: 40px;
            max-width: 210mm;
            margin: 0 auto;
        }

        /* ── PRINT BUTTON ── */
        .print-btn {
            position: fixed;
            top: 24px;
            right: 24px;
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 12px 22px;
            background: var(--espresso);
            color: var(--paper);
            border: none;
            cursor: pointer;
            font-family: 'Montserrat', sans-serif;
            font-size: 0.58rem;
            font-weight: 600;
            letter-spacing: 0.28em;
            text-transform: uppercase;
            transition: background 0.2s;
        }
        .print-btn:hover { background: var(--ink); }

        /* ── HEADER ── */
        .header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            padding-bottom: 20px;
            border-bottom: 1px solid var(--rule);
            margin-bottom: 28px;
        }
        .header-brand {
            display: flex;
            flex-direction: column;
        }
        .header-rule {
            width: 36px;
            height: 1px;
            background: var(--rule);
            margin-bottom: 10px;
        }
        .header-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.9rem;
            font-weight: 400;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            margin: 0 0 4px 0;
            color: var(--ink);
        }
        .header-sub {
            font-size: 0.52rem;
            font-weight: 600;
            letter-spacing: 0.38em;
            text-transform: uppercase;
            color: var(--label);
            margin: 0;
        }
        .header-date {
            text-align: right;
        }
        .header-date .date-label {
            font-size: 0.5rem;
            font-weight: 600;
            letter-spacing: 0.3em;
            text-transform: uppercase;
            color: var(--label);
            display: block;
            margin-bottom: 4px;
        }
        .header-date .date-value {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.1rem;
            font-weight: 400;
            color: var(--ink);
        }

        /* ── META INFO ── */
        .meta-grid {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 0;
            border: 1px solid var(--rule);
            margin-bottom: 28px;
        }
        .meta-item {
            padding: 14px 18px;
            border-right: 1px solid var(--rule);
        }
        .meta-item:last-child { border-right: none; }
        .meta-label {
            font-size: 0.48rem;
            font-weight: 600;
            letter-spacing: 0.3em;
            text-transform: uppercase;
            color: var(--label);
            display: block;
            margin-bottom: 5px;
        }
        .meta-value {
            font-size: 0.78rem;
            font-weight: 500;
            color: var(--ink);
        }

        /* ── SUMMARY ── */
        .summary {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 0;
            border: 1px solid var(--rule);
            margin-bottom: 36px;
            background: var(--cream);
        }
        .summary-item {
            padding: 22px 20px;
            border-right: 1px solid var(--rule);
            position: relative;
        }
        .summary-item:last-child { border-right: none; }
        .summary-item::before {
            content: '';
            position: absolute;
            top: 0; left: 0;
            width: 3px; height: 100%;
            background: var(--espresso);
        }
        .summary-label {
            font-size: 0.48rem;
            font-weight: 600;
            letter-spacing: 0.3em;
            text-transform: uppercase;
            color: var(--label);
            display: block;
            margin-bottom: 8px;
        }
        .summary-value {
            font-family: 'Cormorant Garamond', serif;
            font-size: 2.6rem;
            font-weight: 300;
            color: var(--ink);
            line-height: 1;
        }
        .summary-value.denda {
            font-size: 1.4rem;
        }

        /* ── SECTION HEADING ── */
        .section { margin-bottom: 32px; }
        .section-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 14px;
        }
        .section-bar {
            width: 3px;
            height: 18px;
            background: var(--espresso);
            flex-shrink: 0;
        }
        .section-title {
            font-size: 0.56rem;
            font-weight: 600;
            letter-spacing: 0.32em;
            text-transform: uppercase;
            color: var(--ink);
            margin: 0;
        }

        /* ── DATA TABLE ── */
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid var(--rule);
        }
        table.data-table thead tr {
            background: var(--cream);
            border-bottom: 1px solid var(--rule);
        }
        table.data-table th {
            padding: 10px 14px;
            text-align: left;
            font-size: 0.48rem;
            font-weight: 600;
            letter-spacing: 0.25em;
            text-transform: uppercase;
            color: var(--label);
        }
        table.data-table tbody tr {
            border-bottom: 1px solid var(--rule);
        }
        table.data-table tbody tr:last-child { border-bottom: none; }
        table.data-table td {
            padding: 10px 14px;
            font-size: 0.75rem;
            color: var(--ink);
        }
        .td-label { color: var(--label); }
        .td-bold  { font-weight: 600; }

        /* ── BADGES ── */
        .badge {
            display: inline-block;
            padding: 2px 8px;
            font-size: 0.45rem;
            font-weight: 600;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            border: 1px solid var(--rule);
            background: var(--cream);
            color: var(--label);
        }
        .badge-good {
            border-color: rgba(28,25,23,0.25);
            background: rgba(28,25,23,0.05);
            color: var(--espresso);
        }
        .badge-warn {
            border-color: rgba(74,69,64,0.25);
            background: rgba(74,69,64,0.05);
            color: var(--dim);
        }

        /* ── EMPTY STATE ── */
        .empty-row td {
            padding: 28px 14px;
            text-align: center;
            font-family: 'Cormorant Garamond', serif;
            font-size: 1rem;
            color: var(--label);
            font-style: italic;
        }

        /* ── FOOTER / SIGNATURE ── */
        .footer {
            margin-top: 60px;
            page-break-inside: avoid;
        }
        .footer-divider {
            height: 1px;
            background: var(--rule);
            margin-bottom: 40px;
        }
        .signature-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
        }
        .signature { text-align: center; }
        .signature-role {
            font-size: 0.52rem;
            font-weight: 600;
            letter-spacing: 0.25em;
            text-transform: uppercase;
            color: var(--label);
            margin-bottom: 70px;
        }
        .signature-line {
            height: 1px;
            background: var(--dim);
            margin-bottom: 8px;
        }
        .signature-name {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1rem;
            font-weight: 400;
            color: var(--ink);
        }
    </style>
</head>
<body>

    {{-- ── PRINT BUTTON ── --}}
    <button onclick="window.print()" class="print-btn no-print">
        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 9V2h12v7"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><path d="M6 14h12v8H6z"/></svg>
        Cetak Laporan
    </button>

    {{-- ── HEADER ── --}}
    <div class="header">
        <div class="header-brand">
            <div class="header-rule"></div>
            <h1 class="header-title">Sistema</h1>
            <p class="header-sub">Laporan Peminjaman Harian</p>
        </div>
        <div class="header-date">
            <span class="date-label">Tanggal Laporan</span>
            <span class="date-value">{{ date('d F Y', strtotime($tanggal)) }}</span>
        </div>
    </div>

    {{-- ── META INFO ── --}}
    <div class="meta-grid">
        <div class="meta-item">
            <span class="meta-label">Tanggal Laporan</span>
            <span class="meta-value">{{ date('d F Y', strtotime($tanggal)) }}</span>
        </div>
        <div class="meta-item">
            <span class="meta-label">Waktu Cetak</span>
            <span class="meta-value">{{ date('d F Y, H:i') }} WIB</span>
        </div>
        <div class="meta-item">
            <span class="meta-label">Dicetak Oleh</span>
            <span class="meta-value">{{ session('username', 'Administrator') }}</span>
        </div>
    </div>

    {{-- ── SUMMARY ── --}}
    <div class="summary">
        <div class="summary-item">
            <span class="summary-label">Total Peminjaman</span>
            <span class="summary-value">{{ $totalPeminjamanHariIni }}</span>
        </div>
        <div class="summary-item">
            <span class="summary-label">Total Pengembalian</span>
            <span class="summary-value">{{ $totalPengembalianHariIni }}</span>
        </div>
        <div class="summary-item">
            <span class="summary-label">Total Denda</span>
            <span class="summary-value denda">Rp {{ number_format($totalDendaHariIni, 0, ',', '.') }}</span>
        </div>
    </div>

    {{-- ── DATA PEMINJAMAN ── --}}
    <div class="section">
        <div class="section-header">
            <div class="section-bar"></div>
            <h3 class="section-title">Data Peminjaman</h3>
        </div>

        <table class="data-table">
            <thead>
                <tr>
                    <th>Waktu</th>
                    <th>Peminjam</th>
                    <th>Alat</th>
                    <th>Jumlah</th>
                    <th>Jatuh Tempo</th>
                    <th>Petugas</th>
                </tr>
            </thead>
            <tbody>
                @forelse($peminjamanHariIni as $item)
                    <tr>
                        <td class="td-bold">{{ date('H:i', strtotime($item['tgl_pinjam'])) }}</td>
                        <td>{{ $item['peminjam'] }}</td>
                        <td class="td-label">{{ $item['alat'] }}</td>
                        <td>{{ $item['jumlah'] }}</td>
                        <td class="td-label">{{ date('d/m/Y', strtotime($item['jatuh_tempo'])) }}</td>
                        <td class="td-label">{{ session('username', 'Administrator') }}</td>
                    </tr>
                @empty
                    <tr class="empty-row">
                        <td colspan="6">Tidak ada data peminjaman pada tanggal ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- ── DATA PENGEMBALIAN ── --}}
    <div class="section">
        <div class="section-header">
            <div class="section-bar"></div>
            <h3 class="section-title">Data Pengembalian</h3>
        </div>

        <table class="data-table">
            <thead>
                <tr>
                    <th>Waktu</th>
                    <th>Peminjam</th>
                    <th>Alat</th>
                    <th>Kondisi</th>
                    <th>Keterlambatan</th>
                    <th>Denda</th>
                    <th>Petugas</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pengembalianHariIni as $item)
                    <tr>
                        <td class="td-bold">{{ date('H:i', strtotime($item['tgl_kembali'])) }}</td>
                        <td>{{ $item['peminjam'] }}</td>
                        <td class="td-label">{{ $item['alat'] }}</td>
                        <td>
                            @if($item['kondisi'] == 'Baik')
                                <span class="badge badge-good">Baik</span>
                            @elseif($item['kondisi'] == 'Rusak Ringan')
                                <span class="badge badge-warn">Rusak Ringan</span>
                            @else
                                <span class="badge">{{ $item['kondisi'] }}</span>
                            @endif
                        </td>
                        <td>
                            @if($item['terlambat'] > 0)
                                <span style="font-weight:600; color:var(--espresso)">{{ $item['terlambat'] }} hari</span>
                            @else
                                <span class="td-label">Tepat waktu</span>
                            @endif
                        </td>
                        <td>
                            @if($item['denda'] > 0)
                                <span style="font-weight:600; color:var(--espresso)">Rp {{ number_format($item['denda'], 0, ',', '.') }}</span>
                            @else
                                <span class="td-label">—</span>
                            @endif
                        </td>
                        <td class="td-label">{{ session('username', 'Administrator') }}</td>
                    </tr>
                @empty
                    <tr class="empty-row">
                        <td colspan="7">Tidak ada data pengembalian pada tanggal ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- ── FOOTER / SIGNATURE ── --}}
    <div class="footer">
        <div class="footer-divider"></div>
        <div class="signature-grid">
            <div class="signature">
                <p class="signature-role">Petugas</p>
                <div class="signature-line"></div>
                <p class="signature-name">{{ session('username', 'Administrator') }}</p>
            </div>
            <div class="signature">
                <p class="signature-role">Mengetahui</p>
                <div class="signature-line"></div>
                <p class="signature-name">&nbsp;</p>
            </div>
        </div>
    </div>

</body>
</html>