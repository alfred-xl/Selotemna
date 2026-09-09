@extends('layouts.site')

@push('structured-data')
    @php
        $projectUrl = route('projects.show', ['slug' => $project['slug']]);
        $isRealEstate = $project['division'] === 'Real Estate Development';
        $projectSchema = [
            '@context' => 'https://schema.org',
            '@type' => $isRealEstate ? 'RealEstateListing' : 'WebPage',
            '@id' => $projectUrl.'#project',
            'url' => $projectUrl,
            'name' => $project['name'],
            'description' => $project['overview'],
            'publisher' => [
                '@type' => 'Organization',
                'name' => 'Selotemna Limited',
                'url' => route('home'),
            ],
        ];

        if ($project['locations']) {
            $projectSchema['spatialCoverage'] = collect($project['locations'])->map(fn (string $location): array => [
                '@type' => 'Place',
                'name' => $location,
            ])->values()->all();
        }

        if ($isRealEstate && $project['options']) {
            $projectSchema['offers'] = collect($project['options'])->map(fn (array $option): array => [
                '@type' => 'Offer',
                'url' => $projectUrl,
                'name' => $project['name'].' '.$option['label'],
                'price' => $option['price'],
                'priceCurrency' => $option['currency'] ?? $project['currency'],
                'itemOffered' => [
                    '@type' => 'Product',
                    'name' => $project['name'].' '.$option['label'],
                    'category' => $project['type'],
                    'size' => [
                        '@type' => 'QuantitativeValue',
                        'value' => $option['size_sqm'],
                        'unitCode' => 'MTK',
                        'unitText' => 'square metre',
                    ],
                ],
            ])->values()->all();
        }

        if ($heroImage) {
            $projectSchema['primaryImageOfPage'] = ['@type' => 'ImageObject', 'url' => $heroImage];
        }
    @endphp
    <script type="application/ld+json">{!! json_encode($projectSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP) !!}</script>
@endpush

