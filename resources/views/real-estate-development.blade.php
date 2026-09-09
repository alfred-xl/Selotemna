@extends('layouts.site')

@section('content')
    <x-site.page-hero
        variant="overlay"
        heading="Real Estate Development"
        intro="Explore Selotemna’s development work and current property opportunities."
        :background-image="$heroImage"
        image-position="50% 50%"
        :show-breadcrumbs="false"
        :show-eyebrow="false"
        alignment="center"
        overlay-size="compact"
    />

    <section class="section-space bg-white" data-development-introduction>
        <div class="site-container grid items-start gap-12 lg:grid-cols-[minmax(0,0.9fr)_minmax(0,1.1fr)] lg:gap-20">
            <x-site.section-heading eyebrow="Our development work" heading="Development opportunities presented with the facts in view." intro="Selotemna has undertaken previous real estate development projects. Omu Creek is the latest project and the current opportunity with detailed public information available." />
            <div class="border-t border-ink-200 pt-8 lg:border-l lg:border-t-0 lg:pl-12 lg:pt-0" data-reveal>
                <h2 class="text-2xl font-semibold">What you can review</h2>
                <ul class="mt-6 border-y border-ink-200 text-lg text-ink-500" data-reveal-group>
                    @foreach (['Published project information', 'Current plot sizes and outright prices', 'Title and documentation information', 'Inspection and enquiry options'] as $item)
                        <li class="border-b border-ink-200 py-4 leading-7 last:border-b-0" data-reveal>{{ $item }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </section>

    @if ($featuredProperty)
        <x-site.omu-creek-summary :property="$featuredProperty" :contact="$contact" />
    @else
        <section class="section-space bg-ink-50" data-development-empty-state>
            <div class="site-container border-y border-ink-200 py-10">
                <h2 class="text-2xl font-semibold">No property opportunity is currently published.</h2>
                <p class="mt-3 max-w-3xl leading-7 text-ink-500">Published Real Estate Development opportunities will appear here when their information is ready for public review.</p>
                <a href="{{ route('contact') }}" class="text-link mt-6">Contact Selotemna</a>
            </div>
        </section>
    @endif

    <x-site.testimonial-section :items="$testimonials" eyebrow="Real Estate Development experiences" heading="Feedback relevant to property decisions." />

    @if ($featuredProperty)
        <x-site.conversion-cta
            heading="Interested in Omu Creek?"
            intro="Review the complete project information or select the plot size you are considering."
            primary-label="Select a Plot Size"
            :primary-href="route('project-enquiries.create')"
            secondary-label="View Full Project Details"
            :secondary-href="route('omu-creek')"
        >
            <p class="mt-5 text-sm leading-6 text-white/70">Submitting an enquiry does not reserve a plot; availability remains subject to confirmation.</p>
        </x-site.conversion-cta>
    @endif
@endsection
