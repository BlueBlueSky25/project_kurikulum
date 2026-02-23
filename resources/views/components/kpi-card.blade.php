@props(['title', 'value', 'icon', 'color' => 'warm'])

@php
    $palette = [
        'warm'   => ['bg' => 'bg-espresso',    'text' => 'text-paper'],
        'cream'  => ['bg' => 'bg-cream',        'text' => 'text-ink'],
        'sand'   => ['bg' => 'bg-sand',         'text' => 'text-ink'],
        'muted'  => ['bg' => 'bg-rule',         'text' => 'text-ink'],
    ];

    // Map warna lama ke palette baru supaya dashboard.blade tidak perlu diubah
    $colorMap = [
        'blue'   => 'warm',
        'green'  => 'cream',
        'yellow' => 'sand',
        'purple' => 'muted',
        'red'    => 'warm',
    ];

    $resolved  = $colorMap[$color] ?? $color;
    $bg        = $palette[$resolved]['bg']   ?? 'bg-espresso';
    $iconText  = $palette[$resolved]['text'] ?? 'text-paper';
@endphp

<div class="bg-paper border border-rule p-6 group hover:border-espresso/30 transition-colors duration-200">
    <div class="flex items-start justify-between gap-4">

        {{-- Value & Title --}}
        <div class="flex-1 min-w-0">
            <p class="font-sans text-[0.58rem] font-semibold tracking-[0.25em] uppercase text-label mb-2">
                {{ $title }}
            </p>
            <p class="font-serif text-[2rem] font-normal leading-none text-ink">
                {{ $value }}
            </p>
        </div>

        {{-- Icon Box --}}
        <div class="w-12 h-12 {{ $bg }} flex items-center justify-center flex-shrink-0">
            <i class="fas {{ $icon }} text-base {{ $iconText }}"></i>
        </div>

    </div>

    {{-- Bottom accent line --}}
    <div class="mt-5 h-px w-0 bg-espresso/20 group-hover:w-full transition-all duration-500"></div>
</div>