@props(['items', 'idPrefix' => 'faq'])

<div class="divide-y divide-ink-200 border-y border-ink-200" data-faq-group data-reveal>
    @foreach ($items as $item)
        @php($faqId = $idPrefix.'-panel-'.$loop->iteration)
        <div>
            <h3>
                <button type="button" class="flex min-h-16 w-full items-center justify-between gap-6 py-4 text-left font-display text-lg font-semibold text-ink-950 md:text-xl" aria-expanded="true" aria-controls="{{ $faqId }}" data-faq-trigger data-motion-button>
                    <span>{{ $item['question'] }}</span>
                    <svg aria-hidden="true" class="size-5 shrink-0 rotate-45 text-brand-700" data-faq-icon viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M12 5v14" /><path d="M5 12h14" /></svg>
                </button>
            </h3>
            <div id="{{ $faqId }}" class="pb-6 pr-8 leading-7 text-ink-500" data-faq-panel>
                <p>{{ $item['answer'] }}</p>
                @if (! empty($item['points']))
                    <ul class="mt-3 list-disc space-y-2 pl-5">
                        @foreach ($item['points'] as $point) <li>{{ $point }}</li> @endforeach
                    </ul>
                @endif
                @if (! empty($item['note'])) <p class="mt-3 text-sm">{{ $item['note'] }}</p> @endif
            </div>
        </div>
    @endforeach
</div>
