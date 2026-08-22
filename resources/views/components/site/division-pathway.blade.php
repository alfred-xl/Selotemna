@props(['title', 'description', 'href', 'linkLabel', 'icon'])

<article
    {{ $attributes->class('border-t border-ink-200 py-8 md:py-10 lg:px-8 lg:first:pl-0 lg:last:pr-0') }}
    data-primary-division
>
    <div class="flex items-start gap-5">
        <span class="flex size-12 shrink-0 items-center justify-center rounded-xl border border-brand-100 bg-brand-50 text-brand-700" aria-hidden="true">
            @if ($icon === 'engineering')
                <svg class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14.7 6.3a4 4 0 0 0-5-5L7.4 3.6l3 3L12.7 4.3a4 4 0 0 0 2 2Z" />
                    <path d="m9 7-6.5 6.5a2.1 2.1 0 0 0 3 3L12 10" />
                    <path d="m14 14 6 6" />
                    <path d="m17 11 3-3" />
                    <path d="m16 10 4 4" />
                </svg>
            @else
                <svg class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 21h18" />
                    <path d="M5 21V8l7-5 7 5v13" />
                    <path d="M9 21v-6h6v6" />
                    <path d="M8 10h.01M16 10h.01" />
                </svg>
            @endif
        </span>

        <div class="min-w-0">
            <h3 class="text-2xl font-semibold leading-tight md:text-3xl">{{ $title }}</h3>
            <p class="mt-4 max-w-xl leading-7 text-ink-500">{{ $description }}</p>
            <a href="{{ $href }}" class="group text-link mt-5" data-division-action>
                {{ $linkLabel }}
                <svg aria-hidden="true" class="size-4 shrink-0 transition-transform group-hover:translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M5 12h14" />
                    <path d="m13 6 6 6-6 6" />
                </svg>
            </a>
        </div>
    </div>
</article>
