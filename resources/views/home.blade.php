@extends('layouts.site')

@section('content')
    <section class="relative isolate flex min-h-[38rem] w-full overflow-hidden bg-ink-950 sm:min-h-[40rem] md:min-h-[42rem] lg:min-h-[clamp(40rem,calc(100svh-5rem),45rem)]" data-home-hero data-hero-sequence>
        <div class="site-container relative z-20 flex items-end py-12 sm:py-16 md:items-center md:py-20 lg:py-24">
            <div class="w-full max-w-[47.5rem]">
                <span class="eyebrow max-w-md text-brand-100">Real Estate Development <span aria-hidden="true">·</span> Engineering &amp; Construction</span>
                <h1 class="max-w-[47.5rem] text-[clamp(2.5rem,11vw,2.875rem)] font-semibold leading-[1.02] tracking-[-0.045em] text-white [text-wrap:wrap] md:text-[3.5rem] lg:text-[clamp(4rem,5vw,4.25rem)]">Developing places. Building with purpose.</h1>
                <p class="mt-6 max-w-2xl text-base leading-7 text-white/85 sm:text-lg sm:leading-8 md:text-xl">Selotemna brings Real Estate Development and Engineering &amp; Construction together, helping clients explore property opportunities and begin focused project conversations.</p>
                <div class="mt-8" data-hero-item data-hero-actions>
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                        <x-site.button href="{{ route('inspections.create', ['interest' => 'Omu Creek']) }}" variant="reversed" class="w-full sm:w-auto" data-event="book_inspection_click">Request an Inspection</x-site.button>
                        @if ($featuredProperty)
                            <x-site.button href="{{ route('omu-creek') }}" variant="outline-reversed" class="w-full sm:w-auto">Explore Omu Creek</x-site.button>
                        @endif
                    </div>
                    @if ($contact['whatsapp_url'])
                        <a href="{{ $contact['whatsapp_url'] }}" class="text-link mt-4 text-white decoration-white/60 hover:text-white focus-visible:ring-white focus-visible:ring-offset-ink-950" data-event="whatsapp_click" data-hero-whatsapp>Chat on WhatsApp</a>
                    @endif
                </div>
            </div>
        </div>

        @if ($media['development_aerial'])
            <figure class="pointer-events-none absolute inset-0 z-0 overflow-hidden" data-hero-item data-hero-media>
                <img src="{{ $media['development_aerial'] }}" alt="Aerial view of a large waterfront development and active construction site" class="h-full w-full object-cover object-[58%_52%] md:object-[55%_52%] lg:object-[52%_52%]" width="1280" height="960" fetchpriority="high">
            </figure>
        @else
            <div class="brand-media pointer-events-none absolute inset-0 z-0 flex items-center justify-center rounded-none border-0 bg-brand-950 p-8" aria-hidden="true" data-hero-item data-hero-media>
                <div class="absolute left-[14%] top-[18%] h-[58%] w-px rotate-[28deg] bg-brand-100/40"></div>
                <div class="absolute bottom-[16%] right-[12%] h-px w-[62%] bg-brand-100/40"></div>
                <div class="relative flex size-40 items-center justify-center rounded-full border border-brand-100/50 bg-white/80 md:size-48">
                    <div class="absolute inset-6 rounded-full border border-brand-100"></div>
                    <img src="{{ asset('assets/logo.png') }}" alt="" class="relative w-24 md:w-28" width="189" height="153">
                </div>
            </div>
        @endif

        <div class="pointer-events-none absolute inset-0 z-10 bg-ink-950/70" aria-hidden="true" data-hero-overlay></div>
    </section>

    <section class="section-space bg-ink-50" data-home-divisions>
        <div class="site-container grid items-start gap-12 lg:grid-cols-[minmax(0,0.62fr)_minmax(0,1fr)] lg:gap-16 xl:gap-20">
            <x-site.section-heading eyebrow="What we do" heading="Explore our developments. Discuss your next project." intro="Selotemna operates through Real Estate Development and Engineering & Construction, giving visitors a clear way to explore our development work, review current opportunities or begin a project conversation." />
            <div class="border-y border-ink-200" data-reveal-group>
                <x-site.division-pathway number="01" title="Real Estate Development" description="Explore Selotemna’s real-estate developments and property opportunities. Omu Creek, our latest project, is the current featured opportunity for buyers and investors to review before making an enquiry or requesting an inspection." href="{{ route('real-estate-development') }}" link-label="Explore Real Estate Development" />
                <x-site.division-pathway number="02" title="Engineering & Construction" description="Bring an engineering or construction requirement to Selotemna. Share the site, scope and current stage so the team can understand the project and identify the appropriate next step." href="{{ route('engineering-construction') }}" link-label="Explore Engineering & Construction" />
            </div>
        </div>
    </section>

    @if ($featuredProperty)
        <x-site.omu-creek-summary :property="$featuredProperty" :contact="$contact" compact />
    @endif

    <section class="bg-brand-50 py-16 md:py-20 lg:py-24" data-home-about>
        <div class="site-container grid gap-7 border-y border-brand-100 py-8 md:py-10 lg:grid-cols-[minmax(0,0.42fr)_minmax(0,1fr)] lg:gap-16 lg:py-12" data-reveal>
            <span class="eyebrow mb-0 lg:pt-2">About Selotemna</span>
            <div class="max-w-4xl">
                <h2 class="text-[clamp(1.875rem,4vw,2.75rem)] font-semibold leading-[1.14] tracking-[-0.03em]">Property opportunities and project requirements, brought under one company.</h2>
                <p class="mt-5 max-w-3xl text-lg leading-8 text-ink-500">Selotemna operates through Real Estate Development and Engineering &amp; Construction. We help clients review published development opportunities and begin focused conversations about engineering or construction requirements.</p>
                <a href="{{ route('about') }}" class="text-link mt-6">Learn About Selotemna</a>
            </div>
        </div>
    </section>

    <x-site.testimonial-section :items="$testimonials" heading="Experiences connected to our services." intro="Only feedback approved for public use appears here." />

    @if ($faqs)
        <section class="section-space bg-white" data-home-faq>
            <div class="site-container grid gap-12 lg:grid-cols-[0.75fr_1.25fr] lg:gap-20">
                <div>
                    <x-site.section-heading eyebrow="Frequently asked questions" heading="Start with the essential Omu Creek facts." intro="Review the opportunity, land title, available plot sizes and current outright prices." />
                    <a href="{{ route('faq') }}" class="text-link mt-7">View All FAQs</a>
                </div>
                <x-site.faq :items="$faqs" id-prefix="home-faq" />
            </div>
        </section>
    @endif

    <x-site.conversion-cta heading="Choose a clear next step." intro="Request an Omu Creek inspection or contact Selotemna about a Real Estate Development, Engineering or Construction requirement." secondary-label="Contact Selotemna" :secondary-href="route('contact')">
        <p class="mt-5 text-sm leading-6 text-white/70">Submitting an inspection request does not automatically confirm an appointment.</p>
    </x-site.conversion-cta>
@endsection
