@props(['property', 'contact'])

<section class="section-space bg-ink-50">
    <div class="site-container">
        <div class="grid items-start gap-10 lg:grid-cols-[minmax(0,1.05fr)_minmax(22rem,0.95fr)] lg:gap-16" data-reveal-group>
            <div class="aspect-video min-w-0 overflow-hidden rounded-[1.5rem] border border-brand-100 bg-brand-950" data-reveal="media">
                @if ($property['short_video_url'])
                    <video class="h-full w-full bg-ink-950 object-contain" controls playsinline preload="metadata" @if ($property['short_video_poster']) poster="{{ $property['short_video_poster'] }}" @endif data-event="omu_creek_short_video_play" aria-label="Omu Creek property video preview">
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
            <div data-reveal>
                <span class="eyebrow">{{ $property['status'] }} <span aria-hidden="true">·</span> Featured land opportunity</span>
                <h2 class="text-[clamp(2.25rem,5vw,3.75rem)] font-semibold leading-[1.08] tracking-[-0.04em]">{{ $property['name'] }}</h2>
                <p class="mt-5 max-w-xl text-lg leading-8 text-ink-500">{{ $property['overview'] }}</p>
                <dl class="mt-8 border-y border-ink-200">
                    <div class="summary-row"><dt>Opportunity</dt><dd>{{ $property['type'] }}</dd></div>
                    <div class="summary-row"><dt>Title</dt><dd>{{ $property['title'] }}</dd></div>
                    <div class="summary-row"><dt>Rate</dt><dd class="font-display text-brand-700">₦{{ number_format($property['price_per_sqm']) }} per sqm</dd></div>
                </dl>
            </div>
        </div>
        <div class="mt-10 grid gap-10 border-t border-ink-200 pt-10 lg:grid-cols-[minmax(0,1fr)_minmax(22rem,0.95fr)] lg:gap-16" data-reveal-group>
            <div data-reveal>
                <h3 class="text-2xl font-semibold">A clear route to the full opportunity.</h3>
                <p class="mt-4 max-w-2xl leading-7 text-ink-500">Review the complete title, payment, documentation, allocation and policy information before requesting an inspection.</p>
            </div>
            <div>
            <h3 class="text-xl font-semibold">Current outright prices</h3>
            <dl class="mt-4 border-t border-ink-200" data-reveal-group>
                @foreach (array_reverse($property['options']) as $option)
                    <div class="flex flex-wrap items-baseline justify-between gap-x-5 gap-y-1 border-b border-ink-200 py-4" data-reveal>
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
    </div>
</section>
