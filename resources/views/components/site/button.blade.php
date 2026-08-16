@props([
    'href',
    'variant' => 'primary',
])

@php
    $variants = [
        'primary' => 'bg-brand-700 text-white hover:bg-brand-800',
        'secondary' => 'border border-ink-200 bg-white text-ink-950 hover:border-brand-700 hover:bg-brand-50',
        'reversed' => 'bg-white text-brand-950 hover:bg-brand-50',
        'outline-reversed' => 'border border-white/50 text-white hover:border-white hover:bg-white/10',
    ];
@endphp

<a
    href="{{ $href }}"
    {{ $attributes->class([
        'inline-flex min-h-12 items-center justify-center gap-2 rounded-xl px-5 py-3 text-sm font-semibold transition duration-200 active:scale-[0.98]',
        $variants[$variant] ?? $variants['primary'],
    ]) }}
>
    <span>{{ $slot }}</span>
    @if ($attributes->get('data-arrow') !== 'false')
        <svg aria-hidden="true" class="size-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M5 12h14" />
            <path d="m13 6 6 6-6 6" />
        </svg>
    @endif
</a>
