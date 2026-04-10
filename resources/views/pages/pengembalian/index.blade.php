@extends('layouts.app')

@section('title', 'Pengembalian Alat')

@section('content')

    {{-- ══ PAGE HEADER ══ --}}
    <div class="flex items-end justify-between mb-8">
        <div>
            <p class="font-sans text-[0.58rem] font-semibold tracking-[0.35em] uppercase text-label mb-1">
                Manajemen Aset
            </p>
            <h2 class="font-serif text-ink text-3xl font-normal leading-none">
                Pengembalian Alat
            </h2>
            <div class="mt-3 h-px w-10 bg-rule"></div>
        </div>

        @if(in_array(auth()->user()->level, ['admin', 'petugas', 'peminjam']))
            <button
                onclick="openModal()"
                class="relative overflow-hidden flex items-center gap-2 bg-espresso px-5 py-3
                       font-sans text-[0.62rem] font-semibold tracking-[0.2em] uppercase text-paper
                       transition-colors duration-200 hover:bg-ink active:scale-[0.99]
                       after:content-[''] after:absolute after:inset-0 after:bg-white/[0.06]
                       after:-translate-x-full after:transition-transform after:duration-300
                       hover:after:translate-x-0"
            >
                <i class="fas fa-undo text-xs"></i>
                <span>Proses Pengembalian</span>
            </button>
        @endif
    </div>

    {{-- ══ SUCCESS ALERT ══ --}}
    @if(session('success'))
        <div class="flex items-center justify-between border-l-2 border-espresso bg-cream px-4 py-3 mb-6">
            <span class="font-sans text-[0.75rem] tracking-wide text-ink">{{ session('success') }}</span>
            <button onclick="this.parentElement.remove()" class="text-label hover:text-ink transition-colors ml-4">
                <i class="fas fa-times text-xs"></i>
            </button>
        </div>
    @endif

    {{-- ══ ERROR ALERT ══ --}}
    @if($errors->any())
        <div class="border-l-2 border-espresso bg-cream px-4 py-3 mb-6">
            @foreach($errors->all() as $error)
                <p class="font-sans text-[0.72rem] leading-relaxed tracking-wide text-ink">{{ $error }}</p>
            @endforeach
        </div>
    @endif

    {{-- ✅ NEW: Summary Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">

        {{-- Total Denda --}}
        <div class="bg-paper border border-rule p-5 group hover:border-espresso/30 transition-colors duration-200">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="font-sans text-[0.55rem] font-semibold tracking-[0.25em] uppercase text-label mb-2">Total Denda</p>
                    <p class="font-serif text-[1.3rem] font-normal leading-none text-ink">
                        Rp {{ number_format($totalDenda, 0, ',', '.') }}
                    </p>
                </div>
                <div class="w-10 h-10 bg-espresso flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-money-bill-wave text-xs text-paper"></i>
                </div>
            </div>
            <div class="mt-4 h-px w-0 bg-espresso/20 group-hover:w-full transition-all duration-500"></div>
        </div>

        {{-- Denda Belum Lunas --}}
        <div class="bg-paper border border-rule p-5 group hover:border-espresso/30 transition-colors duration-200">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="font-sans text-[0.55rem] font-semibold tracking-[0.25em] uppercase text-label mb-2">Belum Lunas</p>
                    <p class="font-serif text-[1.3rem] font-normal leading-none text-espresso">
                        Rp {{ number_format($dendaBelumLunas, 0, ',', '.') }}
                    </p>
                </div>
                <div class="w-10 h-10 bg-dim flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-exclamation-circle text-xs text-paper"></i>
                </div>
            </div>
            <div class="mt-4 h-px w-0 bg-espresso/20 group-hover:w-full transition-all duration-500"></div>
        </div>

        {{-- Alat Rusak --}}
        <div class="bg-paper border border-rule p-5 group hover:border-espresso/30 transition-colors duration-200">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="font-sans text-[0.55rem] font-semibold tracking-[0.25em] uppercase text-label mb-2">Alat Rusak</p>
                    <p class="font-serif text-[1.9rem] font-normal leading-none text-ink">{{ $alatRusak }}</p>
                </div>
                <div class="w-10 h-10 bg-rule flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-hammer text-xs text-paper"></i>
                </div>
            </div>
            <div class="mt-4 h-px w-0 bg-espresso/20 group-hover:w-full transition-all duration-500"></div>
        </div>

        {{-- Alat Hilang --}}
        <div class="bg-paper border border-rule p-5 group hover:border-espresso/30 transition-colors duration-200">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="font-sans text-[0.55rem] font-semibold tracking-[0.25em] uppercase text-label mb-2">Alat Hilang</p>
                    <p class="font-serif text-[1.9rem] font-normal leading-none text-espresso">{{ $alatHilang }}</p>
                </div>
                <div class="w-10 h-10 bg-espresso flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-search-minus text-xs text-paper"></i>
                </div>
            </div>
            <div class="mt-4 h-px w-0 bg-espresso/20 group-hover:w-full transition-all duration-500"></div>
        </div>

    </div>

    {{-- ══ TABLE ══ --}}
    <div class="bg-paper border border-rule overflow-hidden">
        <table class="min-w-full">
            <thead>
                <tr class="border-b border-rule bg-cream">
                    <th class="px-5 py-3.5 text-left font-sans text-[0.55rem] font-semibold tracking-[0.25em] uppercase text-label">Peminjam</th>
                    <th class="px-5 py-3.5 text-left font-sans text-[0.55rem] font-semibold tracking-[0.25em] uppercase text-label">Alat</th>
                    <th class="px-5 py-3.5 text-left font-sans text-[0.55rem] font-semibold tracking-[0.25em] uppercase text-label">Tgl. Kembali</th>
                    <th class="px-5 py-3.5 text-left font-sans text-[0.55rem] font-semibold tracking-[0.25em] uppercase text-label">Kondisi</th>
                    <th class="px-5 py-3.5 text-left font-sans text-[0.55rem] font-semibold tracking-[0.25em] uppercase text-label">Telat</th>
                    {{-- ✅ NEW: Denda Breakdown --}}
                    <th class="px-5 py-3.5 text-left font-sans text-[0.55rem] font-semibold tracking-[0.25em] uppercase text-label">Denda Keterlambatan</th>
                    <th class="px-5 py-3.5 text-left font-sans text-[0.55rem] font-semibold tracking-[0.25em] uppercase text-label">Denda Barang</th>
                    <th class="px-5 py-3.5 text-left font-sans text-[0.55rem] font-semibold tracking-[0.25em] uppercase text-label">Total Denda</th>
                    <th class="px-5 py-3.5 text-left font-sans text-[0.55rem] font-semibold tracking-[0.25em] uppercase text-label">Status</th>
                    @if(auth()->user()->level == 'admin')
                        <th class="px-5 py-3.5 text-left font-sans text-[0.55rem] font-semibold tracking-[0.25em] uppercase text-label">Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody class="divide-y divide-rule">
                @forelse($pengembalian as $item)
                    <tr class="hover:bg-cream/40 transition-colors duration-100">

                        {{-- Peminjam --}}
                        <td class="px-5 py-4 font-sans text-[0.78rem] font-medium text-ink whitespace-nowrap">
                            {{ $item->peminjaman->user->username ?? '—' }}
                        </td>

                        {{-- Alat --}}
                        <td class="px-5 py-4 font-sans text-[0.78rem] text-label whitespace-nowrap">
                            {{ $item->peminjaman->alat->nama_alat ?? '—' }}
                        </td>

                        {{-- Tanggal Kembali --}}
                        <td class="px-5 py-4 font-sans text-[0.78rem] text-label whitespace-nowrap">
                            {{ $item->tanggal_kembali_aktual->format('d M Y') }}
                        </td>

                        {{-- Kondisi --}}
                        <td class="px-5 py-4 whitespace-nowrap">
                            @if($item->kondisi_alat == 'baik')
                                <span class="px-2.5 py-1 border border-ink/20 bg-ink/5 font-sans text-[0.52rem] font-semibold tracking-[0.15em] uppercase text-ink">
                                    Baik
                                </span>
                            @elseif($item->kondisi_alat == 'rusak')
                                <span class="px-2.5 py-1 border border-dim/20 bg-dim/5 font-sans text-[0.52rem] font-semibold tracking-[0.15em] uppercase text-dim">
                                    ⚠️ Rusak
                                </span>
                            @else
                                <span class="px-2.5 py-1 border border-espresso/20 bg-espresso/5 font-sans text-[0.52rem] font-semibold tracking-[0.15em] uppercase text-espresso">
                                    ❌ Hilang
                                </span>
                            @endif
                        </td>

                        {{-- Keterlambatan --}}
                        <td class="px-5 py-4 whitespace-nowrap">
                            @if($item->keterlambatan_hari > 0)
                                <span class="font-sans text-[0.75rem] font-semibold text-espresso">
                                    {{ $item->keterlambatan_hari }} hari
                                </span>
                            @else
                                <span class="font-sans text-[0.75rem] text-label">
                                    Tepat waktu
                                </span>
                            @endif
                        </td>

                        {{-- ✅ NEW: Denda Keterlambatan --}}
                        <td class="px-5 py-4 whitespace-nowrap">
                            @if($item->denda_keterlambatan > 0)
                                <span class="font-sans text-[0.75rem] font-semibold text-ink">
                                    Rp {{ number_format($item->denda_keterlambatan, 0, ',', '.') }}
                                </span>
                            @else
                                <span class="font-sans text-[0.75rem] text-ghost">-</span>
                            @endif
                        </td>

                        {{-- ✅ NEW: Denda Barang --}}
                        <td class="px-5 py-4 whitespace-nowrap">
                            @if($item->denda_barang > 0)
                                <span class="font-sans text-[0.75rem] font-semibold text-espresso">
                                    Rp {{ number_format($item->denda_barang, 0, ',', '.') }}
                                </span>
                            @else
                                <span class="font-sans text-[0.75rem] text-ghost">-</span>
                            @endif
                        </td>

                        {{-- ✅ NEW: Total Denda --}}
                        <td class="px-5 py-4 whitespace-nowrap">
                            <span class="font-sans text-[0.8rem] font-bold text-ink">
                                Rp {{ number_format($item->total_denda, 0, ',', '.') }}
                            </span>
                        </td>

                        {{-- Status Denda --}}
                        <td class="px-5 py-4 whitespace-nowrap">
                            @if($item->status_denda == 'lunas')
                                <span class="px-2.5 py-1 border border-ink/20 bg-ink/5 font-sans text-[0.52rem] font-semibold tracking-[0.15em] uppercase text-ink">
                                    ✓ Lunas
                                </span>
                            @else
                                <span class="px-2.5 py-1 border border-espresso/20 bg-espresso/5 font-sans text-[0.52rem] font-semibold tracking-[0.15em] uppercase text-espresso">
                                    Belum Lunas
                                </span>
                            @endif
                        </td>

                        {{-- Aksi --}}
                        @if(auth()->user()->level == 'admin')
                            <td class="px-5 py-4 whitespace-nowrap">
                                <form action="{{ route('pengembalian.destroy', $item->pengembalian_id) }}" method="POST"
                                    class="inline" onsubmit="return confirm('Yakin ingin menghapus data pengembalian ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="w-7 h-7 flex items-center justify-center border border-rule text-ghost
                                               hover:border-espresso hover:text-espresso transition-all duration-150">
                                        <i class="fas fa-trash text-[0.6rem]"></i>
                                    </button>
                                </form>
                            </td>
                        @endif

                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="px-5 py-16 text-center">
                            <div class="w-12 h-12 bg-cream border border-rule flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-inbox text-ghost text-base"></i>
                            </div>
                            <p class="font-serif text-ink text-lg font-normal mb-1">Belum ada data pengembalian</p>
                            <p class="font-sans text-[0.7rem] text-label tracking-wide">
                                Klik tombol "Proses Pengembalian" untuk menambahkan data baru.
                            </p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- ══ MODAL PROSES PENGEMBALIAN ══ --}}
    <div id="pengembalianModal" class="hidden fixed inset-0 z-50 flex items-center justify-center px-4 py-8"
        style="background:rgba(26,23,20,0.55)">
        <div class="relative w-full max-w-md bg-paper border border-rule shadow-2xl flex flex-col max-h-[90vh] animate-fade-up">

            {{-- Modal Header --}}
            <div class="flex-shrink-0 flex items-end justify-between px-8 pt-7 pb-5 border-b border-rule">
                <div>
                    <p class="font-sans text-[0.5rem] font-semibold tracking-[0.35em] uppercase text-label mb-1">
                        Formulir
                    </p>
                    <h3 class="font-serif text-ink text-2xl font-normal leading-none">
                        Proses Pengembalian
                    </h3>
                </div>
                <button onclick="closeModal()"
                    class="w-7 h-7 flex items-center justify-center border border-rule text-ghost hover:border-espresso hover:text-ink transition-all duration-150 mb-0.5">
                    <i class="fas fa-times text-[0.6rem]"></i>
                </button>
            </div>

            {{-- Modal Body --}}
            <form action="{{ route('pengembalian.store') }}" method="POST" class="flex flex-col flex-1 overflow-hidden">
                @csrf

                <div class="flex-1 overflow-y-auto px-8 py-6 space-y-6">

                    {{-- Pilih Peminjaman --}}
                    <div>
                        <label class="block font-sans text-[0.55rem] font-semibold tracking-[0.28em] uppercase text-label mb-2.5">
                            Peminjaman <span class="text-espresso">*</span>
                        </label>
                        <div class="relative">
                            <select name="peminjaman_id" id="peminjaman_select" required
                                class="w-full appearance-none bg-cream border border-rule px-3 py-2.5 font-sans text-[0.8rem] text-ink outline-none focus:border-ink transition-colors duration-200 cursor-pointer">
                                <option value="">Pilih Peminjaman</option>
                                @foreach(\App\Models\Peminjaman::with(['user', 'alat'])->where('status', 'disetujui')->whereDoesntHave('pengembalian')->get() as $pinjam)
                                    <option value="{{ $pinjam->peminjaman_id }}"
                                        data-jatuh-tempo="{{ $pinjam->tanggal_kembali_rencana->format('Y-m-d') }}"
                                        data-user="{{ $pinjam->user->username }}"
                                        data-alat="{{ $pinjam->alat->nama_alat }}"
                                        data-harga="{{ $pinjam->alat->harga_alat }}"
                                        data-persen-rusak="{{ $pinjam->alat->persen_denda_rusak }}"
                                        data-jumlah="{{ $pinjam->jumlah }}">
                                        {{ $pinjam->user->username }} — {{ $pinjam->alat->nama_alat }}
                                        ({{ $pinjam->tanggal_peminjaman->format('d/m/Y') }})
                                    </option>
                                @endforeach
                            </select>
                            <i class="fas fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-ghost text-[0.55rem] pointer-events-none"></i>
                        </div>
                        <p id="info_peminjaman" class="font-sans text-[0.62rem] text-label mt-1.5"></p>
                    </div>

                    {{-- Tanggal Kembali --}}
                    <div class="relative">
                        <label class="block font-sans text-[0.55rem] font-semibold tracking-[0.28em] uppercase text-label mb-2.5">
                            Tanggal Kembali <span class="text-espresso">*</span>
                        </label>
                        <input type="date" id="tanggal_kembali" name="tanggal_kembali_aktual" required
                            value="{{ date('Y-m-d') }}"
                            class="peer w-full bg-transparent border-b border-rule pb-2.5 pt-1 font-sans text-[0.85rem] text-ink outline-none transition-colors duration-200 focus:border-ink">
                        <span class="absolute bottom-0 left-0 h-px w-0 bg-ink transition-all duration-300 peer-focus:w-full"></span>
                        <p id="info_keterlambatan" class="font-sans text-[0.65rem] mt-1.5"></p>
                    </div>

                    {{-- Kondisi Alat --}}
                    <div>
                        <label class="block font-sans text-[0.55rem] font-semibold tracking-[0.28em] uppercase text-label mb-2.5">
                            Kondisi Alat <span class="text-espresso">*</span>
                        </label>
                        <div class="relative">
                            <select name="kondisi_alat" required
                                class="w-full appearance-none bg-cream border border-rule px-3 py-2.5 font-sans text-[0.8rem] text-ink outline-none focus:border-ink transition-colors duration-200 cursor-pointer">
                                <option value="">Pilih Kondisi</option>
                                <option value="baik">Baik</option>
                                <option value="rusak">Rusak</option>
                                <option value="hilang">Hilang</option>
                            </select>
                            <i class="fas fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-ghost text-[0.55rem] pointer-events-none"></i>
                        </div>
                    </div>

                    {{-- Keterangan --}}
                    <div>
                        <label class="block font-sans text-[0.55rem] font-semibold tracking-[0.28em] uppercase text-label mb-2.5">
                            Keterangan
                        </label>
                        <textarea name="keterangan" rows="3" placeholder="Catatan tambahan (opsional)"
                            class="w-full bg-cream border border-rule px-3 py-2.5 font-sans text-[0.82rem] text-ink outline-none placeholder-ghost/60 focus:border-ink transition-colors duration-200 resize-none"></textarea>
                    </div>

                    {{-- ✅ NEW: Breakdown Denda (Real-time Display) --}}
                    <div id="dendaBreakdown" style="display: none;">
                        <div class="bg-cream border border-rule p-4 rounded">
                            <p class="font-sans text-[0.55rem] font-semibold tracking-[0.28em] uppercase text-label mb-3">
                                📊 Breakdown Denda
                            </p>
                            
                            {{-- Keterlambatan --}}
                            <div class="flex justify-between items-center mb-2 pb-2 border-b border-rule">
                                <span class="font-sans text-[0.75rem] text-label">
                                    Keterlambatan:
                                    <span id="hari_telat" class="font-semibold text-ink">0 hari</span>
                                    × Rp 50.000/hari
                                </span>
                                <span id="denda_hari" class="font-sans text-[0.8rem] font-semibold text-ink">
                                    Rp 0
                                </span>
                            </div>

                            {{-- Barang (Conditional) --}}
                            <div id="dendaBarangSection" style="display: none;">
                                <div class="flex justify-between items-center mb-2 pb-2 border-b border-rule">
                                    <span class="font-sans text-[0.75rem] text-label">
                                        Denda Barang:
                                        <span id="desc_barang" class="font-semibold text-ink">-</span>
                                    </span>
                                    <span id="denda_barang" class="font-sans text-[0.8rem] font-semibold text-dim">
                                        Rp 0
                                    </span>
                                </div>
                            </div>

                            {{-- Total --}}
                            <div class="flex justify-between items-center pt-2">
                                <span class="font-sans text-[0.8rem] font-bold text-ink">
                                    TOTAL DENDA:
                                </span>
                                <span id="total_denda" class="font-sans text-[1rem] font-bold text-espresso">
                                    Rp 0
                                </span>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- Modal Footer --}}
                <div class="flex-shrink-0 flex gap-3 px-8 py-5 border-t border-rule bg-paper">
                    <button type="submit"
                        class="flex-1 bg-espresso text-paper font-sans text-[0.6rem] font-semibold tracking-[0.25em] uppercase py-3.5 hover:bg-ink transition-colors duration-200">
                        Proses
                    </button>
                    <button type="button" onclick="closeModal()"
                        class="flex-1 border border-rule text-label font-sans text-[0.6rem] font-semibold tracking-[0.25em] uppercase py-3.5 hover:border-espresso hover:text-espresso transition-all duration-200">
                        Batal
                    </button>
                </div>
            </form>

        </div>
    </div>

    <script>
        function openModal() {
            document.getElementById('pengembalianModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('pengembalianModal').classList.add('hidden');
        }

        // ✅ NEW: Real-time Denda Calculation
        const peminjamanSelect = document.getElementById('peminjaman_select');
        const tanggalKembali = document.getElementById('tanggal_kembali');
        const kondisiSelect = document.querySelector('select[name="kondisi_alat"]');
        const dendaBreakdown = document.getElementById('dendaBreakdown');
        const infoPeminjaman = document.getElementById('info_peminjaman');
        const infoKeterlambatan = document.getElementById('info_keterlambatan');

        function hitungDenda() {
            const selected = peminjamanSelect.options[peminjamanSelect.selectedIndex];
            const jatuhTempo = selected.getAttribute('data-jatuh-tempo');
            const user = selected.getAttribute('data-user');
            const alat = selected.getAttribute('data-alat');
            
            if (!jatuhTempo || !tanggalKembali.value) {
                dendaBreakdown.style.display = 'none';
                infoPeminjaman.textContent = '';
                infoKeterlambatan.textContent = '';
                return;
            }

            // Info Peminjaman
            infoPeminjaman.textContent = user + ' — ' + alat;

            // Hitung keterlambatan
            const tempo = new Date(jatuhTempo);
            const kembali = new Date(tanggalKembali.value);
            const keterlambatan = Math.max(0, Math.ceil((kembali - tempo) / (1000 * 60 * 60 * 24)));

            // Denda keterlambatan (Fixed: 50.000/hari)
            const dendaKeterlambatan = keterlambatan * 50000;

            // Data barang (dari data attribute)
            const hargaAlat = parseFloat(selected.getAttribute('data-harga') || 0);
            const persenRusak = parseInt(selected.getAttribute('data-persen-rusak') || 30);
            const jumlah = parseInt(selected.getAttribute('data-jumlah') || 1);
            const kondisi = kondisiSelect.value;

            // Hitung denda barang
            let dendaBarang = 0;
            let descBarang = '';

            if (kondisi === 'baik') {
                dendaBarang = 0;
                descBarang = 'Tidak ada denda';
            } else if (kondisi === 'rusak') {
                dendaBarang = (hargaAlat * (persenRusak / 100)) * jumlah;
                descBarang = `Rusak (${persenRusak}% dari Rp ${hargaAlat.toLocaleString('id-ID')})`;
            } else if (kondisi === 'hilang') {
                dendaBarang = hargaAlat * jumlah;
                descBarang = `Hilang (100% Ganti Rugi Rp ${hargaAlat.toLocaleString('id-ID')})`;
            }

            // Total denda
            const totalDenda = dendaKeterlambatan + dendaBarang;

            // Update display
            document.getElementById('hari_telat').textContent = keterlambatan + ' hari';
            document.getElementById('denda_hari').textContent = 'Rp ' + dendaKeterlambatan.toLocaleString('id-ID');
            document.getElementById('total_denda').textContent = 'Rp ' + totalDenda.toLocaleString('id-ID');

            if (dendaBarang > 0) {
                document.getElementById('dendaBarangSection').style.display = 'block';
                document.getElementById('desc_barang').textContent = descBarang;
                document.getElementById('denda_barang').textContent = 'Rp ' + dendaBarang.toLocaleString('id-ID');
            } else {
                document.getElementById('dendaBarangSection').style.display = 'none';
            }

            // Info keterlambatan
            if (keterlambatan > 0) {
                infoKeterlambatan.innerHTML = `
                    <span style="color:#1c1917;font-weight:600;">
                        Terlambat ${keterlambatan} hari &nbsp;·&nbsp; 
                        Denda: Rp ${dendaKeterlambatan.toLocaleString('id-ID')}
                    </span>
                `;
            } else {
                infoKeterlambatan.innerHTML = '<span style="color:#6e665e;">Tepat waktu</span>';
            }

            dendaBreakdown.style.display = 'block';
        }

        // Trigger calculation on change
        peminjamanSelect.addEventListener('change', hitungDenda);
        tanggalKembali.addEventListener('change', hitungDenda);
        kondisiSelect.addEventListener('change', hitungDenda);

        window.onclick = function(event) {
            const modal = document.getElementById('pengembalianModal');
            if (event.target == modal) closeModal();
        }
    </script>

@endsection