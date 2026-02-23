@extends('layouts.app')

@section('title', 'Log Aktivitas')

@section('content')

    {{-- ══ PAGE HEADER ══ --}}
    <div class="mb-8">
        <p class="font-sans text-[0.58rem] font-semibold tracking-[0.35em] uppercase text-label mb-1">
            Sistem
        </p>
        <h2 class="font-serif text-ink text-3xl font-normal leading-none">
            Log Aktivitas
        </h2>
        <div class="mt-3 h-px w-10 bg-rule"></div>
    </div>

    {{-- ══ TABLE ══ --}}
    <div class="bg-paper border border-rule overflow-hidden">
        <table class="min-w-full">
            <thead>
                <tr class="border-b border-rule bg-cream">
                    <th class="px-5 py-3.5 text-left font-sans text-[0.55rem] font-semibold tracking-[0.25em] uppercase text-label">
                        Waktu
                    </th>
                    <th class="px-5 py-3.5 text-left font-sans text-[0.55rem] font-semibold tracking-[0.25em] uppercase text-label">
                        User
                    </th>
                    <th class="px-5 py-3.5 text-left font-sans text-[0.55rem] font-semibold tracking-[0.25em] uppercase text-label">
                        Username
                    </th>
                    <th class="px-5 py-3.5 text-left font-sans text-[0.55rem] font-semibold tracking-[0.25em] uppercase text-label">
                        Level
                    </th>
                    <th class="px-5 py-3.5 text-left font-sans text-[0.55rem] font-semibold tracking-[0.25em] uppercase text-label">
                        Aksi
                    </th>
                    <th class="px-5 py-3.5 text-left font-sans text-[0.55rem] font-semibold tracking-[0.25em] uppercase text-label">
                        Deskripsi
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-rule">
                @forelse($logs as $log)
                    <tr class="hover:bg-cream/40 transition-colors duration-100">

                        {{-- Waktu --}}
                        <td class="px-5 py-4 whitespace-nowrap">
                            <span class="font-sans text-[0.75rem] text-label tracking-wide">
                                {{ optional($log->timestamp)->format('d-m-Y') ?? '-' }}
                            </span>
                            <span class="block font-sans text-[0.65rem] text-ghost tracking-wide">
                                {{ optional($log->timestamp)->format('H:i:s') ?? '' }}
                            </span>
                        </td>

                        {{-- Nama User --}}
                        <td class="px-5 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <div class="w-7 h-7 bg-espresso flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-user text-paper text-[0.55rem]"></i>
                                </div>
                                <span class="font-sans text-[0.82rem] text-ink">
                                    {{ optional($log->user)->nama_lengkap ?? 'User #' . ($log->user_id ?? 'N/A') }}
                                </span>
                            </div>
                        </td>

                        {{-- Username --}}
                        <td class="px-5 py-4 whitespace-nowrap">
                            <span class="font-sans text-[0.82rem] font-medium text-ink">
                                {{ optional($log->user)->username ?? '-' }}
                            </span>
                        </td>

                        {{-- Level Badge --}}
                        <td class="px-5 py-4 whitespace-nowrap">
                            @php $level = optional($log->user)->level; @endphp
                            @if(strtolower($level ?? '') === 'admin')
                                <span class="px-2.5 py-1 border border-espresso/30 bg-espresso/5 font-sans text-[0.52rem] font-semibold tracking-[0.15em] uppercase text-espresso">
                                    Admin
                                </span>
                            @elseif(strtolower($level ?? '') === 'petugas')
                                <span class="px-2.5 py-1 border border-dim/30 bg-dim/5 font-sans text-[0.52rem] font-semibold tracking-[0.15em] uppercase text-dim">
                                    Petugas
                                </span>
                            @elseif(strtolower($level ?? '') === 'peminjam')
                                <span class="px-2.5 py-1 border border-rule bg-cream font-sans text-[0.52rem] font-semibold tracking-[0.15em] uppercase text-label">
                                    Peminjam
                                </span>
                            @else
                                <span class="font-sans text-[0.75rem] text-ghost">—</span>
                            @endif
                        </td>

                        {{-- Aksi --}}
                        <td class="px-5 py-4 whitespace-nowrap">
                            <span class="font-sans text-[0.82rem] text-ink">
                                {{ $log->aktivitas ?? '-' }}
                            </span>
                        </td>

                        {{-- Deskripsi --}}
                        <td class="px-5 py-4">
                            <span class="font-sans text-[0.78rem] leading-relaxed text-label">
                                {{ $log->modul ?? '-' }}
                            </span>
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-16 text-center">
                            <div class="w-12 h-12 bg-cream border border-rule flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-book text-ghost text-base"></i>
                            </div>
                            <p class="font-serif text-ink text-lg font-normal mb-1">Belum ada log aktivitas</p>
                            <p class="font-sans text-[0.7rem] text-label tracking-wide">
                                Aktivitas sistem akan tercatat secara otomatis di sini.
                            </p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- ══ PAGINATION ══ --}}
        @if($logs->hasPages())
            <div class="bg-cream px-5 py-3 border-t border-rule">
                {{ $logs->links() }}
            </div>
        @endif

    </div>

@endsection