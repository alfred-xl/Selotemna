@extends('layouts.site')

@section('content')
    @php
        $phones = $contact['phones'] ?? [];
        $emails = $contact['emails'] ?? [];
    @endphp

    <x-site.page-hero variant="overlay" heading="Contact Selotemna" intro="Speak with our team about Omu Creek, real estate development, or an engineering and construction requirement." :background-image="$contactMedia['url'] ?? null" image-position="center 48%" :show-breadcrumbs="false" :show-eyebrow="false" alignment="center" overlay-size="compact" />

    @if ($contactMedia)
        <div class="border-b border-ink-200 bg-white" aria-label="Hero image credit">
            <p class="site-container py-2 text-right text-xs leading-5 text-ink-500">
                Editorial image. <a href="{{ $contactMedia['sourceUrl'] }}" class="font-semibold text-brand-700 underline decoration-transparent underline-offset-2 hover:decoration-current" target="_blank" rel="noopener noreferrer">{{ $contactMedia['credit'] }}</a>
            </p>
        </div>
    @endif

    <section class="section-space bg-white">
        <div class="site-container grid min-w-0 items-start gap-12 lg:grid-cols-[minmax(0,0.72fr)_minmax(0,1.28fr)] lg:gap-16 xl:gap-20">
            <aside class="min-w-0 lg:sticky lg:top-28" data-reveal>
                <div class="max-w-xl">
                    <h2 class="text-[clamp(1.75rem,3vw,2.5rem)] font-semibold leading-tight">Speak with our team</h2>
                    <p class="mt-5 leading-7 text-ink-500">Call or email Selotemna about a property opportunity, an inspection or an engineering and construction requirement.</p>

                    <div class="mt-8 divide-y divide-ink-200 border-y border-ink-200">
                        @if ($phones !== [])
                            <section class="py-6" aria-labelledby="contact-telephone-heading">
                                <h3 id="contact-telephone-heading" class="text-sm font-bold uppercase tracking-[0.12em] text-ink-500">Telephone</h3>
                                <ul class="mt-4 space-y-2">
                                    @foreach ($phones as $phone)
                                        <li><a href="{{ $phone['url'] }}" class="inline-flex min-h-11 items-center font-display text-xl font-semibold text-ink-950 hover:text-brand-700">{{ $phone['display'] }}</a></li>
                                    @endforeach
                                </ul>
                            </section>
                        @endif

                        @if ($emails !== [])
                            <section class="py-6" aria-labelledby="contact-email-heading">
                                <h3 id="contact-email-heading" class="text-sm font-bold uppercase tracking-[0.12em] text-ink-500">Email</h3>
                                <ul class="mt-4 space-y-2">
                                    @foreach ($emails as $email)
                                        <li><a href="{{ $email['url'] }}" class="inline-flex min-h-11 max-w-full items-center break-all font-semibold text-brand-700 underline decoration-transparent underline-offset-4 hover:text-brand-800 hover:decoration-current">{{ $email['address'] }}</a></li>
                                    @endforeach
                                </ul>
                            </section>
                        @endif

                        @if ($contact['address'])
                            <section class="py-6" aria-labelledby="contact-office-heading">
                                <h3 id="contact-office-heading" class="text-sm font-bold uppercase tracking-[0.12em] text-ink-500">Office</h3>
                                <p class="mt-4 leading-7 text-ink-800">{{ $contact['address'] }}</p>
                            </section>
                        @endif

                        @if ($contact['business_hours'])
                            <section class="py-6" aria-labelledby="contact-hours-heading">
                                <h3 id="contact-hours-heading" class="text-sm font-bold uppercase tracking-[0.12em] text-ink-500">Business hours</h3>
                                <p class="mt-4 leading-7 text-ink-800">{{ $contact['business_hours'] }}</p>
                            </section>
                        @endif
                    </div>
                </div>
            </aside>

            <div id="contact-enquiry-form" class="min-w-0 scroll-mt-28 lg:ml-auto lg:w-full lg:max-w-3xl">
                @if ($receipt)
                    <div class="rounded-[1.5rem] border border-brand-100 bg-brand-50 p-6 md:p-9" role="status" data-contact-enquiry-receipt data-reveal>
                        <span class="eyebrow">Enquiry received</span>
                        <h2 class="text-3xl font-semibold md:text-4xl">Your enquiry has been saved.</h2>
                        <p class="mt-5 max-w-2xl leading-7 text-ink-500">A Selotemna representative will review the information provided and contact you through your preferred method.</p>
                        <p class="mt-3 font-semibold text-ink-950">Keep this reference for follow-up.</p>

                        <dl class="mt-8 border-y border-brand-100 px-1 md:px-2">
                            <div class="summary-row"><dt>Enquiry reference</dt><dd class="select-all font-mono text-lg tracking-wide text-brand-800" tabindex="0">{{ $receipt['reference'] }}</dd></div>
                            <div class="summary-row"><dt>Enquiry type</dt><dd>{{ $receipt['enquiry_type'] }}</dd></div>
                            <div class="summary-row"><dt>Preferred contact method</dt><dd>{{ $receipt['contact_method'] }}</dd></div>
                        </dl>

                        <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                            <x-site.button href="{{ route('home') }}">Return to Home</x-site.button>
                            <x-site.button href="{{ route('contact') }}" variant="secondary">Send Another Enquiry</x-site.button>
                        </div>
                    </div>
                @else
                    <form method="POST" action="{{ route('contact.store') }}" class="rounded-[1.5rem] border border-ink-200 bg-ink-50 p-6 md:p-8" data-contact-enquiry-form data-submit-once data-reveal novalidate>
                        @csrf
                        <input id="submission_token" type="hidden" name="submission_token" value="{{ $submissionToken }}">

                        <div class="absolute -left-[10000px] top-auto size-px overflow-hidden" aria-hidden="true">
                            <label for="website">Leave this field empty</label>
                            <input id="website" name="website" type="text" value="" tabindex="-1" autocomplete="off">
                        </div>

                        <div class="border-b border-ink-200 pb-7">
                            <h2 class="text-2xl font-semibold leading-tight md:text-3xl">Tell us what you would like to discuss.</h2>
                            <p class="mt-4 max-w-2xl leading-7 text-ink-500">Share enough information for our team to understand your enquiry and determine the appropriate next step.</p>
                        </div>

                        @if ($errors->any())
                            <div id="contact_error_summary" class="mt-7 rounded-xl border border-brand-500 bg-white px-5 py-4" role="alert" tabindex="-1" data-form-error-summary>
                                <h3 class="text-base font-semibold">Check the highlighted fields.</h3>
                                <ul class="mt-3 list-disc space-y-1 pl-5 text-sm leading-6 text-brand-800">
                                    @foreach ($errors->getMessages() as $field => $messages)
                                        @foreach ($messages as $message)
                                            <li><a href="#{{ $field }}" class="underline underline-offset-2">{{ $message }}</a></li>
                                        @endforeach
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <fieldset class="mt-8">
                            <legend class="w-full border-b border-ink-200 pb-3 font-display text-xl font-semibold text-ink-950">Contact details</legend>
                            <div class="mt-6 grid gap-6 md:grid-cols-2">
                                <div class="md:col-span-2">
                                    <label for="full_name" class="form-label">Full name <span aria-hidden="true">*</span></label>
                                    <input id="full_name" name="full_name" type="text" value="{{ old('full_name') }}" autocomplete="name" class="form-control" required @error('full_name') aria-describedby="full_name_error" aria-invalid="true" @enderror>
                                    @error('full_name') <p id="full_name_error" class="form-error">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="phone" class="form-label">Telephone number <span aria-hidden="true">*</span></label>
                                    <input id="phone" name="phone" type="tel" value="{{ old('phone') }}" autocomplete="tel" class="form-control" inputmode="tel" required @error('phone') aria-describedby="phone_error" aria-invalid="true" @enderror>
                                    @error('phone') <p id="phone_error" class="form-error">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="contact_method" class="form-label">Preferred contact method <span aria-hidden="true">*</span></label>
                                    <select id="contact_method" name="contact_method" class="form-control" required data-contact-method @error('contact_method') aria-describedby="contact_method_error" aria-invalid="true" @enderror>
                                        <option value="">Select a contact method</option>
                                        @foreach (['Telephone', 'Email', 'WhatsApp'] as $method)
                                            <option value="{{ $method }}" @selected(old('contact_method') === $method)>{{ $method }}</option>
                                        @endforeach
                                    </select>
                                    @error('contact_method') <p id="contact_method_error" class="form-error">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="email" class="form-label">Email address <span id="email_requirement" class="font-normal text-ink-500" data-contact-requirement data-method="Email">(required when Email is selected)</span></label>
                                    <input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" class="form-control" aria-describedby="email_requirement @error('email') email_error @enderror" data-conditional-contact="Email" @error('email') aria-invalid="true" @enderror>
                                    @error('email') <p id="email_error" class="form-error">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="whatsapp" class="form-label">WhatsApp number <span id="whatsapp_requirement" class="font-normal text-ink-500" data-contact-requirement data-method="WhatsApp">(required when WhatsApp is selected)</span></label>
                                    <input id="whatsapp" name="whatsapp" type="tel" value="{{ old('whatsapp') }}" autocomplete="tel" class="form-control" inputmode="tel" aria-describedby="whatsapp_requirement @error('whatsapp') whatsapp_error @enderror" data-conditional-contact="WhatsApp" @error('whatsapp') aria-invalid="true" @enderror>
                                    @error('whatsapp') <p id="whatsapp_error" class="form-error">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </fieldset>

                        <fieldset class="mt-10">
                            <legend class="w-full border-b border-ink-200 pb-3 font-display text-xl font-semibold text-ink-950">Enquiry details</legend>
                            <div class="mt-6">
                                <label for="enquiry_type" class="form-label">Enquiry type <span aria-hidden="true">*</span></label>
                                <select id="enquiry_type" name="enquiry_type" class="form-control" required data-enquiry-type @error('enquiry_type') aria-describedby="enquiry_type_error" aria-invalid="true" @enderror>
                                    <option value="">Select an enquiry type</option>
                                    @foreach (['Omu Creek information', 'Real Estate Development', 'Engineering & Construction', 'General enquiry'] as $type)
                                        <option value="{{ $type }}" @selected(old('enquiry_type') === $type)>{{ $type }}</option>
                                    @endforeach
                                </select>
                                @error('enquiry_type') <p id="enquiry_type_error" class="form-error">{{ $message }}</p> @enderror
                            </div>
                        </fieldset>

                        <fieldset class="mt-10" data-engineering-fields>
                            <legend class="w-full border-b border-ink-200 pb-3 font-display text-xl font-semibold text-ink-950">Engineering &amp; Construction details <span class="font-body text-sm font-normal text-ink-500">(optional)</span></legend>
                            <p class="mt-4 leading-7 text-ink-500">Add any information already available. These fields help the team understand the requirement.</p>
                            <div class="mt-6 grid gap-6 md:grid-cols-2">
                                <div>
                                    <label for="project_type" class="form-label">Project type</label>
                                    <input id="project_type" name="project_type" type="text" value="{{ old('project_type') }}" class="form-control" maxlength="160" data-engineering-input @error('project_type') aria-describedby="project_type_error" aria-invalid="true" @enderror>
                                    @error('project_type') <p id="project_type_error" class="form-error">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="proposed_location" class="form-label">Proposed location</label>
                                    <input id="proposed_location" name="proposed_location" type="text" value="{{ old('proposed_location') }}" class="form-control" maxlength="160" data-engineering-input @error('proposed_location') aria-describedby="proposed_location_error" aria-invalid="true" @enderror>
                                    @error('proposed_location') <p id="proposed_location_error" class="form-error">{{ $message }}</p> @enderror
                                </div>
                                <div class="md:col-span-2">
                                    <label for="project_stage" class="form-label">Current project stage</label>
                                    <input id="project_stage" name="project_stage" type="text" value="{{ old('project_stage') }}" class="form-control" maxlength="160" data-engineering-input @error('project_stage') aria-describedby="project_stage_error" aria-invalid="true" @enderror>
                                    @error('project_stage') <p id="project_stage_error" class="form-error">{{ $message }}</p> @enderror
                                </div>
                                <div class="md:col-span-2">
                                    <label for="scope_summary" class="form-label">Scope or requirement summary</label>
                                    <textarea id="scope_summary" name="scope_summary" rows="4" class="form-control" maxlength="2000" data-engineering-input @error('scope_summary') aria-describedby="scope_summary_error" aria-invalid="true" @enderror>{{ old('scope_summary') }}</textarea>
                                    @error('scope_summary') <p id="scope_summary_error" class="form-error">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </fieldset>

                        <fieldset class="mt-10">
                            <legend class="w-full border-b border-ink-200 pb-3 font-display text-xl font-semibold text-ink-950">Your message</legend>
                            <div class="mt-6">
                                <label for="message" class="form-label">Message <span aria-hidden="true">*</span></label>
                                <textarea id="message" name="message" rows="6" class="form-control" maxlength="4000" aria-describedby="message_help @error('message') message_error @enderror" required @error('message') aria-invalid="true" @enderror>{{ old('message') }}</textarea>
                                <p id="message_help" class="mt-2 text-sm leading-6 text-ink-500">Share the key information needed to understand your enquiry. Maximum 4,000 characters.</p>
                                @error('message') <p id="message_error" class="form-error">{{ $message }}</p> @enderror
                            </div>
                        </fieldset>

                        <fieldset class="mt-10">
                            <legend class="w-full border-b border-ink-200 pb-3 font-display text-xl font-semibold text-ink-950">Consent and submission</legend>
                            <div class="mt-6">
                                <label class="flex items-start gap-3 leading-7 text-ink-800" for="consent">
                                    <input id="consent" name="consent" type="checkbox" value="1" class="mt-1 size-5 shrink-0 accent-brand-700 @error('consent') outline-2 outline-offset-2 outline-brand-700 @enderror" @checked(old('consent')) required @error('consent') aria-describedby="consent_error" aria-invalid="true" @enderror>
                                    <span>I agree that Selotemna may use the details provided to review and respond to this enquiry. <span aria-hidden="true">*</span></span>
                                </label>
                                @error('consent') <p id="consent_error" class="form-error">{{ $message }}</p> @enderror
                            </div>

                            <button type="submit" class="mt-8 inline-flex min-h-12 w-full items-center justify-center rounded-xl bg-brand-700 px-5 py-3 text-sm font-semibold text-white transition hover:bg-brand-800 disabled:cursor-wait disabled:opacity-70 sm:w-auto sm:min-w-48" data-submit-button data-pending-label="Sending enquiry…">
                                <span aria-live="polite" aria-atomic="true" data-submit-label>Send Enquiry</span>
                            </button>
                            <span class="sr-only" aria-live="polite" data-submit-status></span>
                        </fieldset>
                    </form>
                @endif
            </div>
        </div>
    </section>

    <section class="section-space border-y border-ink-200 bg-ink-50">
        <div class="site-container">
            <div class="max-w-3xl" data-reveal>
                <span class="eyebrow">Choose a pathway</span>
                <h2 class="text-[clamp(1.8rem,4vw,3rem)] font-semibold leading-tight">Continue with the route that matches your next step.</h2>
            </div>

            <div class="mt-10 grid border-y border-ink-200 lg:grid-cols-2 lg:divide-x lg:divide-ink-200" data-reveal-group>
                <article class="py-8 lg:pr-10" data-reveal>
                    <h3 class="text-2xl font-semibold">Property or project enquiry</h3>
                    <p class="mt-4 max-w-xl leading-7 text-ink-500">Use the general enquiry form to discuss Omu Creek information, Real Estate Development, or an Engineering &amp; Construction requirement.</p>
                    <a href="#contact-enquiry-form" class="text-link mt-5">Start an enquiry <span aria-hidden="true">→</span></a>
                </article>
                <article class="border-t border-ink-200 py-8 lg:border-t-0 lg:pl-10" data-reveal>
                    <h3 class="text-2xl font-semibold">Omu Creek inspection</h3>
                    <p class="mt-4 max-w-xl leading-7 text-ink-500">Choose a preferred date through the dedicated inspection form. Submission remains a request until availability is confirmed.</p>
                    <a href="{{ route('inspections.create') }}" class="text-link mt-5">Request an Inspection <span aria-hidden="true">→</span></a>
                </article>
            </div>
        </div>
    </section>

    <section class="section-space bg-white">
        <div class="site-container grid gap-12 lg:grid-cols-2 lg:gap-20">
            <div data-reveal>
                <span class="eyebrow">Before you send an enquiry</span>
                <h2 class="text-[clamp(1.8rem,4vw,3rem)] font-semibold leading-tight">Prepare the details that move the conversation forward.</h2>
                <p class="mt-5 max-w-xl leading-7 text-ink-500">A focused first message helps Selotemna understand the requirement without asking you to share unnecessary personal or financial information.</p>
            </div>
            <div class="grid gap-8 sm:grid-cols-2" data-reveal-group>
                <div class="plain-panel" data-reveal>
                    <h3>Property enquiry</h3>
                    <ul class="mt-5 space-y-3">
                        <li class="feature-line">The opportunity or development you want to discuss</li>
                        <li class="feature-line">The information you need before taking a next step</li>
                        <li class="feature-line">The best way to contact you</li>
                    </ul>
                </div>
                <div class="plain-panel" data-reveal>
                    <h3>Engineering or construction enquiry</h3>
                    <ul class="mt-5 space-y-3">
                        <li class="feature-line">Project type and proposed location</li>
                        <li class="feature-line">Current project stage</li>
                        <li class="feature-line">Available scope or requirement summary</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <x-site.conversion-cta heading="Interested in visiting Omu Creek?" intro="Review the published project information or submit a preferred inspection date for Selotemna to confirm." primary-label="Request an Inspection" :primary-href="route('inspections.create')" secondary-label="View Omu Creek" :secondary-href="route('omu-creek')" />
@endsection
