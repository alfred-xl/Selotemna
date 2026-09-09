@php
    $dialogToken = (string) \Illuminate\Support\Str::uuid();
@endphp

<dialog class="inspection-dialog m-auto max-h-[calc(100dvh-2rem)] w-[min(44rem,calc(100%-2rem))] overflow-hidden rounded-[1.5rem] bg-white p-0 text-ink-950 backdrop:bg-ink-950/75" data-inspection-dialog data-inspection-path="{{ route('inspections.create') }}" aria-labelledby="inspection-dialog-title">
    <div class="relative max-h-[calc(100dvh-2rem)] overflow-y-auto p-5 sm:p-7 md:p-9" data-inspection-dialog-panel>
        <button type="button" class="absolute right-4 top-4 inline-flex size-11 items-center justify-center rounded-full border border-ink-200 bg-white text-2xl leading-none text-ink-800 hover:border-brand-700 hover:text-brand-700" aria-label="Close inspection form" data-inspection-dialog-close>&times;</button>

        <div class="border-b border-ink-200 pb-6 pr-12">
            <span class="eyebrow">Omu Creek</span>
            <h2 id="inspection-dialog-title" class="text-2xl font-semibold leading-tight sm:text-3xl">Request an inspection</h2>
            <p class="mt-3 leading-7 text-ink-500">Choose a preferred date and tell us how to reach you. This request does not confirm an appointment.</p>
        </div>

        <form method="POST" action="{{ route('inspections.store') }}" class="mt-7" data-inspection-form data-submit-once data-async-form="inspection" data-step-form novalidate>
            @csrf
            <input type="hidden" name="submission_token" value="{{ $dialogToken }}" data-submission-token>
            <input type="hidden" name="interest" value="Omu Creek">
            <div class="absolute -left-[10000px] top-auto size-px overflow-hidden" aria-hidden="true">
                <label for="modal_website">Leave this field empty</label>
                <input id="modal_website" name="website" type="text" value="" tabindex="-1" autocomplete="off">
            </div>

            <div class="mb-7" data-step-progress hidden>
                <div class="flex items-center justify-between gap-4 text-sm font-bold text-ink-500"><span data-step-label>Step 1 of 3</span><span data-step-name>Inspection preference</span></div>
                <div class="mt-3 h-1.5 overflow-hidden rounded-full bg-brand-100"><span class="block h-full rounded-full bg-brand-700 transition-[width]" data-step-progress-bar></span></div>
            </div>
            <div class="form-message" role="alert" tabindex="-1" data-async-error-summary hidden></div>

            <fieldset data-form-step="1" data-step-title="Inspection preference">
                <legend class="font-display text-xl font-semibold">When would you like to visit?</legend>
                <div class="mt-5 grid gap-5 sm:grid-cols-2">
                    <div>
                        <label for="modal_preferred_date" class="form-label">Preferred date <span aria-hidden="true">*</span></label>
                        <input id="modal_preferred_date" name="preferred_date" type="date" min="{{ now()->toDateString() }}" class="form-control" required data-required-message="Please choose a preferred inspection date.">
                    </div>
                    <div>
                        <label for="modal_preferred_time" class="form-label">Preferred time of day</label>
                        <select id="modal_preferred_time" name="preferred_time" class="form-control">
                            <option value="">Select a time</option>
                            <option value="Morning">Morning</option>
                            <option value="Afternoon">Afternoon</option>
                            <option value="No preference">I’m flexible</option>
                        </select>
                    </div>
                </div>
            </fieldset>

            <fieldset data-form-step="2" data-step-title="Contact details">
                <legend class="font-display text-xl font-semibold">How can we reach you?</legend>
                <div class="mt-5 grid gap-5 sm:grid-cols-2">
                    <div class="sm:col-span-2">
                        <label for="modal_full_name" class="form-label">Full name <span aria-hidden="true">*</span></label>
                        <input id="modal_full_name" name="full_name" type="text" autocomplete="name" class="form-control" required data-required-message="Please enter your full name.">
                    </div>
                    <div>
                        <label for="modal_phone" class="form-label">Telephone number <span aria-hidden="true">*</span></label>
                        <input id="modal_phone" name="phone" type="tel" autocomplete="tel" inputmode="tel" minlength="7" class="form-control" required data-required-message="Please enter your telephone number." data-invalid-message="Please enter a valid telephone number.">
                    </div>
                    <div>
                        <label for="modal_contact_method" class="form-label">Preferred contact method <span aria-hidden="true">*</span></label>
                        <select id="modal_contact_method" name="contact_method" class="form-control" required data-contact-method data-required-message="Please choose how you would like us to contact you.">
                            <option value="">Select a contact method</option>
                            <option value="Telephone">Telephone</option>
                            <option value="WhatsApp">WhatsApp</option>
                            <option value="Email">Email</option>
                        </select>
                    </div>
                    <div data-conditional-contact-group="WhatsApp">
                        <label for="modal_whatsapp" class="form-label">WhatsApp number <span data-contact-requirement data-method="WhatsApp"></span></label>
                        <input id="modal_whatsapp" name="whatsapp" type="tel" autocomplete="tel" inputmode="tel" minlength="7" class="form-control" data-conditional-contact="WhatsApp" data-required-message="Please enter your WhatsApp number.">
                    </div>
                    <div data-conditional-contact-group="Email">
                        <label for="modal_email" class="form-label">Email address <span data-contact-requirement data-method="Email"></span></label>
                        <input id="modal_email" name="email" type="email" autocomplete="email" class="form-control" data-conditional-contact="Email" data-required-message="Please enter your email address." data-invalid-message="Please enter a valid email address.">
                    </div>
                </div>
            </fieldset>

            <fieldset data-form-step="3" data-step-title="Review and submit">
                <legend class="font-display text-xl font-semibold">Anything else we should know?</legend>
                <div class="mt-5">
                    <label for="modal_message" class="form-label">Message <span class="font-normal text-ink-500">(optional)</span></label>
                    <textarea id="modal_message" name="message" rows="4" maxlength="2000" class="form-control" placeholder="Share any timing or access details."></textarea>
                </div>
                <label class="mt-6 flex items-start gap-3 leading-7 text-ink-800" for="modal_consent">
                    <input id="modal_consent" name="consent" type="checkbox" value="1" class="mt-1 size-5 shrink-0 accent-brand-700" required data-required-message="Please acknowledge that this is an inspection request, not a confirmed appointment.">
                    <span>I agree that Selotemna may use these details to respond, and understand that this request does not confirm an appointment. <span aria-hidden="true">*</span></span>
                </label>
                <button type="submit" class="mt-7 inline-flex min-h-12 w-full items-center justify-center rounded-xl bg-brand-700 px-5 py-3 text-sm font-semibold text-white hover:bg-brand-800 disabled:cursor-wait disabled:opacity-70 sm:w-auto sm:min-w-56" data-submit-button data-pending-label="Saving request…">
                    <span aria-live="polite" data-submit-label>Submit Inspection Request</span>
                </button>
                <span class="sr-only" aria-live="polite" data-submit-status></span>
            </fieldset>
        </form>

        <div class="rounded-[1.25rem] border border-brand-100 bg-brand-50 p-6 sm:p-8" role="status" tabindex="-1" data-async-success="inspection" hidden>
            <span class="eyebrow">Request received</span>
            <h2 class="text-2xl font-semibold sm:text-3xl">Thanks! We got your inspection request.</h2>
            <p class="mt-4 leading-7 text-ink-500" data-success-message>We will reply within 24 hours to confirm the next step.</p>
            <dl class="mt-6 border-y border-brand-100">
                <div class="summary-row"><dt>Reference</dt><dd class="font-mono" data-success-reference></dd></div>
                <div class="summary-row"><dt>Preferred date</dt><dd data-success-date></dd></div>
                <div class="summary-row"><dt>Time of day</dt><dd data-success-time></dd></div>
            </dl>
            <p class="mt-5 text-sm leading-6 text-ink-500">Your request is saved, but it is not yet a confirmed appointment.</p>
            <div class="mt-7 flex flex-col gap-3 sm:flex-row">
                <button type="button" class="inline-flex min-h-12 items-center justify-center rounded-xl bg-brand-700 px-5 py-3 text-sm font-semibold text-white hover:bg-brand-800" data-inspection-dialog-close>Done</button>
                <button type="button" class="inline-flex min-h-12 items-center justify-center rounded-xl border border-ink-200 bg-white px-5 py-3 text-sm font-semibold" data-form-reset>Submit another request</button>
            </div>
        </div>
    </div>
</dialog>
