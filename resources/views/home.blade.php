@extends('layouts.site')

@section('content')
    <section class="relative overflow-hidden bg-white">
        <div class="site-container-wide grid min-h-[calc(100svh-5rem)] items-center gap-10 py-16 md:min-h-[42rem] md:grid-cols-12 md:py-20 lg:gap-16" data-hero-sequence>
            <div class="md:col-span-7 lg:col-span-6">
                <span class="eyebrow">Real Estate Development <span aria-hidden="true">·</span> Engineering &amp; Construction</span>
                <h1 class="max-w-3xl text-[clamp(2.35rem,6vw,4rem)] font-semibold leading-[1.06] tracking-[-0.045em]">Developing places. Building with purpose.</h1>
                <p class="mt-6 max-w-2xl text-lg leading-8 text-ink-500 md:text-xl">Selotemna brings Real Estate Development and Engineering &amp; Construction together, helping clients explore property opportunities and begin focused project conversations.</p>
                <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:items-center" data-hero-item>
                    <x-site.button href="{{ route('inspections.create', ['interest' => 'Omu Creek']) }}" data-event="book_inspection_click">Request an Inspection</x-site.button>
                    <x-site.button href="{{ route('omu-creek') }}" variant="secondary">Explore Omu Creek</x-site.button>
                </div>
                @if ($contact['whatsapp_url']) <a href="{{ $contact['whatsapp_url'] }}" class="text-link mt-5" data-event="whatsapp_click">Chat on WhatsApp</a> @endif
            </div>
            <div class="md:col-span-5 lg:col-span-6">
                @if ($media['development_aerial'])
                    <figure class="overflow-hidden rounded-[1.5rem] border border-brand-100 bg-brand-50" data-hero-item data-hero-media>
                        <img src="{{ $media['development_aerial'] }}" alt="Aerial view of a large waterfront development and active construction site" class="aspect-[4/3] w-full object-cover" width="1280" height="960" fetchpriority="high">
                    </figure>
                @else
                    <div class="brand-media flex aspect-[4/3] items-center justify-center p-8" aria-hidden="true" data-hero-item data-hero-media>
                        <div class="absolute left-[14%] top-[18%] h-[58%] w-px rotate-[28deg] bg-brand-100"></div>
                        <div class="absolute bottom-[16%] right-[12%] h-px w-[62%] bg-brand-100"></div>
                        <div class="relative flex size-40 items-center justify-center rounded-full border border-brand-100 bg-white/70 md:size-48">
                            <div class="absolute inset-6 rounded-full border border-brand-100"></div>
                            <img src="{{ asset('assets/logo.png') }}" alt="" class="relative w-24 md:w-28" width="189" height="153">
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <section class="section-space bg-ink-50" data-home-divisions>
        <div class="site-container">
            <x-site.section-heading eyebrow="What we do" heading="Two divisions. One clear place to begin." intro="Choose the pathway that matches the opportunity you want to explore or the project requirement you want to discuss." />
            <div class="mt-12 grid border-b border-ink-200 lg:grid-cols-2 lg:divide-x lg:divide-ink-200" data-reveal-group>
                <x-site.division-pathway title="Real Estate Development" description="Review Selotemna’s published land and development opportunities, beginning with Omu Creek." href="{{ route('real-estate-development') }}" link-label="Explore Real Estate Development" icon="development" />
                <x-site.division-pathway title="Engineering & Construction" description="Bring an engineering or construction requirement into a focused conversation about the site, scope and next step." href="{{ route('engineering-construction') }}" link-label="Explore Engineering & Construction" icon="engineering" />
            </div>
        </div>
    </section>

    <x-site.omu-creek-summary :property="$featuredProperty" :contact="$contact" />

    <section class="section-space bg-brand-50">
        <div class="site-container max-w-4xl" data-reveal>
            <span class="eyebrow">About Selotemna</span>
            <h2 class="text-[clamp(1.875rem,4vw,2.75rem)] font-semibold leading-[1.14] tracking-[-0.03em]">A focused route from requirement to next step.</h2>
            <p class="mt-6 max-w-3xl text-lg leading-8 text-ink-500">Selotemna helps visitors identify the relevant division, review the information currently available and choose an appropriate inspection or enquiry pathway.</p>
            <a href="{{ route('about') }}" class="text-link mt-6">About Selotemna</a>
        </div>
    </section>

    <x-site.testimonial-section :items="$testimonials" heading="Experiences connected to our services." intro="Only feedback approved for public use appears here." />

    <section class="section-space bg-white" data-home-faq>
        <div class="site-container grid gap-12 lg:grid-cols-[0.75fr_1.25fr] lg:gap-20">
            <div>
                <x-site.section-heading eyebrow="Frequently asked questions" heading="Start with the essential Omu Creek facts." intro="Review the opportunity, land title, available plot sizes and current outright prices." />
                <a href="{{ route('faq') }}" class="text-link mt-7">View All FAQs</a>
            </div>
            <x-site.faq :items="$faqs" id-prefix="home-faq" />
        </div>
    </section>

    <x-site.conversion-cta heading="Choose a clear next step." intro="Request an Omu Creek inspection or contact Selotemna about a Real Estate Development, Engineering or Construction requirement." secondary-label="Contact Selotemna" :secondary-href="route('contact')">
        <p class="mt-5 text-sm leading-6 text-white/70">Submitting an inspection request does not automatically confirm an appointment.</p>
    </x-site.conversion-cta>
@endsection
