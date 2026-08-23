@extends('layouts.site')

@section('content')
    <x-site.page-hero eyebrow="Engineering & Construction" heading="Bring your project requirement into focus." intro="Start with the information you already have so Selotemna can understand the proposed site, scope, current stage and appropriate next conversation." :breadcrumbs="[['label' => 'Home', 'href' => route('home')], ['label' => 'Engineering & Construction']]" />

    <section class="section-space bg-white">
        <div class="site-container grid items-start gap-12 lg:grid-cols-[0.9fr_1.1fr] lg:gap-20">
            <div>
                <x-site.section-heading eyebrow="Division introduction" heading="A practical starting point for engineering and construction enquiries." intro="This division provides a direct pathway for people and organisations preparing engineering, building or construction requirements." />
                <h2 class="mt-10 text-2xl font-semibold">Information that helps begin the discussion</h2>
                <ul class="mt-6 grid gap-3 sm:grid-cols-2">
                    <li class="feature-line">Project type</li>
                    <li class="feature-line">Proposed location</li>
                    <li class="feature-line">Current project stage</li>
                    <li class="feature-line">Available scope information</li>
                    <li class="feature-line">Site information</li>
                    <li class="feature-line">Preferred contact method</li>
                </ul>
            </div>
            @if ($media['earthworks_truck'])
                <figure class="overflow-hidden rounded-[1.5rem] border border-ink-200 bg-ink-50" data-reveal="media">
                    <img src="{{ $media['earthworks_truck'] }}" alt="Dump truck operating on an active earthworks site" class="aspect-[4/5] w-full object-cover object-bottom" width="1280" height="1920" loading="eager">
                </figure>
            @else
                <div class="brand-media flex aspect-[4/5] items-center justify-center p-8" aria-hidden="true" data-reveal="media">
                    <img src="{{ asset('assets/logo.png') }}" alt="" class="w-28 rounded-xl bg-white p-3" width="189" height="153">
                </div>
            @endif
        </div>
    </section>

    <section class="section-space bg-ink-50">
        <div class="site-container grid items-center gap-12 lg:grid-cols-[1.05fr_0.95fr] lg:gap-20">
            @if ($media['building_construction'])
                <figure class="overflow-hidden rounded-[1.5rem] border border-ink-200 bg-white" data-reveal="media">
                    <img src="{{ $media['building_construction'] }}" alt="Construction worker applying render to a building façade from scaffolding" class="aspect-video w-full object-cover" width="1280" height="720" loading="lazy">
                </figure>
            @else
                <div class="brand-media flex aspect-video items-center justify-center p-8" aria-hidden="true" data-reveal="media">
                    <img src="{{ asset('assets/logo.png') }}" alt="" class="w-24 rounded-xl bg-white p-3" width="189" height="153">
                </div>
            @endif
            <div>
                <x-site.section-heading eyebrow="Enquiry pathway" heading="Move from an initial requirement to a focused next step." intro="The first conversation is intended to establish context. It does not promise a scope, programme or delivery outcome before the available information has been reviewed." />
                <ol class="mt-8 space-y-5 text-ink-500" data-reveal-group>
                    <li class="numbered-step" data-reveal><span>1</span><p>Share the proposed project, location, current stage and the information already available.</p></li>
                    <li class="numbered-step" data-reveal><span>2</span><p>Allow the Selotemna team to review the requirement and identify any important follow-up questions.</p></li>
                    <li class="numbered-step" data-reveal><span>3</span><p>Continue through a configured contact channel with a clearer understanding of the next conversation.</p></li>
                </ol>
            </div>
        </div>
    </section>

    <x-site.testimonial-section :items="$testimonials" eyebrow="Engineering & Construction experiences" heading="Feedback relevant to project requirements." />

    <x-site.conversion-cta heading="Start an engineering or construction conversation." intro="Contact Selotemna with the proposed project type, location, current stage and the scope information you already have." primary-label="Start a Project Enquiry" :primary-href="route('contact')" secondary-label="About Selotemna" :secondary-href="route('about')" />
@endsection
