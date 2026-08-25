@props([
    'eyebrow' => null,
    'heading',
    'intro',
    'breadcrumbs' => [],
    'media' => null,
    'variant' => 'default',
    'backgroundImage' => null,
    'imagePosition' => 'center center',
    'showBreadcrumbs' => true,
    'showEyebrow' => true,
    'alignment' => 'start',
    'overlaySize' => 'default',
])

@php
    $isCentered = $alignment === 'center';
    $isCompactOverlay = $overlaySize === 'compact';
@endphp

@if ($variant === 'overlay')
    <section @class([
        'relative isolate overflow-hidden bg-brand-950 text-white',
        'min-h-[22.5rem] md:min-h-[27.5rem] lg:min-h-[29rem]' => $isCompactOverlay,
        'min-h-[26.25rem] md:min-h-[30rem] lg:min-h-[35rem]' => ! $isCompactOverlay,
    ]) data-page-hero data-page-hero-variant="overlay" data-page-hero-alignment="{{ $alignment }}" data-page-hero-size="{{ $overlaySize }}">
        @if ($backgroundImage)
            <img src="{{ $backgroundImage }}" alt="" class="pointer-events-none absolute inset-0 z-0 h-full w-full object-cover" style="object-position: {{ $imagePosition }};" width="1280" height="960" fetchpriority="high" aria-hidden="true" data-page-hero-image data-page-hero-focal-position>
        @else
            <div class="pointer-events-none absolute inset-0 z-0 overflow-hidden bg-brand-950" aria-hidden="true" data-page-hero-fallback>
                <div class="absolute -left-24 top-1/2 size-72 -translate-y-1/2 rounded-full border border-white/10"></div>
                <div class="absolute -right-20 -top-20 size-80 rounded-full border border-white/10"></div>
                <div class="absolute bottom-[18%] right-[10%] h-px w-1/2 bg-white/15"></div>
                <img src="{{ asset('assets/logo.png') }}" alt="" class="absolute bottom-10 right-10 hidden w-28 rounded-xl bg-white/90 p-3 md:block" width="189" height="153">
            </div>
        @endif

        <div class="pointer-events-none absolute inset-0 z-10 bg-ink-950/50" aria-hidden="true" data-page-hero-overlay>
            <div class="absolute inset-0 bg-linear-to-r from-ink-950/80 via-ink-950/55 to-ink-950/10"></div>
        </div>

        <div @class([
            'site-container relative z-20 flex flex-col py-4 md:py-6',
            'min-h-[22.5rem] md:min-h-[27.5rem] lg:min-h-[29rem]' => $isCompactOverlay,
            'min-h-[26.25rem] md:min-h-[30rem] lg:min-h-[35rem]' => ! $isCompactOverlay,
        ])>
            @if ($showBreadcrumbs && $breadcrumbs)
                <nav aria-label="Breadcrumb" data-page-hero-breadcrumb>
                    <ol class="flex flex-wrap items-center gap-2 text-sm font-semibold text-white/70">
                        @foreach ($breadcrumbs as $crumb)
                            <li class="flex items-center gap-2">
                                @if (! $loop->first) <span aria-hidden="true">/</span> @endif
                                @if (! empty($crumb['href']))
                                    <a href="{{ $crumb['href'] }}" class="inline-flex min-h-11 items-center rounded-lg hover:text-white focus-visible:ring-white focus-visible:ring-offset-brand-950">{{ $crumb['label'] }}</a>
                                @else
                                    <span aria-current="page" class="text-white/90">{{ $crumb['label'] }}</span>
                                @endif
                            </li>
                        @endforeach
                    </ol>
                </nav>
            @endif

            <div @class([
                'my-auto w-full py-4 md:py-6 lg:py-4',
                'mx-auto max-w-[43.75rem] text-center' => $isCentered,
                'max-w-[50rem]' => ! $isCentered,
            ]) data-hero-sequence>
                @if ($showEyebrow && $eyebrow)
                    <span class="eyebrow text-brand-100" data-hero-item>{{ $eyebrow }}</span>
                @endif
                <h1 @class([
                    'font-semibold leading-[1.04] tracking-[-0.045em] !text-white',
                    'text-[clamp(2.5rem,12vw,2.75rem)] md:text-[clamp(3.5rem,7vw,4rem)]' => $isCompactOverlay,
                    'text-[clamp(2rem,10vw,2.75rem)] md:text-[clamp(2.75rem,7vw,4.5rem)]' => ! $isCompactOverlay,
                ]) data-hero-item>{{ $heading }}</h1>
                <p @class([
                    'mt-5 text-white/85 md:mt-6',
                    'mx-auto max-w-[43.75rem] text-lg leading-8 md:text-xl md:leading-8' => $isCentered,
                    'max-w-3xl text-base leading-7 md:text-xl md:leading-8' => ! $isCentered,
                ]) data-hero-item>{{ $intro }}</p>
            </div>
        </div>
    </section>
@else
    <section class="relative overflow-hidden border-b border-brand-100 bg-brand-50" data-page-hero data-page-hero-variant="default">
        <div class="site-container-wide py-14 md:py-20 lg:py-24">
            @if ($showBreadcrumbs && $breadcrumbs)
                <nav class="mb-8" aria-label="Breadcrumb">
                    <ol class="flex flex-wrap items-center gap-2 text-sm font-semibold text-ink-500">
                        @foreach ($breadcrumbs as $crumb)
                            <li class="flex items-center gap-2">
                                @if (! $loop->first) <span aria-hidden="true">/</span> @endif
                                @if (! empty($crumb['href']))
                                    <a href="{{ $crumb['href'] }}" class="inline-flex min-h-11 items-center hover:text-brand-700">{{ $crumb['label'] }}</a>
                                @else
                                    <span aria-current="page" class="text-ink-800">{{ $crumb['label'] }}</span>
                                @endif
                            </li>
                        @endforeach
                    </ol>
                </nav>
            @endif
            <div class="grid items-end gap-10 lg:grid-cols-[minmax(0,1fr)_24rem]" data-hero-sequence>
                <div class="min-w-0 max-w-4xl">
                    @if ($showEyebrow && $eyebrow)
                        <span class="eyebrow" data-hero-item>{{ $eyebrow }}</span>
                    @endif
                    <h1 class="text-[clamp(2.35rem,6vw,4.5rem)] font-semibold leading-[1.04] tracking-[-0.045em]" data-hero-item>{{ $heading }}</h1>
                    <p class="mt-6 max-w-3xl text-lg leading-8 text-ink-500 md:text-xl" data-hero-item>{{ $intro }}</p>
                </div>
                @if ($media)
                    <figure class="min-w-0 overflow-hidden rounded-[1.5rem] border border-brand-100 bg-white" data-hero-item data-hero-media>
                        <img src="{{ $media['url'] }}" alt="{{ $media['alt'] }}" class="aspect-[4/3] w-full object-cover" width="960" height="720" fetchpriority="high">
                        <figcaption class="break-words px-4 py-3 text-xs leading-5 text-ink-500">
                            Editorial image. <a href="{{ $media['sourceUrl'] }}" class="font-semibold text-brand-700 hover:text-brand-800" target="_blank" rel="noopener noreferrer">{{ $media['credit'] }}</a>
                        </figcaption>
                    </figure>
                @else
                    <div class="brand-media hidden aspect-[4/3] items-center justify-center lg:flex" aria-hidden="true" data-hero-item data-hero-media>
                        <img src="{{ asset('assets/logo.png') }}" alt="" class="w-24 rounded-xl bg-white p-3" width="189" height="153">
                    </div>
                @endif
            </div>
        </div>
    </section>
@endif
