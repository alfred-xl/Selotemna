@extends('layouts.site')

@section('content')
    <x-site.page-hero
        variant="overlay"
        heading="Engineering & Construction"
        intro="Share your project requirements and begin a focused engineering or construction conversation."
        :background-image="$heroImage"
        image-position="38% 48%"
        :show-breadcrumbs="false"
        :show-eyebrow="false"
        alignment="center"
        overlay-size="compact"
    />

    <section class="section-space bg-white" data-engineering-introduction>
        <div class="site-container grid items-start gap-12 lg:grid-cols-[minmax(0,1.05fr)_minmax(20rem,0.95fr)] lg:gap-20">
            <div>
                <x-site.section-heading eyebrow="Starting a project enquiry" heading="Start with the information you already have." intro="Share the proposed project, location, current stage and available scope information so Selotemna can understand the requirement and identify the important follow-up questions." />
                <h3 class="mt-10 text-2xl font-semibold">Information that helps begin the discussion</h3>
                <ul class="mt-6 grid gap-3 sm:grid-cols-2">
                    <li class="feature-line">Project type</li>
                    <li class="feature-line">Proposed location</li>
                    <li class="feature-line">Current project stage</li>
                    <li class="feature-line">Available scope information</li>
                    <li class="feature-line">Site information</li>
                    <li class="feature-line">Preferred contact method</li>
                </ul>
            </div>
            @if ($bodyImage)
                <figure class="w-full max-w-xl justify-self-center overflow-hidden rounded-[1.5rem] border border-ink-200 bg-ink-50 lg:justify-self-end" data-reveal="media">
                    <img src="{{ $bodyImage }}" alt="Dump truck operating on an active earthworks site" class="aspect-[4/5] w-full object-cover object-bottom" width="1280" height="1920" loading="eager">
                </figure>
            @else
                <div class="brand-media flex aspect-[4/5] w-full max-w-xl items-center justify-center justify-self-center p-8 lg:justify-self-end" aria-hidden="true" data-reveal="media">
                    <img src="{{ asset('assets/logo.png') }}" alt="" class="w-28 rounded-xl bg-white p-3" width="189" height="153">
                </div>
            @endif
        </div>
    </section>

    <section class="section-space bg-ink-50" data-engineering-process>
        <div class="site-container grid items-start gap-12 lg:grid-cols-[minmax(0,0.82fr)_minmax(0,1.18fr)] lg:gap-20">
            <x-site.section-heading eyebrow="What happens next" heading="From an initial enquiry to a clearer project conversation." intro="The first conversation is intended to establish context. It does not promise a scope, programme or delivery outcome before the available information has been reviewed." />
            <div class="border-y border-ink-200" data-reveal-group>
                @php
                    $steps = [
                        ['title' => 'Share the requirement', 'description' => 'Provide the project type, proposed location, current stage and information already available.'],
                        ['title' => 'The information is reviewed', 'description' => 'Selotemna reviews the requirement and identifies any important follow-up questions.'],
                        ['title' => 'Continue the conversation', 'description' => 'Continue through the appropriate contact channel with a clearer understanding of what should be discussed next.'],
                    ];
                @endphp
                <ol>
                    @foreach ($steps as $step)
                        <li class="grid gap-4 border-b border-ink-200 py-7 last:border-b-0 sm:grid-cols-[3rem_minmax(0,1fr)] sm:gap-6 md:py-8" data-reveal>
                            <span class="pt-1 font-display text-sm font-semibold tracking-[0.16em] text-brand-700" aria-hidden="true">{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                            <div>
                                <h3 class="text-xl font-semibold leading-tight md:text-2xl">{{ $step['title'] }}</h3>
                                <p class="mt-3 leading-7 text-ink-500">{{ $step['description'] }}</p>
                            </div>
                        </li>
                    @endforeach
                </ol>
            </div>
        </div>
    </section>

    <x-site.testimonial-section :items="$testimonials" eyebrow="Client feedback" heading="Experiences connected to engineering and construction requirements." />

    <x-site.conversion-cta
        heading="Have a project requirement to discuss?"
        intro="Share the proposed project type, location, current stage and available scope information with Selotemna."
        primary-label="Start a Project Enquiry"
        :primary-href="route('contact')"
        :secondary-label="$secondaryContact['label'] ?? null"
        :secondary-href="$secondaryContact['href'] ?? null"
    />
@endsection
