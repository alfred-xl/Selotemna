@extends('layouts.site')

@section('content')
    <x-site.page-hero eyebrow="Contact" heading="Start with the right Selotemna pathway." intro="Contact the team about a real estate opportunity, an inspection request, or an engineering and construction requirement." :breadcrumbs="[['label' => 'Home', 'href' => route('home')], ['label' => 'Contact']]" />

    <section class="section-space bg-white">
        <div class="site-container grid gap-12 lg:grid-cols-[0.75fr_1.25fr] lg:gap-20">
            <x-site.section-heading eyebrow="Verified contact channels" heading="Use a published channel." intro="Only contact details configured for Selotemna are shown here." />
            <div>
                <x-site.contact-channels :contact="$contact" />
                @if (! collect($contact)->filter()->isNotEmpty())
                    <div class="rounded-[1.5rem] border border-ink-200 bg-ink-50 p-6 md:p-8"><h2 class="text-2xl font-semibold">Direct contact details are not currently published.</h2><p class="mt-4 leading-7 text-ink-500">Use the division and inspection pathways below to review the information already available on the website.</p></div>
                @endif
            </div>
        </div>
    </section>

    <section class="section-space bg-ink-50"><div class="site-container"><x-site.section-heading eyebrow="Enquiry pathways" heading="Choose the subject of your enquiry." /><div class="mt-10 grid gap-6 md:grid-cols-3"><div class="plain-panel"><h3>Real Estate Development</h3><p>Explore land, property and development opportunities, including Omu Creek.</p><a href="{{ route('real-estate-development') }}" class="text-link mt-4">Explore the division</a></div><div class="plain-panel"><h3>Engineering & Construction</h3><p>Review the general capability areas and prepare the scope of your proposed project.</p><a href="{{ route('engineering-construction') }}" class="text-link mt-4">Discuss a project</a></div><div class="plain-panel"><h3>Inspection request</h3><p>Share a preferred date and contact details through the available request path.</p><a href="{{ route('inspections.create') }}" class="text-link mt-4">Book an Inspection</a></div></div></div></section>

    <x-site.conversion-cta heading="Review Omu Creek before requesting an inspection." intro="Read the verified title, plot sizes, pricing, charges, allocation information and policies in one place." primary-label="View Omu Creek" :primary-href="route('omu-creek')" secondary-label="Book an Inspection" :secondary-href="route('inspections.create')" />
@endsection
