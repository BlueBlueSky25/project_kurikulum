@php
    $userLevel = strtolower(auth()->user()->level ?? '');
@endphp

<aside class="w-60 flex-shrink-0 min-h-screen bg-espresso flex flex-col">

    {{-- Sidebar Header --}}
    <div class="px-6 py-6 border-b border-white/10">
        <p class="font-sans text-[0.5rem] font-semibold tracking-[0.35em] uppercase text-paper/30">
            Navigasi Utama
        </p>
    </div>

    {{-- Nav Items --}}
    <nav class="flex-1 px-3 py-4 space-y-0.5">

        {{-- Dashboard --}}
        <a href="{{ route('dashboard') }}"
            @if(request()->routeIs('dashboard'))
                class="flex items-center gap-3 px-4 py-3 font-sans text-[0.72rem] font-medium tracking-wide bg-paper/10 text-paper border-l-2 border-paper/60 transition-all duration-150"
            @else
                class="flex items-center gap-3 px-4 py-3 font-sans text-[0.72rem] font-medium tracking-wide text-paper/50 hover:bg-paper/5 hover:text-paper/80 border-l-2 border-transparent transition-all duration-150"
            @endif
        >
            <i class="fas fa-chart-bar text-xs w-4 text-center"></i>
            <span>Dashboard</span>
        </a>

        {{-- Alat --}}
        @if(in_array($userLevel, ['admin', 'peminjam']))
            <a href="{{ route('alat.index') }}"
                @if(request()->routeIs('alat.*'))
                    class="flex items-center gap-3 px-4 py-3 font-sans text-[0.72rem] font-medium tracking-wide bg-paper/10 text-paper border-l-2 border-paper/60 transition-all duration-150"
                @else
                    class="flex items-center gap-3 px-4 py-3 font-sans text-[0.72rem] font-medium tracking-wide text-paper/50 hover:bg-paper/5 hover:text-paper/80 border-l-2 border-transparent transition-all duration-150"
                @endif
            >
                <i class="fas fa-wrench text-xs w-4 text-center"></i>
                <span>Alat</span>
            </a>
        @endif

        {{-- Peminjaman --}}
        @if(in_array($userLevel, ['admin', 'petugas', 'peminjam']))
            <a href="{{ route('peminjaman.index') }}"
                @if(request()->routeIs('peminjaman.*'))
                    class="flex items-center gap-3 px-4 py-3 font-sans text-[0.72rem] font-medium tracking-wide bg-paper/10 text-paper border-l-2 border-paper/60 transition-all duration-150"
                @else
                    class="flex items-center gap-3 px-4 py-3 font-sans text-[0.72rem] font-medium tracking-wide text-paper/50 hover:bg-paper/5 hover:text-paper/80 border-l-2 border-transparent transition-all duration-150"
                @endif
            >
                <i class="fas fa-clipboard-list text-xs w-4 text-center"></i>
                <span>Peminjaman</span>
            </a>
        @endif

        {{-- Pengembalian --}}
        @if(in_array($userLevel, ['admin', 'petugas', 'peminjam']))
            <a href="{{ route('pengembalian.index') }}"
                @if(request()->routeIs('pengembalian.*'))
                    class="flex items-center gap-3 px-4 py-3 font-sans text-[0.72rem] font-medium tracking-wide bg-paper/10 text-paper border-l-2 border-paper/60 transition-all duration-150"
                @else
                    class="flex items-center gap-3 px-4 py-3 font-sans text-[0.72rem] font-medium tracking-wide text-paper/50 hover:bg-paper/5 hover:text-paper/80 border-l-2 border-transparent transition-all duration-150"
                @endif
            >
                <i class="fas fa-undo text-xs w-4 text-center"></i>
                <span>Pengembalian</span>
            </a>
        @endif

        {{-- Pengguna - Admin Only --}}
        @if($userLevel === 'admin')
            <a href="{{ route('users.index') }}"
                @if(request()->routeIs('users.*'))
                    class="flex items-center gap-3 px-4 py-3 font-sans text-[0.72rem] font-medium tracking-wide bg-paper/10 text-paper border-l-2 border-paper/60 transition-all duration-150"
                @else
                    class="flex items-center gap-3 px-4 py-3 font-sans text-[0.72rem] font-medium tracking-wide text-paper/50 hover:bg-paper/5 hover:text-paper/80 border-l-2 border-transparent transition-all duration-150"
                @endif
            >
                <i class="fas fa-users text-xs w-4 text-center"></i>
                <span>Pengguna</span>
            </a>
        @endif

        {{-- Kategori - Admin Only --}}
        @if($userLevel === 'admin')
            <a href="{{ route('kategori.index') }}"
                @if(request()->routeIs('kategori.*'))
                    class="flex items-center gap-3 px-4 py-3 font-sans text-[0.72rem] font-medium tracking-wide bg-paper/10 text-paper border-l-2 border-paper/60 transition-all duration-150"
                @else
                    class="flex items-center gap-3 px-4 py-3 font-sans text-[0.72rem] font-medium tracking-wide text-paper/50 hover:bg-paper/5 hover:text-paper/80 border-l-2 border-transparent transition-all duration-150"
                @endif
            >
                <i class="fas fa-folder text-xs w-4 text-center"></i>
                <span>Kategori</span>
            </a>
        @endif

        {{-- Laporan - Admin & Petugas --}}
        @if(in_array($userLevel, ['admin', 'petugas']))
            <a href="{{ route('laporan.index') }}"
                @if(request()->routeIs('laporan.*'))
                    class="flex items-center gap-3 px-4 py-3 font-sans text-[0.72rem] font-medium tracking-wide bg-paper/10 text-paper border-l-2 border-paper/60 transition-all duration-150"
                @else
                    class="flex items-center gap-3 px-4 py-3 font-sans text-[0.72rem] font-medium tracking-wide text-paper/50 hover:bg-paper/5 hover:text-paper/80 border-l-2 border-transparent transition-all duration-150"
                @endif
            >
                <i class="fas fa-chart-line text-xs w-4 text-center"></i>
                <span>Laporan</span>
            </a>
        @endif

        {{-- Log Aktivitas - Admin Only --}}
        @if($userLevel === 'admin')
            <a href="{{ route('log.index') }}"
                @if(request()->routeIs('log.*'))
                    class="flex items-center gap-3 px-4 py-3 font-sans text-[0.72rem] font-medium tracking-wide bg-paper/10 text-paper border-l-2 border-paper/60 transition-all duration-150"
                @else
                    class="flex items-center gap-3 px-4 py-3 font-sans text-[0.72rem] font-medium tracking-wide text-paper/50 hover:bg-paper/5 hover:text-paper/80 border-l-2 border-transparent transition-all duration-150"
                @endif
            >
                <i class="fas fa-book text-xs w-4 text-center"></i>
                <span>Log Aktivitas</span>
            </a>
        @endif

    </nav>

    {{-- Sidebar Footer --}}
    <div class="px-6 py-5 border-t border-white/10">
        <p class="font-sans text-[0.5rem] tracking-[0.2em] uppercase text-paper/20">
            &copy; {{ date('Y') }} &nbsp;·&nbsp; Akses Terbatas
        </p>
    </div>

</aside>