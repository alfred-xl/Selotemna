@extends('layouts.site')

@section('content')
    <x-site.page-hero
        variant="overlay"
        heading="About"
        intro="Real estate development, engineering and construction under one company."
        :background-image="$heroImage"
        image-position="62% 52%"
        :show-breadcrumbs="false"
        :show-eyebrow="false"
        alignment="center"
        overlay-size="compact"
    />

    <section class="section-space bg-white" data-about-company>
        <div class="site-container grid items-start gap-10 lg:grid-cols-[minmax(0,0.78fr)_minmax(0,1.22fr)] lg:gap-20" data-reveal-group>
            <x-site.section-heading eyebrow="Who we are" heading="A company working across property development, engineering and construction." />
            <div class="border-t border-ink-200 pt-8 lg:border-l lg:border-t-0 lg:pl-12 lg:pt-0" data-reveal>
                <div class="space-y-5 text-lg leading-8 text-ink-500">
                    <p>Selotemna Limited is a Nigerian company working across real estate development, engineering and construction. Through its development work, the company presents land and property opportunities, including Omu Creek, its latest project. Through engineering and construction, Selotemna provides a direct starting point for people and organisations preparing project requirements.</p>
                    <p>The available information is presented clearly so visitors can understand the opportunity or requirement and continue towards an appropriate inspection or project conversation.</p>
                </div>
                <div class="mt-8 border-t border-ink-200 pt-5">
                    <span class="text-xs font-bold uppercase tracking-[0.14em] text-ink-500">Company registration</span>
                    <p class="mt-2 font-display text-lg font-semibold text-ink-950">RC 7361086</p>
                </div>
            </div>
        </div>
    </section>

    <section class="section-space bg-ink-50" data-about-divisions>
        <div class="site-container">
            <x-site.section-heading eyebrow="What we do" heading="From property development opportunities to engineering and construction requirements." />
            <div class="mt-12 grid border-b border-ink-200 lg:grid-cols-2 lg:divide-x lg:divide-ink-200" data-reveal-group>
                <x-site.division-pathway title="Real Estate Development" description="Explore Selotemna’s development work and published property opportunities. Omu Creek is the latest project and the current opportunity with detailed public information available." href="{{ route('real-estate-development') }}" link-label="Explore Real Estate Development" icon="development" />
                <x-site.division-pathway title="Engineering & Construction" description="Share the proposed site, scope and current stage of an engineering or construction requirement so Selotemna can understand the project and identify the appropriate next conversation." href="{{ route('engineering-construction') }}" link-label="Explore Engineering & Construction" icon="engineering" />
            </div>
        </div>
    </section>

    <section class="bg-white py-16 md:py-20" data-about-audiences>
        <div class="site-container grid items-start gap-10 lg:grid-cols-[minmax(0,0.82fr)_minmax(0,1.18fr)] lg:gap-20">
            <x-site.section-heading eyebrow="Who we support" heading="People and organisations making property or project decisions." intro="Selotemna supports different property interests and project requirements through clear, relevant starting points." />
            <ul class="border-y border-ink-200" data-reveal-group>
                @foreach (['Property and land buyers', 'Families and investors', 'Nigerians in the diaspora', 'Businesses and organisations', 'Engineering and construction clients'] as $audience)
                    <li class="border-b border-ink-200 py-4 text-lg font-semibold leading-7 text-ink-800 last:border-b-0 md:py-5" data-reveal>{{ $audience }}</li>
                @endforeach
            </ul>
        </div>
    </section>

    <x-site.conversion-cta
        heading="What would you like to discuss?"
        intro="Explore Selotemna’s current development opportunities or continue with an engineering or construction requirement."
        primary-label="Explore Real Estate Development"
        :primary-href="route('real-estate-development')"
        secondary-label="Discuss an Engineering or Construction Project"
        :secondary-href="route('engineering-construction')"
    />
@endsection
