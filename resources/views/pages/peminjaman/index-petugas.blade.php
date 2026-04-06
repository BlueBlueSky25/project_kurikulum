{{-- Cari bagian foreach $peminjaman dan ubah jadi: --}}

@foreach($peminjaman as $item)
    <div class="px-6 py-5 hover:bg-cream/50 transition-colors duration-150">

        {{-- Top Row --}}
        <div class="flex items-start justify-between gap-4 mb-3">
            <div class="flex-1 min-w-0">
                <h4 class="font-serif text-ink text-base font-normal leading-snug truncate">
                    {{ $item->alat->nama_alat }}
                </h4>
                <p class="font-sans text-[0.62rem] text-label tracking-wide mt-0.5">
                    {{ $item->tanggal_peminjaman->format('d M Y') }}
                    <span class="mx-1 text-ghost">→</span>
                    {{ $item->tanggal_kembali_rencana->format('d M Y') }}
                </p>
                {{-- Tampilkan kode peminjaman --}}
                <p class="font-mono text-[0.6rem] text-espresso font-semibold mt-1">
                    {{ $item->kode_peminjaman }}
                </p>
            </div>

            {{-- Status Badge --}}
            @if($item->status == 'disetujui')
                <span class="flex-shrink-0 px-2.5 py-1 border border-ink/20 bg-ink/5 font-sans text-[0.55rem] font-semibold tracking-[0.15em] uppercase text-ink">
                    Disetujui
                </span>
            @elseif($item->status == 'menunggu')
                <span class="flex-shrink-0 px-2.5 py-1 border border-dim/20 bg-dim/5 font-sans text-[0.55rem] font-semibold tracking-[0.15em] uppercase text-dim">
                    Menunggu
                </span>
            @elseif($item->status == 'ditolak')
                <span class="flex-shrink-0 px-2.5 py-1 border border-espresso/20 bg-espresso/5 font-sans text-[0.55rem] font-semibold tracking-[0.15em] uppercase text-espresso">
                    Ditolak
                </span>
            @elseif($item->status == 'dikembalikan')
                <span class="flex-shrink-0 px-2.5 py-1 border border-rule bg-cream font-sans text-[0.55rem] font-semibold tracking-[0.15em] uppercase text-label">
                    Dikembalikan
                </span>
            @endif
        </div>

        {{-- Meta Info --}}
        <div class="grid grid-cols-2 gap-3 mb-3">
            <div class="bg-cream px-3 py-2">
                <p class="font-sans text-[0.52rem] font-semibold tracking-[0.2em] uppercase text-ghost mb-1">
                    Peminjam
                </p>
                <p class="font-sans text-[0.78rem] font-medium text-ink">
                    {{-- KONDISIONAL: Guest atau User --}}
                    @if($item->isGuest())
                        <span class="inline-block px-2 py-0.5 bg-cream border border-label/30 text-[0.65rem] rounded mr-1">GUEST</span>
                        {{ $item->nama_peminjam_guest }}
                    @else
                        {{ $item->user->username ?? '-' }}
                    @endif
                </p>
            </div>
            <div class="bg-cream px-3 py-2">
                <p class="font-sans text-[0.52rem] font-semibold tracking-[0.2em] uppercase text-ghost mb-1">
                    Jumlah
                </p>
                <p class="font-sans text-[0.78rem] font-medium text-ink">
                    {{ $item->jumlah }} unit
                </p>
            </div>
        </div>

        {{-- Telepon (buat guest) --}}
        @if($item->isGuest())
            <p class="font-sans text-[0.7rem] text-label mb-2">
                <i class="fas fa-phone text-espresso mr-1"></i>
                <strong>Kontak:</strong> {{ $item->telepon_peminjam_guest }}
            </p>
        @endif

        {{-- Tujuan --}}
        @if($item->tujuan_peminjaman)
            <p class="font-sans text-[0.7rem] text-label leading-relaxed mb-2">
                <span class="font-semibold text-dim">Tujuan:</span>
                {{ $item->tujuan_peminjaman }}
            </p>
        @endif

        {{-- Action Buttons (Approve/Reject) --}}
        @if($item->status == 'menunggu')
            <div class="flex gap-2 mt-3">
                <form action="{{ route('peminjaman.approve', $item->peminjaman_id) }}" method="POST" class="inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="px-3 py-1.5 bg-ink text-paper font-sans text-[0.6rem] font-semibold tracking-wide hover:bg-espresso transition-colors">
                        <i class="fas fa-check mr-1"></i> Setujui
                    </button>
                </form>
                <form action="{{ route('peminjaman.update', $item->peminjaman_id) }}" method="POST" class="inline">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status" value="ditolak">
                    <button type="submit" class="px-3 py-1.5 bg-espresso text-paper font-sans text-[0.6rem] font-semibold tracking-wide hover:bg-ink transition-colors">
                        <i class="fas fa-times mr-1"></i> Tolak
                    </button>
                </form>
            </div>
        @endif

    </div>
@endforeach