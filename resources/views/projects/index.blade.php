@extends('layouts.site')

@section('content')
    <x-site.page-hero eyebrow="Projects" heading="Projects across development and construction." intro="Explore the categories used for verified Selotemna work across Real Estate Development and Engineering & Construction." :breadcrumbs="[['label' => 'Home', 'href' => route('home')], ['label' => 'Projects']]" />
    <section class="section-space bg-white"><div class="site-container"><x-site.section-heading eyebrow="Project categories" heading="Ongoing, completed and upcoming work." intro="A project appears in a category only after its status and public information have been verified." /><div class="mt-10"><x-site.project-tabs :groups="$projectGroups" id-prefix="all-projects" /></div></div></section>
    <x-site.conversion-cta heading="Have a development or construction requirement?" intro="Contact Selotemna to discuss the division, project type and next step that fits your requirement." primary-label="Discuss a Project" :primary-href="route('contact')" secondary-label="Explore Our Divisions" :secondary-href="route('real-estate-development')" />
@endsection
