@extends('layouts.site')

@section('content')
    <x-site.page-hero
        variant="overlay"
        heading="Omu Creek"
        intro="A planned residential community for people looking to build homes or invest in land."
        :background-image="$heroImage"
        image-position="50% 50%"
        :show-breadcrumbs="false"
        :show-eyebrow="false"
        alignment="center"
        overlay-size="compact"
    />

    <nav class="border-b border-ink-200 bg-white" aria-label="Omu Creek page sections" data-page-section-nav>
        <div class="site-container overflow-x-auto overscroll-x-contain">
            <ul class="flex min-w-max divide-x divide-ink-200 py-1 text-sm font-semibold text-ink-800">
                <li><a href="#overview" class="inline-flex min-h-11 items-center px-4 pl-0 hover:text-brand-700">Overview</a></li>
                <li><a href="#pricing" class="inline-flex min-h-11 items-center px-4 hover:text-brand-700">Pricing</a></li>
                <li><a href="#payments-charges" class="inline-flex min-h-11 items-center px-4 hover:text-brand-700">Payments &amp; charges</a></li>
                <li><a href="#documents-infrastructure" class="inline-flex min-h-11 items-center px-4 hover:text-brand-700">Documents &amp; infrastructure</a></li>
                <li><a href="#allocation-policies" class="inline-flex min-h-11 items-center px-4 hover:text-brand-700">Allocation &amp; policies</a></li>
                <li><a href="#faqs" class="inline-flex min-h-11 items-center px-4 pr-0 hover:text-brand-700">FAQs</a></li>
            </ul>
        </div>
    </nav>

    <section id="overview" class="section-space scroll-mt-24 bg-white focus-visible:outline-2 focus-visible:outline-brand-700" tabindex="-1" data-omu-overview>
        <div class="site-container grid items-start gap-10 lg:grid-cols-[minmax(0,1.08fr)_minmax(23rem,0.92fr)] lg:gap-16">
            <div class="aspect-video min-w-0 overflow-hidden rounded-[1.5rem] border border-brand-100 bg-brand-950" data-reveal="media" data-omu-video>
                @if ($featuredProperty['video_url'])
                    <video class="h-full w-full bg-ink-950 object-contain" controls playsinline preload="metadata" @if ($videoPoster) poster="{{ $videoPoster }}" @endif data-event="omu_creek_video_play" aria-label="Detailed Omu Creek project video">
                        <source src="{{ $featuredProperty['video_url'] }}" type="video/mp4">
                        Your browser does not support embedded video. Contact Selotemna for Omu Creek project information.
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
                <span class="eyebrow">Omu Creek</span>
                <h2 class="text-[clamp(2rem,5vw,3rem)] font-semibold leading-tight">Project overview</h2>
                <p class="mt-5 text-lg leading-8 text-ink-500">{{ $featuredProperty['overview'] }}</p>
                <p class="mt-4 leading-7 text-ink-500">{{ $featuredProperty['marketing'] }}</p>

                <dl class="mt-8 border-y border-ink-200" data-omu-key-facts>
                    <div class="summary-row"><dt>Status</dt><dd>{{ $featuredProperty['status'] }}</dd></div>
                    <div class="summary-row"><dt>Opportunity</dt><dd>{{ $featuredProperty['type'] }}</dd></div>
                    <div class="summary-row"><dt>Current rate</dt><dd class="tabular-nums">₦{{ number_format($featuredProperty['price_per_sqm']) }} per sqm</dd></div>
                    <div class="summary-row"><dt>Title</dt><dd>{{ $featuredProperty['title'] }}</dd></div>
                    @if ($locationCoverage)
                        <div class="summary-row"><dt>Location coverage</dt><dd>{{ $locationCoverage }}</dd></div>
                    @endif
                </dl>

                <div class="mt-7 border-l-2 border-brand-700 pl-5">
                    <h3 class="text-lg font-semibold">Published title information</h3>
                    <p class="mt-3 leading-7 text-ink-500">{{ $featuredProperty['title_information'] }}</p>
                </div>

                <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:flex-wrap" data-omu-overview-actions>
                    <x-site.button href="{{ route('inspections.create', ['interest' => 'Omu Creek']) }}" class="w-full sm:w-auto" data-property-interest="Omu Creek">Request an Inspection</x-site.button>
                    @if ($featuredProperty['brochure_url'])
                        <x-site.brochure-button :href="$featuredProperty['brochure_url']" class="w-full sm:w-auto" />
                    @endif
                </div>
            </div>
        </div>
    </section>

    <section id="pricing" class="section-space scroll-mt-24 bg-ink-50 focus-visible:outline-2 focus-visible:outline-brand-700" tabindex="-1" data-omu-pricing>
        <div class="site-container">
            <div class="grid gap-10 lg:grid-cols-[minmax(16rem,0.72fr)_minmax(0,1.28fr)] lg:gap-20">
                <div data-reveal>
                    <span class="eyebrow">Pricing</span>
                    <h2 class="text-[clamp(2rem,5vw,3rem)] font-semibold leading-tight">Current outright prices</h2>
                    <p class="mt-6 text-sm font-bold uppercase tracking-[0.12em] text-ink-500">Current rate</p>
                    <p class="mt-2 font-display text-3xl font-semibold text-brand-700 tabular-nums md:text-4xl">₦{{ number_format($featuredProperty['price_per_sqm']) }} <span class="text-base text-ink-500">per sqm</span></p>
                </div>

                <dl class="border-y border-ink-200" data-reveal-group>
                    @foreach (collect($featuredProperty['options'])->sortBy('size_sqm') as $option)
                        <div class="grid grid-cols-[minmax(0,1fr)_auto] items-center gap-4 border-b border-ink-200 py-5 last:border-b-0 md:py-6" data-reveal>
                            <dt class="min-w-0">
                                <span class="block font-semibold text-ink-950">{{ $option['label'] }}</span>
                                <span class="mt-1 block text-sm text-ink-500 tabular-nums">{{ number_format($option['size_sqm']) }} sqm</span>
                            </dt>
                            <dd class="whitespace-nowrap text-right font-display text-lg font-semibold text-brand-700 tabular-nums sm:text-2xl">₦{{ number_format($option['price']) }}</dd>
                        </div>
                    @endforeach
                </dl>
            </div>
            <p class="mt-8 max-w-4xl border-l-2 border-brand-700 pl-5 text-sm leading-6 text-ink-500">{{ $featuredProperty['disclaimer'] }}</p>
        </div>
    </section>

    <section id="payments-charges" class="section-space scroll-mt-24 bg-white focus-visible:outline-2 focus-visible:outline-brand-700" tabindex="-1" data-omu-payments>
        <div class="site-container">
            <x-site.section-heading eyebrow="Financial information" heading="Payments and additional charges." intro="Review the published payment terms and applicable charges together before making an enquiry." />
            <div class="mt-12 grid gap-12 lg:grid-cols-2 lg:gap-0">
                <div class="lg:pr-14" data-reveal>
                    <h3 class="text-2xl font-semibold">Payment terms</h3>
                    <dl class="mt-6 border-y border-ink-200">
                        <div class="summary-row"><dt>Initial deposit</dt><dd class="tabular-nums">₦{{ number_format($featuredProperty['payment_plan']['initial_deposit']) }}</dd></div>
                        <div class="summary-row"><dt>Balance</dt><dd>Payable within {{ $featuredProperty['payment_plan']['balance_period'] }}</dd></div>
                    </dl>
                    <p class="mt-5 leading-7 text-ink-500">{{ $featuredProperty['payment_plan']['note'] }}</p>
                </div>

                <div class="border-t border-ink-200 pt-10 lg:border-l lg:border-t-0 lg:pl-14 lg:pt-0" data-reveal>
                    <h3 class="text-2xl font-semibold">Additional charges</h3>
                    <dl class="mt-6 border-y border-ink-200">
                        @foreach ($featuredProperty['charges'] as $charge)
                            <div class="summary-row"><dt>{{ $charge['label'] }}</dt><dd class="tabular-nums">{{ $charge['value'] }}</dd></div>
                        @endforeach
                    </dl>
                </div>
            </div>
        </div>
    </section>

    <section id="documents-infrastructure" class="section-space scroll-mt-24 bg-brand-50 focus-visible:outline-2 focus-visible:outline-brand-700" tabindex="-1" data-omu-documents>
        <div class="site-container">
            <x-site.section-heading eyebrow="Project information" heading="Documents and planned infrastructure." intro="Documents are organised by the verified payment stages. Infrastructure remains described as planned." />
            <div class="mt-12 grid gap-14 lg:grid-cols-2 lg:gap-20">
                <div data-reveal>
                    <h3 class="text-2xl font-semibold">Documents provided</h3>
                    <div class="mt-6 border-y border-brand-100">
                        @foreach ($featuredProperty['documents'] as $documentStage)
                            <div class="border-b border-brand-100 py-6 last:border-b-0">
                                <h4 class="text-base font-semibold">{{ $documentStage['stage'] }}</h4>
                                <ul class="mt-3 space-y-2">
                                    @foreach ($documentStage['items'] as $document)
                                        <li class="feature-line">{{ $document }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div data-reveal>
                    <h3 class="text-2xl font-semibold">Planned infrastructure</h3>
                    <p class="mt-4 leading-7 text-ink-500">The published estate plan includes the following planned infrastructure and amenities.</p>
                    <ul class="mt-6 grid gap-x-8 gap-y-3 sm:grid-cols-2">
                        @foreach ($featuredProperty['planned_infrastructure'] as $item)
                            <li class="feature-line">{{ $item }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <section id="allocation-policies" class="section-space scroll-mt-24 bg-white focus-visible:outline-2 focus-visible:outline-brand-700" tabindex="-1" data-omu-allocation>
        <div class="site-container">
            <x-site.section-heading eyebrow="Allocation and policies" heading="Conditions, timing and project policies." intro="Review the published allocation, construction and administrative terms before choosing a next step." />

            <div class="mt-12 border-y border-ink-200" data-reveal-group>
                <div class="grid gap-3 border-b border-ink-200 py-7 md:grid-cols-[15rem_minmax(0,1fr)] md:gap-10" data-reveal>
                    <h3 class="text-lg font-semibold">Physical allocation</h3>
                    <p class="leading-7 text-ink-500">{{ $featuredProperty['allocation'] }}</p>
                </div>
                <div class="grid gap-3 border-b border-ink-200 py-7 md:grid-cols-[15rem_minmax(0,1fr)] md:gap-10" data-reveal>
                    <h3 class="text-lg font-semibold">Construction after allocation</h3>
                    <p class="leading-7 text-ink-500">{{ $featuredProperty['construction'] }}</p>
                </div>
                <div class="grid gap-3 border-b border-ink-200 py-7 md:grid-cols-[15rem_minmax(0,1fr)] md:gap-10" data-reveal>
                    <h3 class="text-lg font-semibold">Building and layout requirements</h3>
                    <p class="leading-7 text-ink-500">Building-layout guidelines are collected from the estate management before buyers commence fencing and construction.</p>
                </div>

                @php
                    $policyHeadings = ['Instalment-default terms', 'Resale and change of ownership', 'Refund terms'];
                @endphp
                @foreach ($featuredProperty['policies'] as $policy)
                    <div class="grid gap-3 border-b border-ink-200 py-7 last:border-b-0 md:grid-cols-[15rem_minmax(0,1fr)] md:gap-10" data-reveal>
                        <h3 class="text-lg font-semibold">{{ $policyHeadings[$loop->index] }}</h3>
                        <p class="leading-7 text-ink-500">{{ $policy }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section id="faqs" class="section-space scroll-mt-24 bg-ink-50 focus-visible:outline-2 focus-visible:outline-brand-700" tabindex="-1" data-omu-faqs>
        <div class="site-container grid gap-12 lg:grid-cols-[minmax(16rem,0.7fr)_minmax(0,1.3fr)] lg:gap-20">
            <div>
                <x-site.section-heading eyebrow="Omu Creek FAQ" heading="Answers for an informed next step." intro="Review selected questions about the opportunity, pricing, payments, allocation and construction." />
                <a href="{{ route('faq') }}" class="text-link mt-7">View All FAQs</a>
            </div>
            <x-site.faq :items="$faqs" id-prefix="omu-detail-faq" />
        </div>
    </section>

    <x-site.testimonial-section :items="$testimonials" eyebrow="Omu Creek experiences" heading="Feedback specifically connected to Omu Creek." />

    <section class="relative isolate overflow-hidden bg-brand-950 py-16 text-white md:py-20 lg:py-28" data-omu-final-cta>
        @if ($heroImage)
            <img src="{{ $heroImage }}" alt="" class="pointer-events-none absolute inset-0 -z-20 h-full w-full object-cover" loading="lazy" aria-hidden="true">
        @endif
        <div class="pointer-events-none absolute inset-0 -z-10 bg-ink-950/80" aria-hidden="true"></div>
        <div class="site-container" data-reveal>
            <div class="max-w-3xl">
                <h2 class="text-[clamp(2rem,5vw,3.25rem)] font-semibold leading-tight !text-white">Take the next step with Omu Creek.</h2>
                <p class="mt-5 max-w-2xl text-lg leading-8 text-white/80">Review the available information, then request an inspection to discuss the opportunity with the Selotemna team.</p>
                <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:flex-wrap">
                    <x-site.button href="{{ route('inspections.create', ['interest' => 'Omu Creek']) }}" variant="reversed" class="w-full sm:w-auto" data-property-interest="Omu Creek">Request an Inspection</x-site.button>
                    @if ($featuredProperty['brochure_url'])
                        <x-site.brochure-button :href="$featuredProperty['brochure_url']" variant="reversed" class="w-full sm:w-auto" />
                    @endif
                </div>
                <div class="mt-6 space-y-2 text-sm leading-6 text-white/70">
                    <p>{{ $featuredProperty['disclaimer'] }}</p>
                    <p>Submitting an inspection request does not automatically confirm an appointment.</p>
                </div>
            </div>
        </div>
    </section>
@endsection
