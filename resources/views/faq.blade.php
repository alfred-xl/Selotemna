@extends('layouts.site')

@section('content')
    <x-site.page-hero eyebrow="Frequently Asked Questions" heading="Omu Creek questions, answered." intro="Review the approved information about the opportunity, title, prices, payments, infrastructure, allocation and policies." :breadcrumbs="[['label' => 'Home', 'href' => route('home')], ['label' => 'FAQ']]" />

    <section class="section-space bg-white">
        <div class="site-container max-w-5xl space-y-16">
            @foreach ($faqGroups as $key => $group)
                <section aria-labelledby="faq-group-{{ $key }}">
                    <h2 id="faq-group-{{ $key }}" class="mb-7 text-[clamp(1.6rem,3vw,2.25rem)] font-semibold">{{ $group['label'] }}</h2>
                    <x-site.faq :items="$group['items']" :id-prefix="'faq-'.$key" />
                </section>
            @endforeach
        </div>
    </section>

    <x-site.conversion-cta heading="Ready to review Omu Creek in person?" intro="Submit an inspection request with your preferred date. A Selotemna representative will follow up; the request does not confirm an appointment." :primary-href="route('inspections.create', ['interest' => 'Omu Creek'])" secondary-label="View Omu Creek" :secondary-href="route('omu-creek')" />
@endsection
