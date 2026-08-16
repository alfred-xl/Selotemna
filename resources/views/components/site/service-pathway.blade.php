@props(['title', 'description', 'href', 'linkLabel'])

<article class="group border-t border-ink-200 py-7 last:border-b md:px-4 md:hover:bg-brand-50" data-service-path>
    <div class="flex items-start gap-4">
        <span class="mt-1 flex size-10 shrink-0 items-center justify-center rounded-lg bg-brand-100 text-brand-700" aria-hidden="true">
            <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <path d="M3 21h18" />
                <path d="M6 21V7l6-4 6 4v14" />
                <path d="M9 21v-6h6v6" />
            </svg>
        </span>
        <div>
            <h3 class="text-xl font-semibold">{{ $title }}</h3>
            <p class="mt-2 leading-7 text-ink-500">{{ $description }}</p>
            <a href="{{ $href }}" class="text-link mt-3" data-event="service_path_click">
                {{ $linkLabel }}
                <svg aria-hidden="true" class="size-4 transition-transform group-hover:translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M5 12h14" />
                    <path d="m13 6 6 6-6 6" />
                </svg>
            </a>
        </div>
    </div>
</article>
