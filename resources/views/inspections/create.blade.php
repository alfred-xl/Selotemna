@extends('layouts.site')

@section('content')
    <x-site.page-hero variant="overlay" heading="Request an Inspection" intro="Choose a preferred date for Omu Creek and our team will follow up to confirm availability." :background-image="$inspectionMedia['url'] ?? null" image-position="center 45%" :show-breadcrumbs="false" :show-eyebrow="false" alignment="center" overlay-size="compact" />

    @if ($inspectionMedia)
        <div class="border-b border-ink-200 bg-white" aria-label="Hero image credit">
            <p class="site-container py-2 text-right text-xs leading-5 text-ink-500">
                Editorial image. <a href="{{ $inspectionMedia['sourceUrl'] }}" class="font-semibold text-brand-700 underline decoration-transparent underline-offset-2 hover:decoration-current" target="_blank" rel="noopener noreferrer">{{ $inspectionMedia['credit'] }}</a>
            </p>
        </div>
    @endif

    <section class="section-space bg-white">
        <div class="site-container grid min-w-0 items-start gap-12 lg:grid-cols-[minmax(0,0.72fr)_minmax(0,1.28fr)] lg:gap-16 xl:gap-20">
            <div class="order-1 min-w-0 max-w-3xl lg:order-2 lg:ml-auto lg:w-full">
                @if ($receipt)
                    <div class="rounded-[1.5rem] border border-brand-100 bg-brand-50 p-6 md:p-9" role="status" data-inspection-receipt data-reveal>
                        <span class="eyebrow">Request received</span>
                        <h2 class="text-3xl font-semibold md:text-4xl">Thanks! We got your inspection request.</h2>
                        <p class="mt-5 max-w-2xl leading-7 text-ink-500">We will reply within 24 hours to confirm the next step. This receipt does not confirm an inspection appointment.</p>
                        <p class="mt-3 font-semibold text-ink-950">Keep this reference for follow-up.</p>

                        <dl class="mt-8 border-y border-brand-100 px-1 md:px-2">
                            <div class="summary-row"><dt>Reference</dt><dd class="select-all font-mono text-lg tracking-wide text-brand-800" tabindex="0">{{ $receipt['reference'] }}</dd></div>
                            <div class="summary-row"><dt>Opportunity</dt><dd>{{ $receipt['project'] }}</dd></div>
                            <div class="summary-row"><dt>Preferred date</dt><dd>{{ $receipt['preferred_date'] }}</dd></div>
                            <div class="summary-row"><dt>Preferred period</dt><dd>{{ $receipt['preferred_time'] }}</dd></div>
                        </dl>

                        <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                            <x-site.button href="{{ route('omu-creek') }}">Review Omu Creek</x-site.button>
                            <x-site.button href="{{ route('inspections.create') }}" variant="secondary">Submit Another Request</x-site.button>
                        </div>
                    </div>
                @else
                    <form method="POST" action="{{ route('inspections.store') }}" class="rounded-[1.5rem] border border-ink-200 bg-ink-50 p-6 md:p-8" data-inspection-form data-submit-once data-async-form="inspection" data-step-form data-reveal novalidate>
                        @csrf
                        <input id="submission_token" type="hidden" name="submission_token" value="{{ $submissionToken }}">
                        <input id="interest" type="hidden" name="interest" value="Omu Creek">

                        <div class="absolute -left-[10000px] top-auto size-px overflow-hidden" aria-hidden="true">
                            <label for="website">Leave this field empty</label>
                            <input id="website" name="website" type="text" value="" tabindex="-1" autocomplete="off">
                        </div>

                        <div class="flex flex-col gap-4 border-b border-ink-200 pb-7 sm:flex-row sm:items-center sm:justify-between">
                            <h2 class="text-2xl font-semibold md:text-3xl">Inspection for Omu Creek</h2>
                            <a href="{{ route('omu-creek') }}" class="text-link w-fit shrink-0">
                                Review project details
                                <span aria-hidden="true">→</span>
                            </a>
                        </div>

                        <div class="mt-7" data-step-progress hidden>
                            <div class="flex items-center justify-between gap-4 text-sm font-bold text-ink-500"><span data-step-label>Step 1 of 3</span><span data-step-name>Inspection preference</span></div>
                            <div class="mt-3 h-1.5 overflow-hidden rounded-full bg-brand-100"><span class="block h-full rounded-full bg-brand-700 transition-[width]" data-step-progress-bar></span></div>
                        </div>
                        <div class="form-message mt-7" role="alert" tabindex="-1" data-async-error-summary hidden></div>

                        @if ($errors->any())
                            <div id="inspection_error_summary" class="mt-7 rounded-xl border border-brand-500 bg-white px-5 py-4" role="alert" tabindex="-1" data-form-error-summary>
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

                        <fieldset class="mt-8" data-form-step="2" data-step-title="Contact details">
                            <legend class="w-full border-b border-ink-200 pb-3 font-display text-xl font-semibold text-ink-950">Contact details</legend>
                            <div class="mt-6 grid gap-6 md:grid-cols-2">
                                <div class="md:col-span-2">
                                    <label for="full_name" class="form-label">Full name <span aria-hidden="true">*</span></label>
                                    <input id="full_name" name="full_name" type="text" value="{{ old('full_name') }}" autocomplete="name" class="form-control" required data-required-message="Please enter your full name." @error('full_name') aria-describedby="full_name_error" aria-invalid="true" @enderror>
                                    @error('full_name') <p id="full_name_error" class="form-error">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="phone" class="form-label">Telephone number <span aria-hidden="true">*</span></label>
                                    <input id="phone" name="phone" type="tel" value="{{ old('phone') }}" autocomplete="tel" class="form-control" inputmode="tel" minlength="7" required data-required-message="Please enter your telephone number." data-invalid-message="Please enter a valid telephone number." @error('phone') aria-describedby="phone_error" aria-invalid="true" @enderror>
                                    @error('phone') <p id="phone_error" class="form-error">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="contact_method" class="form-label">Preferred contact method <span aria-hidden="true">*</span></label>
                                    <select id="contact_method" name="contact_method" class="form-control" required data-contact-method data-required-message="Please choose how you would like us to contact you." @error('contact_method') aria-describedby="contact_method_error" aria-invalid="true" @enderror>
                                        <option value="">Select a contact method</option>
                                        @foreach (['Telephone', 'WhatsApp', 'Email'] as $method)
                                            <option value="{{ $method }}" @selected(old('contact_method') === $method)>{{ $method }}</option>
                                        @endforeach
                                    </select>
                                    @error('contact_method') <p id="contact_method_error" class="form-error">{{ $message }}</p> @enderror
                                </div>
                                <div data-conditional-contact-group="WhatsApp">
                                    <label for="whatsapp" class="form-label">WhatsApp number <span id="whatsapp_requirement" class="font-normal text-ink-500" data-contact-requirement data-method="WhatsApp">(required when WhatsApp is selected)</span></label>
                                    <input id="whatsapp" name="whatsapp" type="tel" value="{{ old('whatsapp') }}" autocomplete="tel" class="form-control" inputmode="tel" minlength="7" aria-describedby="whatsapp_requirement @error('whatsapp') whatsapp_error @enderror" data-conditional-contact="WhatsApp" data-required-message="Please enter your WhatsApp number." data-invalid-message="Please enter a valid WhatsApp number." @error('whatsapp') aria-invalid="true" @enderror>
                                    @error('whatsapp') <p id="whatsapp_error" class="form-error">{{ $message }}</p> @enderror
                                </div>
                                <div data-conditional-contact-group="Email">
                                    <label for="email" class="form-label">Email address <span id="email_requirement" class="font-normal text-ink-500" data-contact-requirement data-method="Email">(required when Email is selected)</span></label>
                                    <input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" class="form-control" aria-describedby="email_requirement @error('email') email_error @enderror" data-conditional-contact="Email" data-required-message="Please enter your email address." data-invalid-message="Please enter a valid email address." @error('email') aria-invalid="true" @enderror>
                                    @error('email') <p id="email_error" class="form-error">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </fieldset>

                        <fieldset class="mt-10" data-form-step="1" data-step-title="Inspection preference">
                            <legend class="w-full border-b border-ink-200 pb-3 font-display text-xl font-semibold text-ink-950">Inspection preference</legend>
                            <dl class="grid gap-1 border-b border-ink-200 py-5 sm:grid-cols-[9rem_1fr] sm:gap-5">
                                <dt class="text-sm font-bold text-ink-500">Opportunity</dt>
                                <dd class="font-semibold text-ink-950">Omu Creek</dd>
                            </dl>
                            <div class="mt-6 grid gap-6 md:grid-cols-2">
                                <div>
                                    <label for="preferred_date" class="form-label">Preferred date <span aria-hidden="true">*</span></label>
                                    <input id="preferred_date" name="preferred_date" type="date" min="{{ now()->toDateString() }}" value="{{ old('preferred_date') }}" class="form-control" required data-required-message="Please choose a preferred inspection date." @error('preferred_date') aria-describedby="preferred_date_error" aria-invalid="true" @enderror>
                                    @error('preferred_date') <p id="preferred_date_error" class="form-error">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="preferred_time" class="form-label">Preferred period <span class="font-normal text-ink-500">(optional)</span></label>
                                    <select id="preferred_time" name="preferred_time" class="form-control" @error('preferred_time') aria-describedby="preferred_time_error" aria-invalid="true" @enderror>
                                        <option value="">Select a period</option>
                                        <option value="Morning" @selected(old('preferred_time') === 'Morning')>Morning</option>
                                        <option value="Afternoon" @selected(old('preferred_time') === 'Afternoon')>Afternoon</option>
                                        <option value="No preference" @selected(old('preferred_time') === 'No preference')>I’m flexible</option>
                                    </select>
                                    @error('preferred_time') <p id="preferred_time_error" class="form-error">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </fieldset>

                        <fieldset class="mt-10" data-form-step="3" data-step-title="Review and submit">
                            <legend class="w-full border-b border-ink-200 pb-3 font-display text-xl font-semibold text-ink-950">Additional information</legend>
                            <div class="mt-6">
                                <label for="message" class="form-label">Message <span class="font-normal text-ink-500">(optional)</span></label>
                                <textarea id="message" name="message" rows="5" class="form-control" maxlength="2000" aria-describedby="message_help @error('message') message_error @enderror" @error('message') aria-invalid="true" @enderror>{{ old('message') }}</textarea>
                                <p id="message_help" class="mt-2 text-sm leading-6 text-ink-500">Share any timing or access detail that will help the team respond. Maximum 2,000 characters.</p>
                                @error('message') <p id="message_error" class="form-error">{{ $message }}</p> @enderror
                            </div>
                        </fieldset>

                        <fieldset class="mt-10" data-form-step="3" data-step-title="Review and submit">
                            <legend class="w-full border-b border-ink-200 pb-3 font-display text-xl font-semibold text-ink-950">Consent and submission</legend>
                            <div class="mt-6">
                                <label class="flex items-start gap-3 leading-7 text-ink-800" for="consent">
                                    <input id="consent" name="consent" type="checkbox" value="1" class="mt-1 size-5 shrink-0 accent-brand-700 @error('consent') outline-2 outline-offset-2 outline-brand-700 @enderror" @checked(old('consent')) required data-required-message="Please acknowledge that this is an inspection request, not a confirmed appointment." @error('consent') aria-describedby="consent_error" aria-invalid="true" @enderror>
                                    <span>I understand that my details will be used to review and respond to this request, and that submitting it does not automatically confirm an inspection appointment. <span aria-hidden="true">*</span></span>
                                </label>
                                @error('consent') <p id="consent_error" class="form-error">{{ $message }}</p> @enderror
                            </div>

                            <button type="submit" class="mt-8 inline-flex min-h-12 w-full items-center justify-center rounded-xl bg-brand-700 px-5 py-3 text-sm font-semibold text-white transition hover:bg-brand-800 disabled:cursor-wait disabled:opacity-70 sm:w-auto sm:min-w-56" data-submit-button data-pending-label="Saving request…">
                                <span aria-live="polite" aria-atomic="true" data-submit-label>Submit Inspection Request</span>
                            </button>
                            <span class="sr-only" aria-live="polite" data-submit-status></span>
                        </fieldset>
                    </form>
                    <div class="rounded-[1.5rem] border border-brand-100 bg-brand-50 p-6 md:p-9" role="status" tabindex="-1" data-async-success="inspection" hidden>
                        <span class="eyebrow">Request received</span>
                        <h2 class="text-3xl font-semibold md:text-4xl">Thanks! We got your inspection request.</h2>
                        <p class="mt-5 max-w-2xl leading-7 text-ink-500" data-success-message>We will reply within 24 hours to confirm the next step.</p>
                        <dl class="mt-8 border-y border-brand-100">
                            <div class="summary-row"><dt>Reference</dt><dd class="font-mono text-brand-800" data-success-reference></dd></div>
                            <div class="summary-row"><dt>Preferred date</dt><dd data-success-date></dd></div>
                            <div class="summary-row"><dt>Time of day</dt><dd data-success-time></dd></div>
                        </dl>
                        <p class="mt-5 text-sm leading-6 text-ink-500">Your request is saved, but it is not yet a confirmed appointment.</p>
                        <button type="button" class="mt-8 inline-flex min-h-12 items-center justify-center rounded-xl border border-ink-200 bg-white px-5 py-3 text-sm font-semibold hover:border-brand-700" data-form-reset>Submit another request</button>
                    </div>
                @endif
            </div>

            <aside class="order-2 min-w-0 lg:order-1 lg:sticky lg:top-28" data-reveal>
                <div class="max-w-xl">
                    <h2 class="text-[clamp(1.75rem,3vw,2.5rem)] font-semibold leading-tight">What happens next</h2>
                    <p class="mt-5 leading-7 text-ink-500">Submit your preferred date and contact details. A Selotemna representative will review availability and contact you about the next step.</p>

                    <ol class="mt-8 space-y-6 text-ink-500" data-reveal-group>
                        <li class="numbered-step" data-reveal><span>1</span><p><strong class="text-ink-950">Share your details</strong> — Complete the request with your preferred date and the best way to reach you.</p></li>
                        <li class="numbered-step" data-reveal><span>2</span><p><strong class="text-ink-950">Receive your reference</strong> — A reference will be provided after the request has been saved.</p></li>
                        <li class="numbered-step" data-reveal><span>3</span><p><strong class="text-ink-950">Wait for confirmation</strong> — A representative will review availability and contact you to agree on the next step.</p></li>
                    </ol>

                    <div class="mt-10 border-t border-ink-200 pt-7">
                        <h3 class="font-display text-lg font-semibold text-ink-950">Important notes</h3>
                        <ul class="mt-5 space-y-3">
                            <li class="feature-line">The request is for Omu Creek.</li>
                            <li class="feature-line">The selected date and period are preferences.</li>
                            <li class="feature-line">No payment is collected through this form.</li>
                            <li class="feature-line">Submitting the form does not confirm an inspection appointment.</li>
                        </ul>
                    </div>

                    @if ($contact['phone_url'] || $contact['whatsapp_url'] || $contact['email_url'])
                        <div class="mt-10 border-t border-ink-200 pt-7">
                            <h3 class="mb-5 font-display text-lg font-semibold text-ink-950">Verified alternative contact channels</h3>
                            <x-site.contact-channels :contact="$contact" :show-details="false" />
                        </div>
                    @endif
                </div>
            </aside>
        </div>
    </section>
@endsection
