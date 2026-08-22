@extends('layouts.site')

@section('content')
    <x-site.page-hero eyebrow="About Selotemna" heading="Real estate development and project delivery under one corporate direction." intro="Selotemna serves property buyers and project clients through two connected public divisions." :breadcrumbs="[['label' => 'Home', 'href' => route('home')], ['label' => 'About']]" />

    <section class="section-space bg-white">
        <div class="site-container grid gap-12 lg:grid-cols-12 lg:gap-20">
            <div class="lg:col-span-5"><x-site.section-heading eyebrow="The company" heading="A clear structure for property and project requirements." /></div>
            <div class="space-y-5 text-lg leading-8 text-ink-500 lg:col-span-7">
                <p>Selotemna brings Real Estate Development and Engineering &amp; Construction together within one company.</p>
                <p>Its public website is designed to help visitors identify the appropriate division, review verified information and take a clear next step.</p>
                <p class="font-semibold text-ink-800">RC 7361086</p>
            </div>
        </div>
    </section>

    <section class="section-space bg-ink-50">
        <div class="site-container">
            <x-site.section-heading eyebrow="Our divisions" heading="Two primary areas of work." />
            <div class="mt-12 grid border-b border-ink-200 lg:grid-cols-2 lg:divide-x lg:divide-ink-200">
                <x-site.division-pathway title="Real Estate Development" description="Land, property and development opportunities for buyers, families, investors, businesses and land buyers." href="{{ route('real-estate-development') }}" link-label="Explore the division" icon="development" />
                <x-site.division-pathway title="Engineering & Construction" description="Project discussions for residential, commercial and real-estate development requirements." href="{{ route('engineering-construction') }}" link-label="Explore the division" icon="engineering" />
            </div>
        </div>
    </section>

    <section class="section-space bg-white">
        <div class="site-container grid gap-12 lg:grid-cols-2 lg:gap-20">
            <div><x-site.section-heading eyebrow="Who we serve" heading="Different audiences, clearer pathways." intro="Selotemna’s public information supports first-time buyers, property investors, Nigerians in the diaspora, families, businesses, high-net-worth buyers, land buyers, and engineering and construction clients." /></div>
            <div><x-site.section-heading eyebrow="Our approach" heading="Begin with the requirement." intro="Visitors can explore verified opportunities and project categories, request an inspection, or contact the appropriate division for a focused discussion." /></div>
        </div>
    </section>

    <x-site.conversion-cta heading="Choose the next step that fits your requirement." intro="Explore the two divisions, review projects or request an inspection for a relevant opportunity." secondary-label="Explore Projects" :secondary-href="route('projects.index')" />
@endsection
