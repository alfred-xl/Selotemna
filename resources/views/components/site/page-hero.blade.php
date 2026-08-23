@props(['eyebrow', 'heading', 'intro', 'breadcrumbs' => [], 'media' => null])

<section class="relative overflow-hidden border-b border-brand-100 bg-brand-50">
    <div class="site-container-wide py-14 md:py-20 lg:py-24">
        @if ($breadcrumbs)
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
                <span class="eyebrow" data-hero-item>{{ $eyebrow }}</span>
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
