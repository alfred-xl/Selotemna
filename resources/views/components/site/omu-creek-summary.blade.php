@props(['property', 'contact'])

<section class="section-space bg-ink-50">
    <div class="site-container grid items-start gap-10 lg:grid-cols-[minmax(0,0.9fr)_minmax(22rem,1.1fr)] lg:gap-16">
        <div>
            <span class="eyebrow">Featured land opportunity</span>
            <h2 class="text-[clamp(2.25rem,5vw,3.75rem)] font-semibold leading-[1.08] tracking-[-0.04em]">{{ $property['name'] }}</h2>
            <p class="mt-5 max-w-xl text-lg leading-8 text-ink-500">{{ $property['overview'] }}</p>
            <dl class="mt-8 border-y border-ink-200">
                <div class="summary-row"><dt>Opportunity</dt><dd>{{ $property['type'] }}</dd></div>
                <div class="summary-row"><dt>Title</dt><dd>{{ $property['title'] }}</dd></div>
                <div class="summary-row"><dt>Rate</dt><dd class="font-display text-brand-700">₦{{ number_format($property['price_per_sqm']) }} per sqm</dd></div>
            </dl>
        </div>
        <div>
            <h3 class="text-xl font-semibold">Current outright prices</h3>
            <dl class="mt-4 border-t border-ink-200">
                @foreach (array_reverse($property['options']) as $option)
                    <div class="flex flex-wrap items-baseline justify-between gap-x-5 gap-y-1 border-b border-ink-200 py-4">
                        <dt class="font-semibold text-ink-800 tabular-nums">{{ number_format($option['size_sqm']) }} sqm</dt>
                        <dd class="font-display text-lg font-semibold text-ink-950 tabular-nums">₦{{ number_format($option['price']) }}</dd>
                    </div>
                @endforeach
            </dl>
            <p class="mt-5 text-sm leading-6 text-ink-500">{{ $property['disclaimer'] }}</p>
            <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:flex-wrap">
                <x-site.button href="{{ route('omu-creek') }}">View Omu Creek</x-site.button>
                <x-site.button href="{{ route('inspections.create', ['interest' => $property['name']]) }}" variant="secondary" data-property-interest="{{ $property['name'] }}">Request an Inspection</x-site.button>
                @if ($contact['whatsapp_url'])
                    <a href="{{ $contact['whatsapp_url'] }}" class="text-link" data-event="whatsapp_click">Ask on WhatsApp</a>
                @endif
            </div>
        </div>
    </div>
</section>
