@php
    $property = app(\App\Support\SelotemnaContent::class)->project('omu-creek');
@endphp

@if ($property)
    <dialog class="inspection-dialog m-auto max-h-[calc(100dvh-2rem)] w-[min(48rem,calc(100%-2rem))] overflow-hidden rounded-[1.5rem] bg-white p-0 text-ink-950 backdrop:bg-ink-950/75" data-project-enquiry-dialog data-project-enquiry-path="{{ route('project-enquiries.create') }}" aria-labelledby="project-enquiry-dialog-title">
        <div class="relative max-h-[calc(100dvh-2rem)] overflow-y-auto p-5 sm:p-7 md:p-9">
            <button type="button" class="absolute right-4 top-4 inline-flex size-11 items-center justify-center rounded-full border border-ink-200 bg-white text-2xl leading-none text-ink-800 hover:border-brand-700 hover:text-brand-700" aria-label="Close plot enquiry form" data-project-enquiry-dialog-close>&times;</button>
            <div class="border-b border-ink-200 pb-6 pr-12">
                <span class="eyebrow">Omu Creek</span>
                <h2 id="project-enquiry-dialog-title" class="text-2xl font-semibold leading-tight sm:text-3xl">Select a plot size</h2>
                <p class="mt-3 leading-7 text-ink-500">Tell us what you are considering so the team can give you the relevant next step.</p>
            </div>
            <x-site.project-enquiry-form :property="$property" :submission-token="(string) \Illuminate\Support\Str::uuid()" modal />
        </div>
    </dialog>
@endif
