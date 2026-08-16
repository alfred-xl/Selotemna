@props([
    'eyebrow',
    'heading',
    'intro' => null,
    'align' => 'left',
    'theme' => 'light',
])

<div {{ $attributes->class(['max-w-3xl', 'mx-auto text-center' => $align === 'center']) }}>
    <span @class(['eyebrow', '!text-brand-100' => $theme === 'dark'])>{{ $eyebrow }}</span>
    <h2 @class([
        'text-[clamp(1.875rem,4vw,2.75rem)] font-semibold leading-[1.14] tracking-[-0.03em]',
        '!text-white' => $theme === 'dark',
    ])>{{ $heading }}</h2>
    @if ($intro)
        <p @class([
            'mt-5 max-w-2xl text-base leading-7 md:text-lg md:leading-8',
            'mx-auto' => $align === 'center',
            'text-white/75' => $theme === 'dark',
            'text-ink-500' => $theme !== 'dark',
        ])>{{ $intro }}</p>
    @endif
</div>
