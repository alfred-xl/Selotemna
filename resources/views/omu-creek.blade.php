@extends('layouts.site')

@push('structured-data')
    @php
        $listingSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'RealEstateListing',
            '@id' => route('omu-creek').'#listing',
            'url' => route('omu-creek'),
            'name' => $featuredProperty['name'],
            'description' => $featuredProperty['overview'],
            'publisher' => [
                '@type' => 'Organization',
                'name' => 'Selotemna Limited',
                'url' => route('home'),
            ],
            'spatialCoverage' => collect($featuredProperty['locations'])->map(fn (string $location): array => [
                '@type' => 'Place',
                'name' => $location.', Lagos State, Nigeria',
            ])->values()->all(),
            'offers' => collect($featuredProperty['options'])->map(fn (array $option): array => [
                '@type' => 'Offer',
                'url' => route('omu-creek'),
                'name' => $featuredProperty['name'].' '.$option['label'],
                'price' => $option['price'],
                'priceCurrency' => 'NGN',
                'businessFunction' => 'http://purl.org/goodrelations/v1#Sell',
                'itemOffered' => [
                    '@type' => 'Product',
                    'name' => $featuredProperty['name'].' '.$option['label'],
                    'category' => $featuredProperty['type'],
                    'size' => [
                        '@type' => 'QuantitativeValue',
                        'value' => $option['size_sqm'],
                        'unitCode' => 'MTK',
                        'unitText' => 'square metre',
                    ],
                ],
            ])->values()->all(),
        ];

        if ($heroImage) {
            $listingSchema['primaryImageOfPage'] = ['@type' => 'ImageObject', 'url' => $heroImage];
        }
    @endphp
    <script type="application/ld+json">{!! json_encode($listingSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP) !!}</script>
@endpush

