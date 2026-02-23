@extends('layouts.app')

@section('title', 'Laporan Peminjaman Alat')

@section('content')

    {{-- ══ PAGE HEADER ══ --}}
    <div class="flex items-end justify-between mb-8">
        <div>
            <p class="font-sans text-[0.58rem] font-semibold tracking-[0.35em] uppercase text-label mb-1">
                Rekap & Analisis
            </p>
            <h2 class="font-serif text-ink text-3xl font-normal leading-none">
                Laporan Peminjaman
            </h2>
            <div class="mt-3 h-px w-10 bg-rule"></div>
        </div>

        <a href="{{ route('laporan.cetak', ['tanggal' => $tanggal]) }}" target="_blank"
           class="relative overflow-hidden flex items-center gap-2 bg-espresso px-5 py-3
                  font-sans text-[0.62rem] font-semibold tracking-[0.2em] uppercase text-paper
                  transition-colors duration-200 hover:bg-ink active:scale-[0.99]
                  after:content-[''] after:absolute after:inset-0 after:bg-white/[0.06]
                  after:-translate-x-full after:transition-transform after:duration-300
                  hover:after:translate-x-0">
            <i class="fas fa-print text-xs"></i>
            <span>Cetak Laporan</span>
        </a>
    </div>

    {{-- ══ FILTER TANGGAL ══ --}}
    <div class="bg-paper border border-rule p-6 mb-6 relative overflow-hidden">
        <div class="pointer-events-none absolute top-3 right-3 h-6 w-6 border-t border-r border-rule"></div>

        <p class="font-sans text-[0.55rem] font-semibold tracking-[0.28em] uppercase text-label mb-4">
            Filter Tanggal
        </p>

        <form method="GET" action="{{ route('laporan.index') }}" class="flex items-end gap-4">
            <div class="flex-1 max-w-xs relative">
                <label class="block font-sans text-[0.55rem] font-semibold tracking-[0.28em] uppercase text-label mb-2.5">
                    Pilih Tanggal
                </label>
                <input
                    type="date" name="tanggal" value="{{ $tanggal }}"
                    class="peer w-full bg-transparent border-b border-rule pb-2.5 pt-1 font-sans text-[0.85rem] text-ink outline-none transition-colors duration-200 focus:border-ink"
                >
                <span class="absolute bottom-0 left-0 h-px w-0 bg-ink transition-all duration-300 peer-focus:w-full"></span>
            </div>

            <button type="submit"
                class="flex items-center gap-2 border border-rule px-4 py-2.5 mb-0.5
                       font-sans text-[0.6rem] font-semibold tracking-[0.2em] uppercase text-label
                       hover:border-espresso hover:text-espresso transition-all duration-150">
                <i class="fas fa-search text-[0.6rem]"></i>
                <span>Tampilkan</span>
            </button>
        </form>
    </div>

    {{-- ══ RINGKASAN HARIAN ══ --}}
    <div class="bg-paper border border-rule p-6 mb-6 relative overflow-hidden">
        <div class="pointer-events-none absolute top-3 right-3 h-6 w-6 border-t border-r border-rule"></div>
        <div class="pointer-events-none absolute bottom-3 left-3 h-6 w-6 border-b border-l border-rule"></div>

        <p class="font-sans text-[0.55rem] font-semibold tracking-[0.28em] uppercase text-label mb-1">
            Ringkasan
        </p>
        <h3 class="font-serif text-ink text-xl font-normal mb-4 leading-none">
            {{ date('d F Y', strtotime($tanggal)) }}
        </h3>
        <div class="h-px w-8 bg-rule mb-6"></div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-0 divide-y md:divide-y-0 md:divide-x divide-rule">
            <div class="pb-5 md:pb-0 md:pr-8">
                <p class="font-sans text-[0.52rem] font-semibold tracking-[0.25em] uppercase text-label mb-2">
                    Total Peminjaman
                </p>
                <p class="font-serif text-ink text-5xl font-light leading-none">
                    {{ $totalPeminjamanHariIni }}
                </p>
            </div>
            <div class="py-5 md:py-0 md:px-8">
                <p class="font-sans text-[0.52rem] font-semibold tracking-[0.25em] uppercase text-label mb-2">
                    Total Pengembalian
                </p>
                <p class="font-serif text-ink text-5xl font-light leading-none">
                    {{ $totalPengembalianHariIni }}
                </p>
            </div>
            <div class="pt-5 md:pt-0 md:pl-8">
                <p class="font-sans text-[0.52rem] font-semibold tracking-[0.25em] uppercase text-label mb-2">
                    Total Denda
                </p>
                <p class="font-serif text-ink text-3xl font-light leading-none">
                    Rp {{ number_format($totalDendaHariIni, 0, ',', '.') }}
                </p>
            </div>
        </div>
    </div>

    {{-- ══ TABLE PEMINJAMAN ══ --}}
    <div class="mb-6">
        <div class="flex items-center gap-4 mb-4">
            <div class="w-1 h-5 bg-espresso flex-shrink-0"></div>
            <p class="font-sans text-[0.58rem] font-semibold tracking-[0.3em] uppercase text-ink">
                Data Peminjaman
            </p>
        </div>

        <div class="bg-paper border border-rule overflow-hidden">
            <table class="min-w-full">
                <thead>
                    <tr class="border-b border-rule bg-cream">
                        <th class="px-5 py-3.5 text-left font-sans text-[0.55rem] font-semibold tracking-[0.25em] uppercase text-label">Waktu</th>
                        <th class="px-5 py-3.5 text-left font-sans text-[0.55rem] font-semibold tracking-[0.25em] uppercase text-label">Peminjam</th>
                        <th class="px-5 py-3.5 text-left font-sans text-[0.55rem] font-semibold tracking-[0.25em] uppercase text-label">Alat</th>
                        <th class="px-5 py-3.5 text-left font-sans text-[0.55rem] font-semibold tracking-[0.25em] uppercase text-label">Jumlah</th>
                        <th class="px-5 py-3.5 text-left font-sans text-[0.55rem] font-semibold tracking-[0.25em] uppercase text-label">Jatuh Tempo</th>
                        <th class="px-5 py-3.5 text-left font-sans text-[0.55rem] font-semibold tracking-[0.25em] uppercase text-label">Petugas</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-rule">
                    @forelse($peminjamanHariIni as $item)
                        <tr class="hover:bg-cream/40 transition-colors duration-100">
                            <td class="px-5 py-4 whitespace-nowrap font-sans text-[0.78rem] text-ink font-medium">
                                {{ date('H:i', strtotime($item['tgl_pinjam'])) }}
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap font-sans text-[0.82rem] text-ink">
                                {{ $item['peminjam'] }}
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap font-sans text-[0.82rem] text-label">
                                {{ $item['alat'] }}
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap font-sans text-[0.82rem] text-ink">
                                {{ $item['jumlah'] }}
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap font-sans text-[0.82rem] text-label">
                                {{ date('d/m/Y', strtotime($item['jatuh_tempo'])) }}
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap font-sans text-[0.82rem] text-label">
                                {{ session('username', 'Administrator') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-14 text-center">
                                <div class="w-10 h-10 bg-cream border border-rule flex items-center justify-center mx-auto mb-3">
                                    <i class="fas fa-clipboard text-ghost text-sm"></i>
                                </div>
                                <p class="font-serif text-ink text-base font-normal mb-1">Tidak ada data peminjaman</p>
                                <p class="font-sans text-[0.68rem] text-label tracking-wide">Tidak ada peminjaman pada tanggal ini.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ══ TABLE PENGEMBALIAN ══ --}}
    <div>
        <div class="flex items-center gap-4 mb-4">
            <div class="w-1 h-5 bg-espresso flex-shrink-0"></div>
            <p class="font-sans text-[0.58rem] font-semibold tracking-[0.3em] uppercase text-ink">
                Data Pengembalian
            </p>
        </div>

        <div class="bg-paper border border-rule overflow-hidden">
            <table class="min-w-full">
                <thead>
                    <tr class="border-b border-rule bg-cream">
                        <th class="px-5 py-3.5 text-left font-sans text-[0.55rem] font-semibold tracking-[0.25em] uppercase text-label">Waktu</th>
                        <th class="px-5 py-3.5 text-left font-sans text-[0.55rem] font-semibold tracking-[0.25em] uppercase text-label">Peminjam</th>
                        <th class="px-5 py-3.5 text-left font-sans text-[0.55rem] font-semibold tracking-[0.25em] uppercase text-label">Alat</th>
                        <th class="px-5 py-3.5 text-left font-sans text-[0.55rem] font-semibold tracking-[0.25em] uppercase text-label">Kondisi</th>
                        <th class="px-5 py-3.5 text-left font-sans text-[0.55rem] font-semibold tracking-[0.25em] uppercase text-label">Keterlambatan</th>
                        <th class="px-5 py-3.5 text-left font-sans text-[0.55rem] font-semibold tracking-[0.25em] uppercase text-label">Denda</th>
                        <th class="px-5 py-3.5 text-left font-sans text-[0.55rem] font-semibold tracking-[0.25em] uppercase text-label">Petugas</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-rule">
                    @forelse($pengembalianHariIni as $item)
                        <tr class="hover:bg-cream/40 transition-colors duration-100">
                            <td class="px-5 py-4 whitespace-nowrap font-sans text-[0.78rem] text-ink font-medium">
                                {{ date('H:i', strtotime($item['tgl_kembali'])) }}
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap font-sans text-[0.82rem] text-ink">
                                {{ $item['peminjam'] }}
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap font-sans text-[0.82rem] text-label">
                                {{ $item['alat'] }}
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap">
                                @if($item['kondisi'] == 'Baik')
                                    <span class="px-2.5 py-1 border border-espresso/20 bg-espresso/5 font-sans text-[0.52rem] font-semibold tracking-[0.12em] uppercase text-espresso">
                                        Baik
                                    </span>
                                @elseif($item['kondisi'] == 'Rusak Ringan')
                                    <span class="px-2.5 py-1 border border-dim/30 bg-dim/5 font-sans text-[0.52rem] font-semibold tracking-[0.12em] uppercase text-dim">
                                        Rusak Ringan
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 border border-rule bg-cream font-sans text-[0.52rem] font-semibold tracking-[0.12em] uppercase text-label">
                                        {{ $item['kondisi'] }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap font-sans text-[0.82rem]">
                                @if($item['terlambat'] > 0)
                                    <span class="text-espresso font-medium">{{ $item['terlambat'] }} hari</span>
                                @else
                                    <span class="text-label">Tepat waktu</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap font-sans text-[0.82rem]">
                                @if($item['denda'] > 0)
                                    <span class="font-medium text-espresso">Rp {{ number_format($item['denda'], 0, ',', '.') }}</span>
                                @else
                                    <span class="text-label">—</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap font-sans text-[0.82rem] text-label">
                                {{ session('username', 'Administrator') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-14 text-center">
                                <div class="w-10 h-10 bg-cream border border-rule flex items-center justify-center mx-auto mb-3">
                                    <i class="fas fa-check-circle text-ghost text-sm"></i>
                                </div>
                                <p class="font-serif text-ink text-base font-normal mb-1">Tidak ada data pengembalian</p>
                                <p class="font-sans text-[0.68rem] text-label tracking-wide">Tidak ada pengembalian pada tanggal ini.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection