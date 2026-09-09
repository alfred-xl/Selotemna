@props(['project', 'featured' => false, 'enquiryHref' => null, 'stageNote' => null])

<article @class([
    'overflow-hidden border border-ink-200 bg-white',
    'rounded-[1.25rem]' => ! $featured,
    'lg:grid lg:grid-cols-[minmax(0,1.15fr)_minmax(22rem,0.85fr)]' => $featured,
]) data-project-card data-reveal @if ($featured) data-project-featured @endif>
    <div @class([
        'relative overflow-hidden bg-brand-950',
        'aspect-video border-b border-ink-200' => ! $featured,
        'aspect-video border-b border-ink-200 lg:aspect-auto lg:min-h-full lg:border-b-0 lg:border-r' => $featured,
    ])>
        @if ($project['media_url'])
            <img src="{{ $project['media_url'] }}" alt="{{ $project['media_alt'] ?? $project['name'].' project preview' }}" class="absolute inset-0 h-full w-full object-cover" loading="lazy">
        @else
            <div class="absolute inset-0" aria-hidden="true">
                <div class="absolute -left-10 -top-16 size-44 rounded-full border border-white/15"></div>
                <div class="absolute -bottom-20 right-6 size-52 rounded-full border border-white/10"></div>
                <div class="absolute inset-x-8 bottom-8 h-px bg-white/20"></div>
                <svg class="absolute bottom-8 left-8 size-11 text-brand-100" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.35" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21h18" /><path d="M5 21V8l7-5 7 5v13" /><path d="M9 21v-6h6v6" /></svg>
            </div>
        @endif
    </div>
    <div @class(['p-6 md:p-7' => ! $featured, 'p-7 md:p-10 lg:p-12' => $featured])>
        <p class="text-xs font-bold uppercase tracking-[0.14em] text-brand-700">{{ $project['status'] }}</p>
        <h3 @class(['mt-3 font-semibold leading-snug', 'text-xl md:text-2xl' => ! $featured, 'text-3xl md:text-4xl' => $featured])>{{ $project['name'] }}</h3>
        <p class="mt-3 text-sm font-semibold text-ink-800">{{ $project['division'] }}</p>
        <p @class(['mt-4 leading-7 text-ink-500', 'text-lg md:leading-8' => $featured])>{{ $project['summary'] }}</p>
        @if ($featured)
            <div class="mt-7 flex flex-col gap-3 sm:flex-row sm:flex-wrap">
                @if ($project['href'])
                    <x-site.button :href="$project['href']" class="w-full sm:w-auto">View Full Project Details</x-site.button>
                @endif
                @if ($enquiryHref)
                    <x-site.button :href="$enquiryHref" variant="secondary" class="w-full sm:w-auto">Select a Plot Size</x-site.button>
                @endif
            </div>
            @if ($stageNote)
                <p class="mt-5 text-sm leading-6 text-ink-500">{{ $stageNote }}</p>
            @endif
        @elseif ($project['href'])
            <a href="{{ $project['href'] }}" class="text-link mt-5">View {{ $project['name'] }}</a>
        @endif
    </div>
</article>
