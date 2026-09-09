@props(['property', 'contact', 'compact' => false])

<section class="section-space bg-ink-50" data-home-omu-creek>
    <div class="site-container">
        <div class="grid items-start gap-10 lg:grid-cols-[minmax(0,1.05fr)_minmax(22rem,0.95fr)] lg:gap-16" data-reveal-group>
            <div class="aspect-video min-w-0 overflow-hidden rounded-[1.5rem] border border-brand-100 bg-brand-950" data-reveal="media" data-omu-creek-media>
                @if ($property['short_video_url'])
                    <video class="h-full w-full bg-ink-950 object-contain" controls autoplay muted playsinline preload="metadata" @if ($property['short_video_poster']) poster="{{ $property['short_video_poster'] }}" @endif data-autoplay-preview data-event="omu_creek_short_video_play" aria-label="Omu Creek property video preview">
                        <source src="{{ $property['short_video_url'] }}" type="video/mp4">
                        Your browser does not support embedded video. Visit the Omu Creek page for verified property information.
                    </video>
                @else
                    <div class="relative flex h-full items-center justify-center overflow-hidden" aria-hidden="true">
                        <div class="absolute -left-16 top-1/2 size-56 -translate-y-1/2 rounded-full border border-white/15"></div>
                        <div class="absolute -right-20 -top-20 size-72 rounded-full border border-white/10"></div>
                        <div class="absolute bottom-8 right-8 h-px w-1/2 bg-white/20"></div>
                        <img src="{{ asset('assets/logo.png') }}" alt="" class="relative w-24 rounded-xl bg-white p-3 sm:w-32" width="189" height="153">
                    </div>
                @endif
            </div>
            <div data-reveal data-omu-creek-summary>
                <span class="eyebrow">LATEST PROJECT <span aria-hidden="true">·</span> REAL ESTATE DEVELOPMENT</span>
                <h2 class="text-[clamp(2.25rem,5vw,3.75rem)] font-semibold leading-[1.08] tracking-[-0.04em]">{{ $property['name'] }}</h2>
                <p class="mt-5 max-w-xl text-lg leading-8 text-ink-500">{{ $property['overview'] }}</p>
                <dl class="mt-8 border-y border-ink-200" data-omu-creek-facts>
                    <div class="summary-row"><dt>Status</dt><dd>{{ $property['status'] }}</dd></div>
                    <div class="summary-row"><dt>Opportunity</dt><dd>{{ $property['type'] }}</dd></div>
                    <div class="summary-row"><dt>Title</dt><dd>{{ $property['title'] }}</dd></div>
                    <div class="summary-row"><dt>Rate</dt><dd class="font-display text-brand-700">₦{{ number_format($property['price_per_sqm']) }} per sqm</dd></div>
                </dl>
            </div>
        </div>
        <div class="mt-10 border-t border-ink-200 pt-8" data-reveal data-omu-creek-actions>
            @unless ($compact)
                <details class="group max-w-3xl border-y border-ink-200" data-price-disclosure>
                    <summary class="flex min-h-14 cursor-pointer list-none items-center justify-between gap-5 py-4 font-display text-lg font-semibold text-ink-950">
                        View plot sizes and current outright prices
                        <span class="text-2xl font-normal text-brand-700 transition-transform group-open:rotate-45" aria-hidden="true">+</span>
                    </summary>
                    <dl class="border-t border-ink-200 pb-2">
                        @foreach (array_reverse($property['options']) as $option)
                            <div class="flex flex-wrap items-baseline justify-between gap-x-5 gap-y-1 border-b border-ink-200 py-4 last:border-b-0">
                                <dt class="font-semibold text-ink-800 tabular-nums">{{ number_format($option['size_sqm']) }} sqm</dt>
                                <dd class="font-display text-lg font-semibold text-ink-950 tabular-nums">₦{{ number_format($option['price']) }}</dd>
                            </div>
                        @endforeach
                    </dl>
                    <p class="pb-5 text-[0.9375rem] leading-7 text-ink-500">{{ $property['disclaimer'] }}</p>
                </details>
            @endunless

            <div class="flex flex-col gap-3 sm:flex-row sm:flex-wrap @unless($compact) mt-8 @endunless">
                <x-site.button href="{{ route('inspections.create', ['interest' => $property['name']]) }}" class="w-full sm:w-auto" data-property-interest="{{ $property['name'] }}">Request an Omu Creek Inspection</x-site.button>
                <x-site.button href="{{ route('omu-creek') }}" variant="secondary" class="w-full sm:w-auto">View Full Project Details</x-site.button>
            </div>
            @if ($contact['whatsapp_url'])
                <a href="{{ $contact['whatsapp_url'] }}" class="text-link mt-4" data-event="whatsapp_click">Ask on WhatsApp</a>
            @endif
        </div>
    </div>
</section>
