<header class="bg-paper border-b border-rule shadow-sm">
    <div class="mx-auto px-6 py-3.5 flex justify-between items-center">

        {{-- Brand --}}
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 bg-espresso flex items-center justify-center">
                <i class="fas fa-wrench text-paper text-sm"></i>
            </div>
            <div>
                <h1 class="font-serif text-ink text-lg font-normal leading-none tracking-wide">
                    Sistem Peminjaman Alat
                </h1>
                <p class="font-sans text-[0.52rem] tracking-[0.25em] uppercase text-label mt-0.5">
                    Platform Manajemen
                </p>
            </div>
        </div>

        {{-- Right Section --}}
        <div class="flex items-center gap-3">

            {{-- User Card --}}
            @php
                $level   = auth()->user()->level ?? 'admin';
                $icon    = match(strtolower($level)) {
                    'admin'     => 'user-shield',
                    'petugas'   => 'user-cog',
                    'peminjam'  => 'user',
                    default     => 'user',
                };
            @endphp

            <div class="flex items-center gap-3 border border-rule bg-cream px-4 py-2">
                <div class="w-8 h-8 bg-espresso flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-{{ $icon }} text-paper text-xs"></i>
                </div>
                <div>
                    <p class="font-sans text-[0.78rem] font-semibold text-ink leading-tight">
                        {{ auth()->user()->username ?? 'user' }}
                    </p>
                    <p class="font-sans text-[0.6rem] tracking-[0.15em] uppercase text-label leading-tight">
                        {{ $level }}
                    </p>
                </div>
            </div>

            {{-- Logout --}}
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button
                    type="submit"
                    class="
                        relative overflow-hidden border border-espresso bg-espresso
                        px-5 py-2.5 font-sans text-[0.62rem] font-semibold
                        tracking-[0.2em] uppercase text-paper
                        flex items-center gap-2
                        transition-colors duration-200 hover:bg-ink active:scale-[0.99]
                        after:content-[''] after:absolute after:inset-0 after:bg-white/[0.06]
                        after:-translate-x-full after:transition-transform after:duration-300
                        hover:after:translate-x-0
                    "
                >
                    <i class="fas fa-sign-out-alt text-xs"></i>
                    <span>Keluar</span>
                </button>
            </form>

        </div>
    </div>
</header>