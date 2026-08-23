@extends('layouts.site')

@section('content')
    <x-site.page-hero eyebrow="Projects" heading="One published project. A clear view of its current stage." intro="Omu Creek is currently Selotemna’s sole published project and is classified as Upcoming under Real Estate Development." :breadcrumbs="[['label' => 'Home', 'href' => route('home')], ['label' => 'Projects']]" />

    <section class="section-space bg-white">
        <div class="site-container">
            <x-site.section-heading eyebrow="Published project" heading="Explore Omu Creek." intro="Review the approved project summary, then continue to the dedicated page for title, prices, charges, allocation information and inspection options." />
            <div class="mt-10"><x-site.project-tabs :groups="$projectGroups" id-prefix="all-projects" /></div>
        </div>
    </section>

    <x-site.conversion-cta heading="Continue with the Omu Creek opportunity." intro="Review the complete published information or request an inspection with your preferred date and contact details." primary-label="View Omu Creek" :primary-href="route('omu-creek')" secondary-label="Request an Inspection" :secondary-href="route('inspections.create', ['interest' => 'Omu Creek'])" />
@endsection
