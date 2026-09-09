@extends('layouts.site')

@section('content')
    <x-site.page-hero
        variant="overlay"
        heading="Projects"
        intro="Explore Selotemna’s development work by project stage."
        :background-image="$heroImage"
        image-position="50% 50%"
        :show-breadcrumbs="false"
        :show-eyebrow="false"
        alignment="center"
        overlay-size="compact"
    />

    <section class="section-space bg-white" data-projects-index>
        <div class="site-container">
            <x-site.section-heading eyebrow="Project portfolio" heading="Explore projects by stage." intro="Omu Creek is Selotemna’s latest project and the current project with detailed public information available. Previous project profiles will appear as their information is approved for publication." />
            <div class="mt-10">
                <x-site.project-tabs
                    :groups="$projectGroups"
                    id-prefix="all-projects"
                    featured
                    :inspection-href="route('inspections.create', ['interest' => 'Omu Creek'])"
                    stage-note="Upcoming Project describes the project stage; property availability remains subject to confirmation."
                    :empty-states="[
                        'ongoing' => [
                            'heading' => 'No ongoing project profiles are currently published.',
                            'description' => 'New project information will appear here when it has been approved for public release.',
                        ],
                        'completed' => [
                            'heading' => 'Completed project profiles are not yet published.',
                            'description' => 'Selotemna has undertaken previous projects. Their names, locations, images and details will appear here as the information is approved for publication.',
                        ],
                    ]"
                />
            </div>
        </div>
    </section>

    @if ($featuredProperty)
        <x-site.conversion-cta
            heading="Interested in {{ $featuredProperty['name'] }}?"
            intro="Review the complete project information or request an inspection with your preferred date and contact details."
            primary-label="Request an Inspection"
            :primary-href="route('inspections.create', ['interest' => $featuredProperty['name']])"
            secondary-label="View Full Project Details"
            :secondary-href="route('omu-creek')"
        >
            <p class="mt-5 text-sm leading-6 text-white/70">Submitting an inspection request does not automatically confirm an appointment.</p>
        </x-site.conversion-cta>
    @endif
@endsection
