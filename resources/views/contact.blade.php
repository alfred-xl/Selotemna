@extends('layouts.site')

@section('content')
    @php($hasPublishedContact = $contact['phone_url'] || $contact['whatsapp_url'] || $contact['email_url'] || $contact['address'] || $contact['business_hours'])

    <x-site.page-hero eyebrow="Contact" heading="Start with the right conversation." intro="Choose the pathway that matches your enquiry: Omu Creek and Real Estate Development, an Engineering & Construction requirement, or an inspection request." :breadcrumbs="[['label' => 'Home', 'href' => route('home')], ['label' => 'Contact']]" :media="$contactMedia" />

    <section class="section-space bg-white">
        <div class="site-container grid items-start gap-12 lg:grid-cols-[0.75fr_1.25fr] lg:gap-20">
            <x-site.section-heading eyebrow="Contact channels" heading="Reach Selotemna through a published channel." intro="Telephone, WhatsApp, email, office details and business hours are shown only when verified values have been configured." />

            <div>
                @if ($hasPublishedContact)
                    <x-site.contact-channels :contact="$contact" />
                @else
                    <div class="rounded-[1.5rem] border border-brand-100 bg-brand-50 p-6 md:p-8">
                        <span class="eyebrow">Omu Creek inspections</span>
                        <h2 class="text-2xl font-semibold md:text-3xl">The online inspection request is available.</h2>
                        <p class="mt-4 max-w-2xl leading-7 text-ink-500">Submit your preferred date and contact method through the dedicated form. Your request will be saved and a Selotemna representative will follow up; it does not confirm an appointment.</p>
                        <div class="mt-7"><x-site.button href="{{ route('inspections.create') }}">Request an Inspection</x-site.button></div>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <section class="section-space border-y border-ink-200 bg-ink-50">
        <div class="site-container">
            <x-site.section-heading eyebrow="Enquiry pathways" heading="Choose the subject that matches your requirement." intro="Each pathway takes you to the most relevant published information before you make contact or submit a request." />

            <div class="mt-10 grid gap-6 lg:grid-cols-3">
                <article class="flex min-h-full flex-col rounded-[1.5rem] border border-ink-200 bg-white p-6 md:p-8">
                    <span class="mb-7 flex size-11 items-center justify-center rounded-xl bg-brand-100 font-display font-semibold text-brand-700" aria-hidden="true">01</span>
                    <h2 class="text-2xl font-semibold">Real Estate Development</h2>
                    <p class="mt-4 flex-1 leading-7 text-ink-500">Review Omu Creek’s published title information, plot sizes, pricing, charges and policies before choosing an inspection or contact pathway.</p>
                    <a href="{{ route('real-estate-development') }}" class="text-link mt-6">Explore Real Estate Development <span aria-hidden="true">→</span></a>
                </article>

                <article class="flex min-h-full flex-col rounded-[1.5rem] border border-ink-200 bg-white p-6 md:p-8">
                    <span class="mb-7 flex size-11 items-center justify-center rounded-xl bg-brand-100 font-display font-semibold text-brand-700" aria-hidden="true">02</span>
                    <h2 class="text-2xl font-semibold">Engineering & Construction</h2>
                    <p class="mt-4 flex-1 leading-7 text-ink-500">Begin with the project type, proposed location, current stage and available scope information so the requirement can be understood.</p>
                    <a href="{{ route('engineering-construction') }}" class="text-link mt-6">Prepare a project enquiry <span aria-hidden="true">→</span></a>
                </article>

                <article class="flex min-h-full flex-col rounded-[1.5rem] border border-brand-700 bg-brand-700 p-6 text-white md:p-8">
                    <span class="mb-7 flex size-11 items-center justify-center rounded-xl bg-white/10 font-display font-semibold text-white" aria-hidden="true">03</span>
                    <h2 class="text-2xl font-semibold text-white">Omu Creek inspection</h2>
                    <p class="mt-4 flex-1 leading-7 text-white/75">Share your preferred date and contact method. The request is saved for follow-up and remains subject to appointment confirmation.</p>
                    <a href="{{ route('inspections.create') }}" class="mt-6 inline-flex min-h-11 items-center gap-2 font-semibold text-white underline decoration-white/40 underline-offset-4 hover:decoration-white">Request an Inspection <span aria-hidden="true">→</span></a>
                </article>
            </div>
        </div>
    </section>

    <section class="section-space bg-white">
        <div class="site-container grid gap-12 lg:grid-cols-2 lg:gap-20">
            <div>
                <x-site.section-heading eyebrow="Before you contact us" heading="Prepare the details that move the conversation forward." intro="A focused first message helps Selotemna understand the request without asking you to share unnecessary personal or financial information." />
            </div>
            <div class="grid gap-8 sm:grid-cols-2">
                <div class="plain-panel">
                    <h3>Property or inspection enquiry</h3>
                    <ul class="mt-5 space-y-3">
                        <li class="feature-line">Omu Creek as the opportunity</li>
                        <li class="feature-line">Your preferred date and period</li>
                        <li class="feature-line">The best verified way to reach you</li>
                    </ul>
                </div>
                <div class="plain-panel">
                    <h3>Project requirement</h3>
                    <ul class="mt-5 space-y-3">
                        <li class="feature-line">Project type and proposed location</li>
                        <li class="feature-line">Current project stage</li>
                        <li class="feature-line">Available scope or requirement summary</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <x-site.conversion-cta heading="Review the opportunity before choosing a date." intro="Read Omu Creek’s published information, then submit an inspection request when you are ready for Selotemna to follow up." primary-label="View Omu Creek" :primary-href="route('omu-creek')" secondary-label="Request an Inspection" :secondary-href="route('inspections.create')" />
@endsection
