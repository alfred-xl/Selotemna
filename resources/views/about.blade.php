@extends('layouts.site')

@section('content')
    <x-site.page-hero eyebrow="About Selotemna" heading="A focused company for property opportunities and project requirements." intro="Selotemna operates through Real Estate Development and Engineering & Construction, with clear pathways for buyers, investors and project clients." :breadcrumbs="[['label' => 'Home', 'href' => route('home')], ['label' => 'About']]" />

    <section class="section-space bg-white">
        <div class="site-container grid gap-12 lg:grid-cols-12 lg:gap-20">
            <div class="lg:col-span-5"><x-site.section-heading eyebrow="The company" heading="Two divisions with distinct roles." /></div>
            <div class="space-y-5 text-lg leading-8 text-ink-500 lg:col-span-7">
                <p>Real Estate Development is the pathway for Selotemna’s published land, property and development opportunities.</p>
                <p>Engineering &amp; Construction is the pathway for conversations about engineering, building and construction requirements.</p>
                <p>Across both divisions, the aim is to make the available information clear and connect each enquiry with an appropriate next step.</p>
                <p class="font-semibold text-ink-800">RC 7361086</p>
            </div>
        </div>
    </section>

    <section class="section-space bg-ink-50">
        <div class="site-container">
            <x-site.section-heading eyebrow="Our divisions" heading="Start with the pathway that fits your requirement." />
            <div class="mt-12 grid border-b border-ink-200 lg:grid-cols-2 lg:divide-x lg:divide-ink-200">
                <x-site.division-pathway title="Real Estate Development" description="Explore published land and development opportunities, including Omu Creek." href="{{ route('real-estate-development') }}" link-label="Explore Real Estate Development" icon="development" />
                <x-site.division-pathway title="Engineering & Construction" description="Share the site, scope and stage of an engineering or construction requirement." href="{{ route('engineering-construction') }}" link-label="Explore Engineering & Construction" icon="engineering" />
            </div>
        </div>
    </section>

    <section class="section-space bg-white">
        <div class="site-container grid gap-12 lg:grid-cols-2 lg:gap-20">
            <div><x-site.section-heading eyebrow="Who we serve" heading="People and organisations making property or project decisions." intro="Selotemna’s pathways are relevant to land buyers, families, investors, businesses and clients preparing engineering or construction requirements." /></div>
            <div><x-site.section-heading eyebrow="How to begin" heading="Review what is published, then choose the next step." intro="Explore Omu Creek, request an inspection, or share an engineering or construction requirement through the relevant contact pathway." /></div>
        </div>
    </section>

    <x-site.conversion-cta heading="Begin with the division that fits your requirement." intro="Explore Omu Creek, review the two divisions or contact Selotemna for a focused property or project conversation." secondary-label="Explore Omu Creek" :secondary-href="route('omu-creek')" />
@endsection
