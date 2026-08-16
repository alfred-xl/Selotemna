@props(['items'])

<div class="divide-y divide-ink-200 border-y border-ink-200" data-faq-group>
    @foreach ($items as $item)
        @php($faqId = 'faq-panel-'.$loop->iteration)
        <div>
            <h3>
                <button
                    type="button"
                    class="flex min-h-16 w-full items-center justify-between gap-6 py-4 text-left font-display text-lg font-semibold text-ink-950 md:text-xl"
                    aria-expanded="false"
                    aria-controls="{{ $faqId }}"
                    data-faq-trigger
                >
                    <span>{{ $item['question'] }}</span>
                    <svg aria-hidden="true" class="size-5 shrink-0 text-brand-700 transition-transform" data-faq-icon viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                        <path d="M12 5v14" />
                        <path d="M5 12h14" />
                    </svg>
                </button>
            </h3>
            <div id="{{ $faqId }}" class="pb-6 pr-10 leading-7 text-ink-500" hidden data-faq-panel>
                <p>{{ $item['answer'] }}</p>
            </div>
        </div>
    @endforeach
</div>
