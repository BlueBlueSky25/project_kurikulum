@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

    {{-- Page Header --}}
    <div class="mb-8">
        <p class="font-sans text-[0.58rem] font-semibold tracking-[0.35em] uppercase text-label mb-1">
            Ringkasan Sistem
        </p>
        <h2 class="font-serif text-ink text-3xl font-normal leading-none">
            Dashboard
        </h2>
        <div class="mt-3 h-px w-10 bg-rule"></div>
    </div>

    {{-- KPI Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">

        @if($userLevel == 'admin')
            <x-kpi-card
                title="Total Pengguna"
                value="{{ $totalUsers }}"
                icon="fa-users"
                color="blue"
            />
        @endif

        @if($userLevel == 'admin' || $userLevel == 'petugas')
            <x-kpi-card
                title="Total Alat"
                value="{{ $totalAlat }}"
                icon="fa-wrench"
                color="green"
            />
        @endif

        <x-kpi-card
            title="Peminjaman Pending"
            value="{{ $peminjamanPending }}"
            icon="fa-hourglass-half"
            color="yellow"
        />

        <x-kpi-card
            title="Peminjaman Aktif"
            value="{{ $peminjamanAktif }}"
            icon="fa-clipboard-check"
            color="purple"
        />

        <x-kpi-card
            title="Total Pengembalian"
            value="{{ $totalPengembalian }}"
            icon="fa-check-circle"
            color="blue"
        />

        <x-kpi-card
            title="Total Denda"
            value="Rp {{ number_format($totalDenda, 0, ',', '.') }}"
            icon="fa-money-bill-wave"
            color="red"
        />

    </div>

    {{-- Welcome Card --}}
    <div class="bg-paper border border-rule p-8 relative overflow-hidden">

        {{-- Decorative corner --}}
        <div class="pointer-events-none absolute top-5 right-5 h-9 w-9 border-t border-r border-rule"></div>
        <div class="pointer-events-none absolute bottom-5 left-5 h-9 w-9 border-b border-l border-rule"></div>

        <p class="font-sans text-[0.55rem] font-semibold tracking-[0.3em] uppercase text-label mb-3">
            Informasi
        </p>
        <h3 class="font-serif text-ink text-2xl font-normal mb-3 leading-snug">
            Selamat Datang,
            <span class="italic text-espresso">{{ auth()->user()->username ?? 'Pengguna' }}</span>
        </h3>
        <div class="h-px w-8 bg-rule mb-4"></div>
        <p class="font-sans text-[0.78rem] leading-relaxed tracking-wide text-label max-w-xl">
            @if($userLevel == 'admin')
                Sistem Peminjaman Alat ini membantu Anda mengelola peminjaman alat dengan mudah.
                Gunakan menu di sidebar untuk mengakses berbagai fitur administrasi.
            @elseif($userLevel == 'petugas')
                Anda dapat menyetujui peminjaman, memantau pengembalian, dan mencetak laporan
                melalui menu di sidebar.
            @else
                Anda dapat melihat daftar alat yang tersedia dan mengajukan peminjaman
                melalui menu di sidebar.
            @endif
        </p>

    </div>

@endsection