@props([
    'items',
    'idPrefix' => 'faq',
    'collapsedByDefault' => false,
    'singleOpen' => true,
])

<div class="divide-y divide-ink-200 border-y border-ink-200" data-faq-group data-faq-collapse-default="{{ $collapsedByDefault ? 'true' : 'false' }}" data-faq-single-open="{{ $singleOpen ? 'true' : 'false' }}" data-reveal>
    @foreach ($items as $item)
        @php
            $itemKey = \Illuminate\Support\Str::slug((string) ($item['id'] ?? $loop->iteration));
            $triggerId = $idPrefix.'-'.$itemKey.'-trigger';
            $faqId = $idPrefix.'-'.$itemKey.'-panel';
            $startsExpanded = ! $collapsedByDefault;
        @endphp
        <div>
            <h3>
                <button id="{{ $triggerId }}" type="button" class="flex min-h-16 w-full items-center justify-between gap-5 py-4 text-left font-display text-lg font-semibold leading-7 text-ink-950 md:gap-6 md:text-xl" aria-expanded="{{ $startsExpanded ? 'true' : 'false' }}" aria-controls="{{ $faqId }}" data-faq-trigger data-motion-button>
                    <span>{{ $item['question'] }}</span>
                    <svg aria-hidden="true" @class(['size-5 shrink-0 text-brand-700', 'rotate-45' => $startsExpanded]) data-faq-icon viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M12 5v14" /><path d="M5 12h14" /></svg>
                </button>
            </h3>
            <div id="{{ $faqId }}" class="pb-6 pr-8 text-base leading-7 text-ink-500 md:pr-12" aria-labelledby="{{ $triggerId }}" data-faq-panel @if ($collapsedByDefault) hidden @endif>
                <p>{{ $item['answer'] }}</p>
                @if (! empty($item['points']))
                    <ul class="mt-4 list-disc space-y-2 pl-5">
                        @foreach ($item['points'] as $point) <li>{{ $point }}</li> @endforeach
                    </ul>
                @endif
                @if (! empty($item['note'])) <p class="mt-4 border-l-2 border-brand-100 pl-4">{{ $item['note'] }}</p> @endif
            </div>
        </div>
    @endforeach
</div>
