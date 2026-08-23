@extends('layouts.site')

@section('content')
    <x-site.page-hero eyebrow="Upcoming Project · Real Estate Development · Land allocation" heading="Omu Creek" intro="A planned residential community for people looking to build homes or invest in land." :breadcrumbs="[['label' => 'Home', 'href' => route('home')], ['label' => 'Real Estate Development', 'href' => route('real-estate-development')], ['label' => 'Omu Creek']]" />

    <section class="section-space bg-white">
        <div class="site-container grid items-start gap-12 lg:grid-cols-[1.05fr_0.95fr] lg:gap-16">
            <div class="aspect-video min-w-0 overflow-hidden rounded-[1.5rem] border border-brand-100 bg-brand-950" data-reveal="media">
                @if ($featuredProperty['video_url'])
                    <video class="h-full w-full bg-ink-950 object-contain" controls playsinline preload="metadata" @if ($featuredProperty['video_poster']) poster="{{ $featuredProperty['video_poster'] }}" @endif data-event="omu_creek_video_play" aria-label="Detailed Omu Creek property video">
                        <source src="{{ $featuredProperty['video_url'] }}" type="video/mp4">
                        Your browser does not support embedded video. Contact Selotemna for Omu Creek property information.
                    </video>
                @else
                    <div class="relative flex h-full items-center justify-center overflow-hidden" aria-hidden="true">
                        <div class="absolute -left-16 top-1/2 size-56 -translate-y-1/2 rounded-full border border-white/15"></div><div class="absolute -right-20 -top-20 size-72 rounded-full border border-white/10"></div><div class="absolute bottom-8 right-8 h-px w-1/2 bg-white/20"></div>
                        <img src="{{ asset('assets/logo.png') }}" alt="" class="relative w-24 rounded-xl bg-white p-3 sm:w-32" width="189" height="153">
                    </div>
                @endif
            </div>
            <div>
                <x-site.section-heading eyebrow="Project and opportunity overview" heading="An upcoming residential land project." :intro="$featuredProperty['overview']" />
                <p class="mt-5 leading-7 text-ink-500">{{ $featuredProperty['marketing'] }}</p>
                <div class="mt-8 grid gap-6 sm:grid-cols-2">
                    <div><h2 class="text-xl font-semibold">Location coverage</h2><ul class="mt-4 space-y-2 text-ink-500">@foreach ($featuredProperty['locations'] as $location)<li class="feature-line">{{ $location }}</li>@endforeach</ul></div>
                    <div><h2 class="text-xl font-semibold">Land title</h2><p class="mt-4 leading-7 text-ink-500">{{ $featuredProperty['title_information'] }}</p></div>
                </div>
            </div>
        </div>
    </section>

    <section class="section-space bg-ink-50">
        <div class="site-container">
            <div class="grid gap-10 lg:grid-cols-[0.8fr_1.2fr] lg:gap-16">
                <x-site.section-heading eyebrow="Plot sizes and pricing" heading="Current outright options." intro="The current rate is ₦50,000 per sqm." />
                <div class="overflow-hidden rounded-[1.25rem] border border-ink-200 bg-white" data-reveal-group>
                    <dl>
                        @foreach ($featuredProperty['options'] as $option)
                            <div class="grid gap-2 border-b border-ink-200 px-6 py-5 last:border-b-0 sm:grid-cols-[1fr_auto] sm:items-center" data-reveal>
                                <dt><span class="block font-semibold text-ink-950">{{ $option['label'] }}</span><span class="mt-1 block text-sm text-ink-500 tabular-nums">{{ number_format($option['size_sqm']) }} sqm</span></dt>
                                <dd class="font-display text-xl font-semibold text-brand-700 tabular-nums">₦{{ number_format($option['price']) }}</dd>
                            </div>
                        @endforeach
                    </dl>
                </div>
            </div>
            <p class="mt-6 text-sm leading-6 text-ink-500">{{ $featuredProperty['disclaimer'] }}</p>
        </div>
    </section>

    <section class="section-space bg-white">
        <div class="site-container grid gap-14 lg:grid-cols-2 lg:gap-20">
            <div>
                <x-site.section-heading eyebrow="Payment information" heading="Approved payment plan." />
                <dl class="mt-7 border-y border-ink-200">
                    <div class="summary-row"><dt>Initial deposit</dt><dd>₦{{ number_format($featuredProperty['payment_plan']['initial_deposit']) }}</dd></div>
                    <div class="summary-row"><dt>Balance period</dt><dd>{{ ucfirst($featuredProperty['payment_plan']['balance_period']) }}</dd></div>
                </dl>
                <p class="mt-5 leading-7 text-ink-500">{{ $featuredProperty['payment_plan']['note'] }}</p>
            </div>
            <div>
                <x-site.section-heading eyebrow="Additional charges" heading="Applicable charges." />
                <dl class="mt-7 border-y border-ink-200">@foreach ($featuredProperty['charges'] as $charge)<div class="summary-row"><dt>{{ $charge['label'] }}</dt><dd>{{ $charge['value'] }}</dd></div>@endforeach</dl>
            </div>
        </div>
    </section>

    <section class="section-space bg-brand-50">
        <div class="site-container grid gap-14 lg:grid-cols-2 lg:gap-20">
            <div>
                <x-site.section-heading eyebrow="Documents" heading="Documents supplied at payment stages." />
                <div class="mt-8 space-y-7">@foreach ($featuredProperty['documents'] as $documentStage)<div><h3 class="text-lg font-semibold">{{ $documentStage['stage'] }}</h3><ul class="mt-3 space-y-2 text-ink-500">@foreach ($documentStage['items'] as $document)<li class="feature-line">{{ $document }}</li>@endforeach</ul></div>@endforeach</div>
            </div>
            <div>
                <x-site.section-heading eyebrow="Planned infrastructure" heading="The estate plan includes." intro="These items are planned infrastructure and amenities." />
                <ul class="mt-8 grid gap-3 sm:grid-cols-2">@foreach ($featuredProperty['planned_infrastructure'] as $item)<li class="feature-line text-ink-500">{{ $item }}</li>@endforeach</ul>
            </div>
        </div>
    </section>

    <section class="section-space bg-white">
        <div class="site-container">
            <x-site.section-heading eyebrow="Allocation and construction" heading="Approved timing and next-step information." />
            <div class="mt-10 grid gap-6 lg:grid-cols-2" data-reveal-group>
                <div class="plain-panel" data-reveal><h3>Physical allocation</h3><p>{{ $featuredProperty['allocation'] }}</p></div>
                <div class="plain-panel" data-reveal><h3>Construction after allocation</h3><p>{{ $featuredProperty['construction'] }}</p></div>
            </div>
            <div class="mt-12 max-w-4xl"><h2 class="text-2xl font-semibold">Legal and administrative policies</h2><ul class="mt-6 space-y-4 leading-7 text-ink-500">@foreach ($featuredProperty['policies'] as $policy)<li class="feature-line">{{ $policy }}</li>@endforeach</ul></div>
        </div>
    </section>

    <section class="section-space bg-ink-50">
        <div class="site-container grid gap-12 lg:grid-cols-[0.7fr_1.3fr] lg:gap-20">
            <div><x-site.section-heading eyebrow="Omu Creek FAQ" heading="Answers for an informed next step." /><a href="{{ route('faq') }}" class="text-link mt-7">View All FAQs</a></div>
            <x-site.faq :items="$faqs" id-prefix="omu-detail-faq" />
        </div>
    </section>

    <x-site.testimonial-section :items="$testimonials" eyebrow="Omu Creek experiences" heading="Feedback specifically connected to Omu Creek." />

    <x-site.conversion-cta heading="Request an Omu Creek inspection." intro="Share your preferred date and contact information. A Selotemna representative will follow up; submitting the request does not confirm an appointment." :primary-href="route('inspections.create', ['interest' => 'Omu Creek'])" secondary-label="Contact Selotemna" :secondary-href="route('contact')">
        <p class="mt-6 text-sm leading-6 text-white/70">{{ $featuredProperty['disclaimer'] }}</p>
    </x-site.conversion-cta>
@endsection
