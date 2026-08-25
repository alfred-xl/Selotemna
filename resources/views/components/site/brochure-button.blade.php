@props([
    'href',
    'variant' => 'secondary',
])

@php
    $isExternal = str_starts_with($href, 'https://');
    $variants = [
        'secondary' => 'border border-ink-200 bg-white text-ink-950 hover:border-brand-700 hover:bg-brand-50',
        'reversed' => 'bg-white text-brand-950 hover:bg-brand-50',
    ];
@endphp

<a
    href="{{ $href }}"
    aria-label="Download Omu Creek brochure (PDF)"
    @if ($isExternal) target="_blank" rel="noopener noreferrer" @else download="Omu-Creek-Brochure.pdf" @endif
    data-motion-button
    {{ $attributes->class([
        'inline-flex min-h-12 items-center justify-center gap-2 rounded-xl px-5 py-3 text-sm font-semibold',
        $variants[$variant] ?? $variants['secondary'],
    ]) }}
>
    <svg aria-hidden="true" class="size-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M12 3v12" />
        <path d="m7 10 5 5 5-5" />
        <path d="M5 21h14" />
    </svg>
    <span>Download Omu Creek Brochure</span>
</a>
