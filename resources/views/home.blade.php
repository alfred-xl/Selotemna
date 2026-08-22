@extends('layouts.site')

@section('content')
    <section class="relative overflow-hidden bg-white">
        <div class="site-container-wide grid min-h-[calc(100svh-5rem)] items-center gap-10 py-16 md:min-h-[42rem] md:grid-cols-12 md:py-20 lg:gap-16">
            <div class="md:col-span-7 lg:col-span-6">
                <span class="eyebrow">Real Estate Development <span aria-hidden="true">·</span> Engineering &amp; Construction</span>
                <h1 class="max-w-3xl text-[clamp(2.35rem,6vw,4rem)] font-semibold leading-[1.06] tracking-[-0.045em]">Developing real estate. Delivering engineered solutions.</h1>
                <p class="mt-6 max-w-2xl text-lg leading-8 text-ink-500 md:text-xl">Selotemna brings real estate development and engineering and construction together under one corporate direction for buyers, investors, families and project clients.</p>
                <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:items-center">
                    <x-site.button href="{{ route('inspections.create') }}" data-event="book_inspection_click">Book an Inspection</x-site.button>
                    <x-site.button href="{{ route('projects.index') }}" variant="secondary">Explore Projects</x-site.button>
                </div>
                @if ($contact['whatsapp_url']) <a href="{{ $contact['whatsapp_url'] }}" class="text-link mt-5" data-event="whatsapp_click">Chat on WhatsApp</a> @endif
            </div>
            <div class="brand-media flex aspect-[4/5] min-h-[26rem] items-center justify-center p-8 md:col-span-5 lg:col-span-6 lg:aspect-[5/4]" aria-hidden="true">
                <div class="absolute left-[14%] top-[18%] h-[58%] w-px rotate-[28deg] bg-brand-100"></div>
                <div class="absolute bottom-[16%] right-[12%] h-px w-[62%] bg-brand-100"></div>
                <div class="relative flex size-56 items-center justify-center rounded-full border border-brand-100 bg-white/70 md:size-64">
                    <div class="absolute inset-6 rounded-full border border-brand-100"></div>
                    <img src="{{ asset('assets/logo.png') }}" alt="" class="relative w-28 md:w-32" width="189" height="153">
                </div>
            </div>
        </div>
    </section>

    <section class="section-space bg-ink-50" data-home-divisions>
        <div class="site-container">
            <x-site.section-heading eyebrow="What we do" heading="Two connected areas of property expertise." intro="Choose the division that matches your property or project requirement." />
            <div class="mt-12 grid border-b border-ink-200 lg:grid-cols-2 lg:divide-x lg:divide-ink-200">
                <x-site.division-pathway title="Real Estate Development" description="Explore Selotemna’s land, property and development opportunities, including Omu Creek." href="{{ route('real-estate-development') }}" link-label="Explore Real Estate Development" icon="development" />
                <x-site.division-pathway title="Engineering & Construction" description="Discuss residential, commercial and real-estate development requirements with the Selotemna team." href="{{ route('engineering-construction') }}" link-label="Explore Engineering & Construction" icon="engineering" />
            </div>
        </div>
    </section>

    <section class="section-space bg-white">
        <div class="site-container">
            <div class="flex flex-col gap-7 md:flex-row md:items-end md:justify-between">
                <x-site.section-heading eyebrow="Our projects" heading="Work across development and construction." intro="Browse the project categories used across Selotemna’s two divisions." />
                <a href="{{ route('projects.index') }}" class="text-link shrink-0">View All Projects</a>
            </div>
            <div class="mt-10"><x-site.project-tabs :groups="$projectGroups" id-prefix="home-projects" /></div>
        </div>
    </section>

    <x-site.omu-creek-summary :property="$featuredProperty" :contact="$contact" />

    <section class="section-space bg-brand-50">
        <div class="site-container max-w-4xl">
            <span class="eyebrow">About Selotemna</span>
            <h2 class="text-[clamp(1.875rem,4vw,2.75rem)] font-semibold leading-[1.14] tracking-[-0.03em]">One corporate direction across property and project delivery.</h2>
            <p class="mt-6 max-w-3xl text-lg leading-8 text-ink-500">Selotemna operates through Real Estate Development and Engineering &amp; Construction, serving people and organisations with different property and project requirements.</p>
            <a href="{{ route('about') }}" class="text-link mt-6">About Selotemna</a>
        </div>
    </section>

    <section class="section-space bg-white" data-home-faq>
        <div class="site-container grid gap-12 lg:grid-cols-[0.75fr_1.25fr] lg:gap-20">
            <div>
                <x-site.section-heading eyebrow="Frequently asked questions" heading="Key Omu Creek answers." intro="Start with the title, plot sizes and current outright prices." />
                <a href="{{ route('faq') }}" class="text-link mt-7">View All FAQs</a>
            </div>
            <x-site.faq :items="$faqs" id-prefix="home-faq" />
        </div>
    </section>

    <x-site.conversion-cta heading="Ready to discuss your next step?" intro="Request an inspection or contact Selotemna about a real estate, engineering or construction requirement." secondary-label="Contact Selotemna" :secondary-href="route('contact')">
        <p class="mt-5 text-sm leading-6 text-white/70">Submitting an inspection request does not automatically confirm an appointment.</p>
    </x-site.conversion-cta>
@endsection
