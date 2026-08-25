@props(['property', 'contact'])

<section class="section-space bg-ink-50" data-home-omu-creek>
    <div class="site-container">
        <div class="grid items-start gap-10 lg:grid-cols-[minmax(0,1.05fr)_minmax(22rem,0.95fr)] lg:gap-16" data-reveal-group>
            <div class="aspect-video min-w-0 overflow-hidden rounded-[1.5rem] border border-brand-100 bg-brand-950" data-reveal="media" data-omu-creek-media>
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
        <div class="mt-10 grid items-start gap-12 border-t border-ink-200 pt-10 lg:grid-cols-[minmax(0,1.05fr)_minmax(22rem,0.95fr)] lg:gap-16" data-reveal-group>
            <div data-reveal data-omu-creek-information>
                <h3 class="text-2xl font-semibold leading-tight">Review the complete project information.</h3>
                <p class="mt-4 max-w-xl text-base leading-7 text-ink-500">Understand the information available before making an enquiry or requesting an inspection.</p>
                <ul class="mt-7 border-y border-ink-200" aria-label="Project information available">
                    @foreach (['Payment terms and applicable charges', 'Documentation and allocation information', 'Infrastructure plans and project policies'] as $information)
                        <li class="flex min-h-14 items-center gap-4 border-b border-ink-200 py-4 text-base font-semibold leading-6 text-ink-800 last:border-b-0">
                            <span class="size-1.5 shrink-0 rounded-full bg-brand-700" aria-hidden="true"></span>
                            <span>{{ $information }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div data-omu-creek-prices>
                <h3 class="text-2xl font-semibold leading-tight">Current outright prices</h3>
                <dl class="mt-5 border-t border-ink-200" data-reveal-group>
                    @foreach (array_reverse($property['options']) as $option)
                        <div class="flex flex-wrap items-baseline justify-between gap-x-5 gap-y-1 border-b border-ink-200 py-4" data-reveal>
                            <dt class="font-semibold text-ink-800 tabular-nums">{{ number_format($option['size_sqm']) }} sqm</dt>
                            <dd class="font-display text-lg font-semibold text-ink-950 tabular-nums">₦{{ number_format($option['price']) }}</dd>
                        </div>
                    @endforeach
                </dl>
                <p class="mt-5 text-[0.9375rem] leading-7 text-ink-500">{{ $property['disclaimer'] }}</p>
                <div class="mt-8" data-omu-creek-actions>
                    <div class="flex flex-col gap-3 sm:flex-row sm:flex-wrap">
                        <x-site.button href="{{ route('inspections.create', ['interest' => $property['name']]) }}" class="w-full sm:w-auto" data-property-interest="{{ $property['name'] }}">Request an Omu Creek Inspection</x-site.button>
                        <x-site.button href="{{ route('omu-creek') }}" variant="secondary" class="w-full sm:w-auto">View Full Project Details</x-site.button>
                    </div>
                    @if ($contact['whatsapp_url'])
                        <a href="{{ $contact['whatsapp_url'] }}" class="text-link mt-4" data-event="whatsapp_click">Ask on WhatsApp</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
