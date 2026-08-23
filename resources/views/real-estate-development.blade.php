@extends('layouts.site')

@section('content')
    <x-site.page-hero eyebrow="Real Estate Development" heading="Explore property opportunities with the facts in view." intro="Review Selotemna’s published land and development opportunities, understand the available information and choose an appropriate inspection or enquiry pathway." :breadcrumbs="[['label' => 'Home', 'href' => route('home')], ['label' => 'Real Estate Development']]" />

    <section class="section-space bg-white">
        <div class="site-container grid gap-12 lg:grid-cols-2 lg:gap-20">
            <x-site.section-heading eyebrow="Division overview" heading="A clear pathway into Selotemna’s published opportunities." intro="Real Estate Development brings together Selotemna’s land, property and development opportunities. Omu Creek is currently the only published project and is classified as Upcoming." />
            <div>
                <h2 class="text-2xl font-semibold">What you can review</h2>
                <ul class="mt-6 grid gap-3 text-lg text-ink-500 sm:grid-cols-2">
                    <li class="feature-line">Published land opportunities</li><li class="feature-line">Current prices and plot sizes</li><li class="feature-line">Available documentation details</li><li class="feature-line">Inspection request pathways</li>
                </ul>
            </div>
        </div>
    </section>

    <x-site.omu-creek-summary :property="$featuredProperty" :contact="$contact" />

    <section class="section-space bg-white">
        <div class="site-container">
            <x-site.section-heading eyebrow="Evaluate the opportunity" heading="Move from interest to an informed next step." intro="Use the published information to understand the opportunity, then contact Selotemna for current availability or request an inspection." />
            <div class="mt-10 grid gap-6 md:grid-cols-3" data-reveal-group>
                <div class="plain-panel" data-reveal><h3>1. Review the details</h3><p>Read the title, plot, pricing, payment, documentation and allocation information currently published.</p></div>
                <div class="plain-panel" data-reveal><h3>2. Confirm availability</h3><p>Contact Selotemna because property information and availability remain subject to confirmation.</p></div>
                <div class="plain-panel" data-reveal><h3>3. Request an inspection</h3><p>Share your preferred date and contact details, then wait for a representative to confirm the next step.</p></div>
            </div>
        </div>
    </section>

    <section class="section-space bg-ink-50">
        <div class="site-container">
            <x-site.section-heading eyebrow="Enquiries and inspections" heading="A request first, followed by confirmation." intro="Share Omu Creek as your opportunity of interest, your preferred date and your contact details. A Selotemna representative will follow up; the submission itself does not confirm an appointment." />
            <div class="mt-8"><x-site.button href="{{ route('inspections.create', ['interest' => 'Omu Creek']) }}">Request an Inspection</x-site.button></div>
        </div>
    </section>

    <x-site.testimonial-section :items="$testimonials" eyebrow="Real Estate Development experiences" heading="Feedback relevant to property decisions." />

    <x-site.conversion-cta heading="Continue with Omu Creek or a direct enquiry." intro="Review the complete opportunity, request an inspection, or contact Selotemna about a Real Estate Development requirement." secondary-label="View Omu Creek" :secondary-href="route('omu-creek')" />
@endsection
