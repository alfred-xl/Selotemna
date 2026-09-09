@extends('layouts.site')

@section('content')
    <x-site.page-hero variant="overlay" heading="Omu Creek Plot Enquiry" intro="Select a plot size and tell us how to contact you about the opportunity." :background-image="$property['hero_image'] ?? null" image-position="50% 50%" :show-breadcrumbs="false" :show-eyebrow="false" alignment="center" overlay-size="compact" />

    <section class="section-space bg-white">
        <div class="site-container max-w-3xl">
            <div class="mb-8">
                <span class="eyebrow">Omu Creek</span>
                <h2 class="text-[clamp(2rem,5vw,3rem)] font-semibold leading-tight">Tell us which plot you are considering.</h2>
                <p class="mt-4 max-w-2xl leading-7 text-ink-500">This is an enquiry, not a reservation. The team will confirm availability, pricing and the appropriate next step.</p>
            </div>
            <x-site.project-enquiry-form :property="$property" :submission-token="$submissionToken" :receipt="$receipt" />
        </div>
    </section>
@endsection