@section('content')
    @if ($isPreview)
        <div class="bg-amber-100 px-5 py-3 text-center text-sm font-semibold text-amber-950" role="status" data-project-preview-banner>
            Administrator preview — this version is private and may not be published.
        </div>
    @endif

    <x-site.page-hero
        variant="overlay"
        :heading="$project['name']"
        :intro="$project['summary']"
        :background-image="$heroImage"
        image-position="50% 50%"
        :show-breadcrumbs="false"
        :show-eyebrow="true"
        :eyebrow="$project['status']"
        alignment="center"
        overlay-size="compact"
    />

    <div data-project-detail>
        <section class="section-space bg-white" data-project-overview>
            <div class="site-container grid items-start gap-10 lg:grid-cols-[minmax(0,1.1fr)_minmax(20rem,0.9fr)] lg:gap-16">
                <div data-reveal>
                    <span class="eyebrow">Project overview</span>
                    <h2 class="text-[clamp(2rem,5vw,3rem)] font-semibold leading-tight">Published project information.</h2>
                    <p class="mt-5 text-lg leading-8 text-ink-500">{{ $project['overview'] }}</p>
                    @if ($project['marketing'])
                        <p class="mt-4 leading-7 text-ink-500">{{ $project['marketing'] }}</p>
                    @endif
                </div>

                <dl class="border-y border-ink-200" data-project-key-facts>
                    <div class="summary-row"><dt>Status</dt><dd>{{ $project['status'] }}</dd></div>
                    <div class="summary-row"><dt>Division</dt><dd>{{ $project['division'] }}</dd></div>
                    @if ($project['type'])
                        <div class="summary-row"><dt>Project type</dt><dd>{{ $project['type'] }}</dd></div>
                    @endif
                    @if ($project['locations'])
                        <div class="summary-row"><dt>Location</dt><dd>{{ collect($project['locations'])->join(', ', ' and ') }}</dd></div>
                    @endif
                    @if ($project['title'])
                        <div class="summary-row"><dt>Title</dt><dd>{{ $project['title'] }}</dd></div>
                    @endif
                </dl>
            </div>
        </section>

        @if ($project['video_url'])
            <section class="pb-16 md:pb-24" data-project-video>
                <div class="site-container">
                    <video class="aspect-video w-full rounded-[1.5rem] bg-ink-950 object-contain" controls playsinline preload="metadata" @if ($project['video_poster']) poster="{{ $project['video_poster'] }}" @endif aria-label="{{ $project['name'] }} project video">
                        <source src="{{ $project['video_url'] }}" type="video/mp4">
                        Your browser does not support embedded video.
                    </video>
                </div>
            </section>
        @endif

        @if ($project['price_per_sqm'] || $project['options'])
            <section class="section-space bg-ink-50" data-project-pricing>
                <div class="site-container">
                    <x-site.section-heading eyebrow="Pricing" heading="Review the published pricing." intro="Confirm current availability and applicable charges with Selotemna before making a commitment." />
                    @if ($project['price_per_sqm'])
                        <p class="mt-8 font-display text-3xl font-semibold text-brand-700 tabular-nums">{{ $project['currency'] }} {{ number_format($project['price_per_sqm']) }} per sqm</p>
                    @endif
                    @if ($project['options'])
                        <dl class="mt-8 border-y border-ink-200">
                            @foreach (collect($project['options'])->sortBy('size_sqm') as $option)
                                <div class="summary-row">
                                    <dt>{{ $option['label'] }} · {{ number_format($option['size_sqm']) }} sqm</dt>
                                    <dd class="tabular-nums">{{ $option['currency'] ?? $project['currency'] }} {{ number_format($option['price']) }}</dd>
                                </div>
                            @endforeach
                        </dl>
                    @endif
                    @if ($project['disclaimer'])
                        <p class="mt-8 max-w-4xl border-l-2 border-brand-700 pl-5 text-sm leading-6 text-ink-500">{{ $project['disclaimer'] }}</p>
                    @endif
                </div>
            </section>
        @endif

        @php
            $hasGuide = $project['payment_plan'] || $project['charges'] || $project['documents'] || $project['planned_infrastructure'] || $project['allocation'] || $project['construction'] || $project['policy_items'];
        @endphp
        @if ($hasGuide)
            <section class="section-space bg-brand-50" data-project-purchase-guide>
                <div class="site-container">
                    <x-site.section-heading eyebrow="Project guide" heading="Open the information you need." intro="Review the published terms, documents, infrastructure, and policies before contacting the team." />
                    <div class="mt-10 grid gap-4 lg:grid-cols-2">
                        @if ($project['payment_plan'] || $project['charges'])
                            <details class="content-disclosure group">
                                <summary>Payments and charges <span aria-hidden="true">+</span></summary>
                                <div class="content-disclosure-body">
                                    @if ($project['payment_plan'])
                                        <dl class="border-y border-ink-200">
                                            @if ($project['payment_plan']['initial_deposit'])
                                                <div class="summary-row"><dt>Initial deposit</dt><dd>{{ $project['payment_plan']['currency'] }} {{ number_format($project['payment_plan']['initial_deposit']) }}</dd></div>
                                            @endif
                                            @if ($project['payment_plan']['balance_period'])
                                                <div class="summary-row"><dt>Balance</dt><dd>{{ $project['payment_plan']['balance_period'] }}</dd></div>
                                            @endif
                                        </dl>
                                    @endif
                                    @foreach ($project['charges'] as $charge)
                                        <div class="summary-row"><span>{{ $charge['label'] }}</span><span>{{ $charge['value'] }}</span></div>
                                    @endforeach
                                </div>
                            </details>
                        @endif

                        @if ($project['documents'])
                            <details class="content-disclosure group">
                                <summary>Documents <span aria-hidden="true">+</span></summary>
                                <div class="content-disclosure-body">
                                    @foreach ($project['documents'] as $stage)
                                        <h3 class="mt-5 font-semibold first:mt-0">{{ $stage['stage'] }}</h3>
                                        <ul class="mt-3 space-y-2">
                                            @foreach ($stage['items'] as $document)
                                                <li class="feature-line">{{ $document }}</li>
                                            @endforeach
                                        </ul>
                                    @endforeach
                                </div>
                            </details>
                        @endif

                        @if ($project['planned_infrastructure'])
                            <details class="content-disclosure group">
                                <summary>Planned infrastructure <span aria-hidden="true">+</span></summary>
                                <div class="content-disclosure-body">
                                    <ul class="grid gap-3 sm:grid-cols-2">
                                        @foreach ($project['planned_infrastructure'] as $item)
                                            <li class="feature-line">{{ $item }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </details>
                        @endif

                        @if ($project['allocation'] || $project['construction'])
                            <details class="content-disclosure group">
                                <summary>Allocation and construction <span aria-hidden="true">+</span></summary>
                                <div class="content-disclosure-body space-y-5">
                                    @if ($project['allocation']) <p><strong>Allocation:</strong> {{ $project['allocation'] }}</p> @endif
                                    @if ($project['construction']) <p><strong>Construction:</strong> {{ $project['construction'] }}</p> @endif
                                </div>
                            </details>
                        @endif

                        @foreach ($project['policy_items'] as $policy)
                            <details class="content-disclosure group">
                                <summary>{{ $policy['heading'] }} <span aria-hidden="true">+</span></summary>
                                <div class="content-disclosure-body"><p class="leading-7 text-ink-500">{{ $policy['body'] }}</p></div>
                            </details>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif

        <x-site.testimonial-section :items="$testimonials" :eyebrow="$project['name'].' experiences'" :heading="'Feedback connected to '.$project['name'].'.'" />

        <x-site.conversion-cta
            :heading="'Discuss '.$project['name'].' with Selotemna.'"
            intro="Ask a question about the published information and the team will explain the appropriate next step."
            primary-label="Make an Enquiry"
            :primary-href="route('contact', ['project' => $project['name']])"
            secondary-label="View All Projects"
            :secondary-href="route('projects.index')"
        />
    </div>
@endsection