@section('content')
    @if ($isPreview)
        <div class="bg-amber-100 px-5 py-3 text-center text-sm font-semibold text-amber-950" role="status" data-project-preview-banner>
            Administrator preview — this version is private and may not be published.
        </div>
    @endif

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

    <nav class="sticky top-20 z-30 border-b border-ink-200 bg-white/95 backdrop-blur-sm" aria-label="Omu Creek page sections" data-page-section-nav>
        <div class="site-container py-2 md:py-0">
            <details class="page-section-menu group md:hidden">
                <summary class="flex min-h-12 cursor-pointer list-none items-center justify-between gap-4 font-display text-sm font-semibold text-ink-950">
                    <span>On this page: <span class="text-brand-700" data-page-section-current>Overview</span></span>
                    <span class="text-xl font-normal text-brand-700 transition-transform group-open:rotate-45" aria-hidden="true">+</span>
                </summary>
                <ul class="grid border-t border-ink-200 pb-2 pt-2 text-sm font-semibold text-ink-800">
                    <li><a href="#overview" class="page-section-link" data-page-section-link>Overview</a></li>
                    <li><a href="#pricing" class="page-section-link" data-page-section-link>Pricing</a></li>
                    <li><a href="#purchase-guide" class="page-section-link" data-page-section-link>Purchase guide</a></li>
                </ul>
            </details>
            <ul class="hidden grid-cols-3 divide-x divide-ink-200 text-center text-sm font-semibold text-ink-800 md:grid">
                <li><a href="#overview" class="page-section-link justify-center" data-page-section-link>Overview</a></li>
                <li><a href="#pricing" class="page-section-link justify-center" data-page-section-link>Pricing</a></li>
                <li><a href="#purchase-guide" class="page-section-link justify-center" data-page-section-link>Purchase guide</a></li>
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
                    <div class="summary-row">
                        <dt>Title</dt>
                        <dd>
                            <span class="block">{{ $featuredProperty['title'] }}</span>
                            @if ($featuredProperty['title_information'])
                                <span class="mt-1 block text-sm font-normal leading-6 text-ink-500">{{ $featuredProperty['title_information'] }}</span>
                            @endif
                        </dd>
                    </div>
                    @if ($locationCoverage)
                        <div class="summary-row"><dt>Location coverage</dt><dd>{{ $locationCoverage }}</dd></div>
                    @endif
                </dl>

                <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:flex-wrap" data-omu-overview-actions>
                    <x-site.button href="{{ route('project-enquiries.create') }}" class="w-full sm:w-auto" data-property-interest="Omu Creek">Select a Plot Size</x-site.button>
                    @if ($featuredProperty['brochure_url'])
                        <x-site.brochure-button :href="$featuredProperty['brochure_url']" class="w-full sm:w-auto" />
                    @endif
                </div>
            </div>
        </div>
    </section>

    <section id="pricing" class="section-space scroll-mt-40 bg-ink-50 focus-visible:outline-2 focus-visible:outline-brand-700" tabindex="-1" data-omu-pricing>
        <div class="site-container">
            <div class="grid items-start gap-10 lg:grid-cols-[minmax(16rem,0.7fr)_minmax(0,1.3fr)] lg:gap-20">
                <div data-reveal>
                    <span class="eyebrow">Pricing</span>
                    <h2 class="text-[clamp(2rem,5vw,3rem)] font-semibold leading-tight">Review the published plot prices.</h2>
                    @if ($featuredProperty['price_per_sqm'])
                        <p class="mt-6 text-sm font-bold uppercase tracking-[0.12em] text-ink-500">Current rate</p>
                        <p class="mt-2 font-display text-3xl font-semibold text-brand-700 tabular-nums md:text-4xl">₦{{ number_format($featuredProperty['price_per_sqm']) }} per sqm</p>
                    @endif
                </div>

                @if ($featuredProperty['options'])
                    <dl class="border-y border-ink-200" data-reveal-group>
                        @foreach (collect($featuredProperty['options'])->sortBy('size_sqm') as $option)
                            <div class="grid grid-cols-[minmax(0,1fr)_auto] items-center gap-3 border-b border-ink-200 py-5 last:border-b-0 md:gap-6 md:py-6" data-reveal>
                                <dt class="min-w-0">
                                    <span class="block font-semibold text-ink-950">{{ $option['label'] }}</span>
                                    <span class="mt-1 block text-sm text-ink-500 tabular-nums">{{ number_format($option['size_sqm']) }} sqm</span>
                                </dt>
                                <dd class="whitespace-nowrap text-right font-display text-base font-semibold text-brand-700 tabular-nums sm:text-2xl">₦{{ number_format($option['price']) }}</dd>
                            </div>
                        @endforeach
                    </dl>
                @endif
            </div>

            @if ($featuredProperty['disclaimer'])
                <p class="mt-8 max-w-4xl border-l-2 border-brand-700 pl-5 text-sm leading-6 text-ink-500">{{ $featuredProperty['disclaimer'] }}</p>
            @endif

        </div>
    </section>

    <section id="purchase-guide" class="section-space scroll-mt-40 bg-brand-50 focus-visible:outline-2 focus-visible:outline-brand-700" tabindex="-1" data-omu-purchase-guide>
        <div class="site-container">
            <x-site.section-heading eyebrow="Purchase guide" heading="Open the terms you need to review." intro="Payment, documentation, infrastructure, allocation, construction, and administrative policies are grouped here to keep the page concise." />

            @php
                $policyHeadings = ['Instalment-default terms', 'Resale and change of ownership', 'Refund terms'];
                $policyItems = $featuredProperty['policy_items'] ?? collect($featuredProperty['policies'])
                    ->map(fn (string $body, int $index): array => ['heading' => $policyHeadings[$index] ?? 'Project policy', 'body' => $body])
                    ->all();
            @endphp

            <div class="mt-10 grid gap-4 lg:grid-cols-2" data-purchase-guide-disclosures>
                @if ($featuredProperty['payment_plan'])
                    <details class="content-disclosure group" data-financial-disclosures data-reveal>
                        <summary>Payment plan <span aria-hidden="true">+</span></summary>
                        <div class="content-disclosure-body">
                            <dl class="border-y border-ink-200">
                                @if ($featuredProperty['payment_plan']['initial_deposit'])
                                    <div class="summary-row"><dt>Initial deposit</dt><dd class="tabular-nums">₦{{ number_format($featuredProperty['payment_plan']['initial_deposit']) }}</dd></div>
                                @endif
                                @if ($featuredProperty['payment_plan']['balance_period'])
                                    <div class="summary-row"><dt>Balance</dt><dd>Payable within {{ $featuredProperty['payment_plan']['balance_period'] }}</dd></div>
                                @endif
                            </dl>
                            @if ($featuredProperty['payment_plan']['note'])
                                <p class="mt-5 leading-7 text-ink-500">{{ $featuredProperty['payment_plan']['note'] }}</p>
                            @endif
                        </div>
                    </details>
                @endif

                @if ($featuredProperty['charges'])
                    <details class="content-disclosure group" data-reveal>
                        <summary>Additional charges <span aria-hidden="true">+</span></summary>
                        <div class="content-disclosure-body">
                            <dl class="border-y border-ink-200">
                                @foreach ($featuredProperty['charges'] as $charge)
                                    <div class="summary-row"><dt>{{ $charge['label'] }}</dt><dd class="tabular-nums">{{ $charge['value'] }}</dd></div>
                                @endforeach
                            </dl>
                        </div>
                    </details>
                @endif

                @if ($featuredProperty['documents'])
                    <details class="content-disclosure group" data-reveal>
                        <summary>Documents by payment stage <span aria-hidden="true">+</span></summary>
                        <div class="content-disclosure-body">
                            @foreach ($featuredProperty['documents'] as $documentStage)
                                <div class="border-b border-brand-100 py-5 first:pt-0 last:border-b-0 last:pb-0">
                                    <h3 class="text-base font-semibold">{{ $documentStage['stage'] }}</h3>
                                    <ul class="mt-3 space-y-2">
                                        @foreach ($documentStage['items'] as $document)
                                            <li class="feature-line">{{ $document }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endforeach
                        </div>
                    </details>
                @endif

                @if ($featuredProperty['planned_infrastructure'])
                    <details class="content-disclosure group" data-reveal>
                        <summary>Planned infrastructure <span aria-hidden="true">+</span></summary>
                        <div class="content-disclosure-body">
                            <ul class="grid gap-x-8 gap-y-3 sm:grid-cols-2">
                                @foreach ($featuredProperty['planned_infrastructure'] as $item)
                                    <li class="feature-line">{{ $item }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </details>
                @endif

                @if ($featuredProperty['allocation'] || $featuredProperty['construction'])
                    <details class="content-disclosure group" data-reveal>
                        <summary>Allocation and construction <span aria-hidden="true">+</span></summary>
                        <div class="content-disclosure-body space-y-5">
                            @if ($featuredProperty['allocation'])
                                <p class="leading-7 text-ink-500"><strong class="text-ink-950">Physical allocation:</strong> {{ $featuredProperty['allocation'] }}</p>
                            @endif
                            @if ($featuredProperty['construction'])
                                <p class="leading-7 text-ink-500"><strong class="text-ink-950">Construction after allocation:</strong> {{ $featuredProperty['construction'] }}</p>
                            @endif
                        </div>
                    </details>
                @endif

                @foreach ($policyItems as $policy)
                    <details class="content-disclosure group" data-policy-disclosures data-reveal>
                        <summary>{{ $policy['heading'] }} <span aria-hidden="true">+</span></summary>
                        <div class="content-disclosure-body">
                            <p class="leading-7 text-ink-500">{{ $policy['body'] }}</p>
                        </div>
                    </details>
                @endforeach
            </div>
        </div>
    </section>

    <x-site.testimonial-section :items="$testimonials" eyebrow="Omu Creek experiences" heading="Feedback specifically connected to Omu Creek." />

    <section class="relative isolate overflow-hidden bg-brand-950 py-16 text-white md:py-20 lg:py-28" data-omu-final-cta>
        @if ($closingImage)
            <img src="{{ $closingImage }}" alt="" class="pointer-events-none absolute inset-0 -z-20 h-full w-full object-cover" loading="lazy" aria-hidden="true">
        @endif
        <div class="pointer-events-none absolute inset-0 -z-10 bg-ink-950/80" aria-hidden="true"></div>
        <div class="site-container" data-reveal>
            <div class="max-w-3xl">
                <h2 class="text-[clamp(2rem,5vw,3.25rem)] font-semibold leading-tight !text-white">Take the next step with Omu Creek.</h2>
                <p class="mt-5 max-w-2xl text-lg leading-8 text-white/80">Request an inspection to visit Omu Creek, or call the Selotemna team to discuss the project and your next step.</p>
                <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:flex-wrap">
                    <x-site.button href="{{ route('inspections.create', ['interest' => 'Omu Creek']) }}" variant="reversed" class="w-full sm:w-auto">Request an Inspection</x-site.button>
                    <x-site.button href="{{ $contact['phone_url'] }}" variant="outline-reversed" class="w-full sm:w-auto" data-event="call_agent_click">Call an Agent</x-site.button>
                </div>
                <p class="mt-6 text-sm leading-6 text-white/70">Submitting an inspection request does not automatically confirm an appointment.</p>
            </div>
        </div>
    </section>
@endsection
