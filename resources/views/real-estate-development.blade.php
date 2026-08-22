@extends('layouts.site')

@section('content')
    <x-site.page-hero eyebrow="Real Estate Development" heading="Land, property and development opportunities." intro="Explore verified opportunities and take the next step with information appropriate to your property requirement." :breadcrumbs="[['label' => 'Home', 'href' => route('home')], ['label' => 'Real Estate Development']]" />

    <section class="section-space bg-white">
        <div class="site-container grid gap-12 lg:grid-cols-2 lg:gap-20">
            <x-site.section-heading eyebrow="Division overview" heading="Real estate development under one clear pathway." intro="This division covers Selotemna’s land, property and development opportunities. Omu Creek is currently the only verified named opportunity published on the website." />
            <div>
                <h2 class="text-2xl font-semibold">Types of opportunities</h2>
                <ul class="mt-6 grid gap-3 text-lg text-ink-500 sm:grid-cols-2">
                    <li class="feature-line">Land opportunities</li><li class="feature-line">Property opportunities</li><li class="feature-line">Development opportunities</li><li class="feature-line">Inspection enquiries</li>
                </ul>
            </div>
        </div>
    </section>

    <x-site.omu-creek-summary :property="$featuredProperty" :contact="$contact" />

    <section class="section-space bg-white">
        <div class="site-container">
            <x-site.section-heading eyebrow="Who this division serves" heading="Property pathways for different plans." intro="Information is structured for first-time buyers, investors, families, diaspora buyers, businesses and land buyers without assuming that every opportunity fits every visitor." />
            <div class="mt-10 grid gap-6 md:grid-cols-3">
                <div class="plain-panel"><h3>Buying and building</h3><p>Review verified title, plot, price and allocation information before requesting an inspection.</p></div>
                <div class="plain-panel"><h3>Property investment</h3><p>Assess current published facts and contact Selotemna for availability and the next step.</p></div>
                <div class="plain-panel"><h3>Business requirements</h3><p>Discuss land or development requirements directly with the relevant Selotemna team.</p></div>
            </div>
        </div>
    </section>

    <section class="section-space bg-ink-50">
        <div class="site-container">
            <x-site.section-heading eyebrow="Enquiries and inspections" heading="Request, follow-up, confirmation." intro="Share the opportunity you are interested in, your preferred date and contact details. A Selotemna representative will follow up; submitting the request does not confirm an appointment." />
            <div class="mt-8"><x-site.button href="{{ route('inspections.create', ['interest' => 'Omu Creek']) }}">Request an Inspection</x-site.button></div>
        </div>
    </section>

    <section class="section-space bg-white">
        <div class="site-container">
            <div class="flex flex-col gap-6 md:flex-row md:items-end md:justify-between"><x-site.section-heading eyebrow="Relevant projects" heading="Real estate development projects." intro="Project categories remain available even when no verified record is published." /><a href="{{ route('projects.index') }}" class="text-link shrink-0">View All Projects</a></div>
            <div class="mt-10"><x-site.project-tabs :groups="$projectGroups" id-prefix="real-estate-projects" /></div>
        </div>
    </section>

    <x-site.conversion-cta heading="Explore Omu Creek or start an enquiry." intro="Review the complete opportunity information, request an inspection, or contact Selotemna about a real estate development requirement." secondary-label="View Omu Creek" :secondary-href="route('omu-creek')" />
@endsection
