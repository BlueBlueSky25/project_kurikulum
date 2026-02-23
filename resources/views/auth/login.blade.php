<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Sistem Manajemen</title>

    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&family=Montserrat:wght@300;400;500;600&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        serif: ['Cormorant Garamond', 'Georgia', 'serif'],
                        sans:  ['Montserrat', 'sans-serif'],
                    },
                    colors: {
                        espresso: '#1c1917',
                        ink:      '#1a1714',
                        dim:      '#4a4540',
                        label:    '#6e665e',
                        rule:     '#c8bfb0',
                        ghost:    '#a89f94',
                        paper:    '#fffdf9',
                        cream:    '#f5f0e8',
                    },
                    animation: {
                        'fade-up': 'fadeUp 0.65s ease both',
                    },
                    keyframes: {
                        fadeUp: {
                            '0%':   { opacity: '0', transform: 'translateY(16px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        },
                    },
                }
            }
        }
    </script>
</head>
<body class="font-sans bg-paper flex min-h-screen overflow-hidden">

    {{-- ══ PANEL KIRI ══ --}}
    <aside class="
        relative hidden lg:flex w-[42%] flex-shrink-0 flex-col justify-between
        bg-espresso overflow-hidden px-14 py-14
        before:content-[''] before:absolute before:inset-0
        before:[background-image:linear-gradient(rgba(255,253,249,0.04)_1px,transparent_1px),linear-gradient(90deg,rgba(255,253,249,0.04)_1px,transparent_1px)]
        before:[background-size:44px_44px] before:pointer-events-none
        after:content-[''] after:absolute after:w-[480px] after:h-[480px]
        after:border after:border-white/[0.06] after:rounded-full
        after:-right-[160px] after:top-1/2 after:-translate-y-1/2 after:pointer-events-none
    ">
        {{-- Brand --}}
        <div class="relative z-10">
            <div class="mb-4 h-px w-9 bg-rule"></div>
            <h1 class="font-serif text-paper text-2xl font-light tracking-[0.28em] uppercase">
                Sistema
            </h1>
            <span class="mt-1.5 block font-sans text-[0.58rem] tracking-[0.4em] uppercase text-paper/40">
                Platform Manajemen
            </span>
        </div>

        {{-- Quote --}}
        <div class="relative z-10">
            <blockquote class="font-serif text-paper/90 text-[2.2rem] font-light italic leading-snug max-w-[280px]">
                "Keteraturan adalah tanda dari sebuah keahlian."
            </blockquote>
            <cite class="mt-6 block font-sans text-[0.6rem] font-medium tracking-[0.3em] uppercase text-paper/40 not-italic">
                — Prinsip Keunggulan
            </cite>
        </div>

        {{-- Footer --}}
        <div class="relative z-10">
            <p class="font-sans text-[0.58rem] tracking-[0.2em] uppercase text-paper/25">
                &copy; {{ date('Y') }} &nbsp;·&nbsp; Akses Terbatas
            </p>
        </div>
    </aside>

    {{-- ══ PANEL KANAN ══ --}}
    <div class="relative flex flex-1 items-center justify-center px-10 py-16 bg-paper lg:px-20">

        {{-- Corner ornaments --}}
        <div class="pointer-events-none absolute top-10 right-10 h-[48px] w-[48px] border-t border-r border-rule"></div>
        <div class="pointer-events-none absolute bottom-10 left-10 h-[48px] w-[48px] border-b border-l border-rule"></div>

        <div class="w-full max-w-sm animate-fade-up">

            {{-- Form Header --}}
            <div class="mb-12">
                <p class="mb-3 font-sans text-[0.58rem] font-semibold tracking-[0.35em] uppercase text-label">
                    Portal Aman
                </p>
                <h2 class="font-serif text-ink text-[2.75rem] font-normal leading-none">
                    Login
                </h2>
                <div class="mt-5 h-px w-10 bg-rule"></div>
            </div>

            {{-- Error Messages --}}
            @if ($errors->any())
                <div class="mb-8 border-l-2 border-espresso bg-cream px-4 py-3">
                    @foreach ($errors->all() as $error)
                        <p class="font-sans text-[0.72rem] leading-relaxed tracking-wide text-ink">
                            {{ $error }}
                        </p>
                    @endforeach
                </div>
            @endif

            {{-- Form --}}
            <form method="POST" action="{{ route('login.post') }}" novalidate>
                @csrf

                {{-- Username --}}
                <div class="relative mb-8">
                    <label for="username"
                        class="mb-2.5 block font-sans text-[0.58rem] font-semibold tracking-[0.3em] uppercase text-label">
                        Nama Pengguna
                    </label>
                    <input
                        type="text"
                        id="username"
                        name="username"
                        value="{{ old('username') }}"
                        placeholder="Masukkan nama pengguna"
                        autocomplete="username"
                        required
                        class="peer w-full border-b border-rule bg-transparent pb-2.5 pt-1 font-sans text-[0.9rem] tracking-wide text-ink outline-none placeholder-ghost transition-colors duration-200 focus:border-ink"
                    >
                    <span class="absolute bottom-0 left-0 h-px w-0 bg-ink transition-all duration-[350ms] peer-focus:w-full"></span>
                </div>

                {{-- Password --}}
                <div class="relative mb-8">
                    <label for="password"
                        class="mb-2.5 block font-sans text-[0.58rem] font-semibold tracking-[0.3em] uppercase text-label">
                        Kata Sandi
                    </label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Masukkan kata sandi"
                        autocomplete="current-password"
                        required
                        class="peer w-full border-b border-rule bg-transparent pb-2.5 pt-1 font-sans text-[0.9rem] tracking-wide text-ink outline-none placeholder-ghost transition-colors duration-200 focus:border-ink"
                    >
                    <span class="absolute bottom-0 left-0 h-px w-0 bg-ink transition-all duration-[350ms] peer-focus:w-full"></span>
                </div>

                {{-- Submit --}}
                <button
                    type="submit"
                    class="
                        relative mt-10 w-full overflow-hidden
                        bg-espresso px-6 py-4
                        font-sans text-[0.62rem] font-semibold tracking-[0.35em] uppercase text-paper
                        transition-colors duration-300 hover:bg-ink active:scale-[0.99]
                        after:content-[''] after:absolute after:inset-0 after:bg-white/[0.06]
                        after:-translate-x-full after:transition-transform after:duration-[350ms]
                        hover:after:translate-x-0
                    "
                >
                    Masuk ke Sistem
                </button>

            </form>

            {{-- Footer Note --}}
            <p class="mt-8 text-center font-sans text-[0.56rem] tracking-[0.15em] uppercase text-label">
                Akses terbatas &nbsp;·&nbsp; Hanya untuk personel berwenang
            </p>

        </div>
    </div>

</body>
</html>