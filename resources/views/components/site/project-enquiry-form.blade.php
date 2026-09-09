@props([
    'property',
    'submissionToken',
    'receipt' => null,
    'modal' => false,
])

@php
    $prefix = $modal ? 'project_modal_' : 'project_';
    $plotOptions = collect($property['options'] ?? [])->sortBy('size_sqm');
@endphp

<div data-project-enquiry-flow>
    <form method="POST" action="{{ route('project-enquiries.store') }}" @class(['mt-7' => $modal, 'rounded-[1.5rem] border border-ink-200 bg-ink-50 p-6 md:p-8' => ! $modal]) data-project-enquiry-form data-submit-once data-async-form="project-enquiry" data-step-form novalidate @if($receipt) hidden @endif>
        @csrf
        <input type="hidden" name="submission_token" value="{{ $submissionToken }}" data-submission-token>
        <div class="absolute -left-[10000px] top-auto size-px overflow-hidden" aria-hidden="true">
            <label for="{{ $prefix }}website">Leave this field empty</label>
            <input id="{{ $prefix }}website" name="website" type="text" value="" tabindex="-1" autocomplete="off">
        </div>

        <div class="mb-7" data-step-progress hidden>
            <div class="flex items-center justify-between gap-4 text-sm font-bold text-ink-500"><span data-step-label>Step 1 of 2</span><span data-step-name>Plot preference</span></div>
            <div class="mt-3 h-1.5 overflow-hidden rounded-full bg-brand-100"><span class="block h-full rounded-full bg-brand-700 transition-[width]" data-step-progress-bar></span></div>
        </div>
        <div class="form-message mb-6" role="alert" tabindex="-1" data-async-error-summary hidden></div>

        @if (! $modal && $errors->any())
            <div class="mb-7 rounded-xl border border-brand-500 bg-white px-5 py-4" role="alert" tabindex="-1" data-form-error-summary>
                <h3 class="text-base font-semibold">Check the highlighted fields.</h3>
                <ul class="mt-3 list-disc space-y-1 pl-5 text-sm leading-6 text-brand-800">
                    @foreach ($errors->all() as $message)
                        <li>{{ $message }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <fieldset data-form-step="1" data-step-title="Plot preference">
            <legend class="font-display text-xl font-semibold">Which plot are you interested in?</legend>
            <p class="mt-2 text-sm leading-6 text-ink-500">Selecting an option does not reserve a plot. Availability will be confirmed by the team.</p>
            <div class="mt-5 grid gap-3 sm:grid-cols-2">
                @foreach ($plotOptions as $option)
                    @php $size = (string) (int) $option['size_sqm']; @endphp
                    <label class="flex cursor-pointer items-start gap-3 rounded-xl border border-ink-200 bg-white p-4 has-checked:border-brand-700 has-checked:ring-1 has-checked:ring-brand-700">
                        <input name="plot_option" type="radio" value="{{ $size }}" class="mt-1 size-4 shrink-0 accent-brand-700" required data-required-message="Please choose a plot size or select Not sure yet." @checked(old('plot_option') === $size)>
                        <span><strong class="block text-ink-950">{{ number_format($option['size_sqm']) }} sqm</strong><span class="mt-1 block text-sm text-ink-500">{{ $option['label'] }} · ₦{{ number_format($option['price']) }}</span></span>
                    </label>
                @endforeach
                <label class="flex cursor-pointer items-start gap-3 rounded-xl border border-ink-200 bg-white p-4 has-checked:border-brand-700 has-checked:ring-1 has-checked:ring-brand-700">
                    <input name="plot_option" type="radio" value="unsure" class="mt-1 size-4 shrink-0 accent-brand-700" required data-required-message="Please choose a plot size or select Not sure yet." @checked(old('plot_option') === 'unsure')>
                    <span><strong class="block text-ink-950">Not sure yet</strong><span class="mt-1 block text-sm text-ink-500">Let the team help you compare options.</span></span>
                </label>
            </div>

            <div class="mt-6 grid gap-5 sm:grid-cols-2">
                <div>
                    <label for="{{ $prefix }}payment_preference" class="form-label">Payment preference <span aria-hidden="true">*</span></label>
                    <select id="{{ $prefix }}payment_preference" name="payment_preference" class="form-control" required data-required-message="Please choose a payment preference.">
                        <option value="">Select an option</option>
                        @foreach (['Outright', 'Instalment', 'Not decided'] as $preference)
                            <option value="{{ $preference }}" @selected(old('payment_preference') === $preference)>{{ $preference }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="{{ $prefix }}purchase_timeline" class="form-label">When are you considering purchasing? <span aria-hidden="true">*</span></label>
                    <select id="{{ $prefix }}purchase_timeline" name="purchase_timeline" class="form-control" required data-required-message="Please choose when you are considering purchasing.">
                        <option value="">Select a timeline</option>
                        @foreach (['Immediately', '1–3 months', '3–6 months', 'Researching'] as $timeline)
                            <option value="{{ $timeline }}" @selected(old('purchase_timeline') === $timeline)>{{ $timeline }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </fieldset>

        <fieldset data-form-step="2" data-step-title="Contact details">
            <legend class="font-display text-xl font-semibold">How can we reach you?</legend>
            <div class="mt-5 grid gap-5 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <label for="{{ $prefix }}full_name" class="form-label">Full name <span aria-hidden="true">*</span></label>
                    <input id="{{ $prefix }}full_name" name="full_name" type="text" value="{{ old('full_name') }}" autocomplete="name" class="form-control" required data-required-message="Please enter your full name.">
                </div>
                <div>
                    <label for="{{ $prefix }}phone" class="form-label">Telephone number <span aria-hidden="true">*</span></label>
                    <input id="{{ $prefix }}phone" name="phone" type="tel" value="{{ old('phone') }}" autocomplete="tel" inputmode="tel" minlength="7" class="form-control" required data-required-message="Please enter your telephone number." data-invalid-message="Please enter a valid telephone number.">
                </div>
                <div>
                    <label for="{{ $prefix }}contact_method" class="form-label">Preferred contact method <span aria-hidden="true">*</span></label>
                    <select id="{{ $prefix }}contact_method" name="contact_method" class="form-control" required data-contact-method data-required-message="Please choose how you would like us to contact you.">
                        <option value="">Select a contact method</option>
                        @foreach (['Telephone', 'WhatsApp', 'Email'] as $method)
                            <option value="{{ $method }}" @selected(old('contact_method') === $method)>{{ $method }}</option>
                        @endforeach
                    </select>
                </div>
                <div data-conditional-contact-group="WhatsApp">
                    <label for="{{ $prefix }}whatsapp" class="form-label">WhatsApp number <span data-contact-requirement data-method="WhatsApp"></span></label>
                    <input id="{{ $prefix }}whatsapp" name="whatsapp" type="tel" value="{{ old('whatsapp') }}" autocomplete="tel" inputmode="tel" minlength="7" class="form-control" data-conditional-contact="WhatsApp" data-required-message="Please enter your WhatsApp number.">
                </div>
                <div data-conditional-contact-group="Email">
                    <label for="{{ $prefix }}email" class="form-label">Email address <span data-contact-requirement data-method="Email"></span></label>
                    <input id="{{ $prefix }}email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" class="form-control" data-conditional-contact="Email" data-required-message="Please enter your email address." data-invalid-message="Please enter a valid email address.">
                </div>
                <div class="sm:col-span-2">
                    <label for="{{ $prefix }}message" class="form-label">Message <span class="font-normal text-ink-500">(optional)</span></label>
                    <textarea id="{{ $prefix }}message" name="message" rows="3" maxlength="2000" class="form-control" placeholder="Share any question about this plot option.">{{ old('message') }}</textarea>
                </div>
            </div>
            <label class="mt-6 flex items-start gap-3 leading-7 text-ink-800" for="{{ $prefix }}consent">
                <input id="{{ $prefix }}consent" name="consent" type="checkbox" value="1" class="mt-1 size-5 shrink-0 accent-brand-700" required data-required-message="Please allow Selotemna to use these details to respond to your request." @checked(old('consent'))>
                <span>I agree that Selotemna may use these details to respond to my enquiry. <span aria-hidden="true">*</span></span>
            </label>
            <button type="submit" class="mt-7 inline-flex min-h-12 w-full items-center justify-center rounded-xl bg-brand-700 px-5 py-3 text-sm font-semibold text-white hover:bg-brand-800 disabled:cursor-wait disabled:opacity-70 sm:w-auto sm:min-w-56" data-submit-button data-pending-label="Sending enquiry…">
                <span aria-live="polite" data-submit-label>Send Plot Enquiry</span>
            </button>
            <span class="sr-only" aria-live="polite" data-submit-status></span>
        </fieldset>
    </form>

    <div class="rounded-[1.25rem] border border-brand-100 bg-brand-50 p-6 sm:p-8" role="status" tabindex="-1" data-async-success="project-enquiry" @if(! $receipt) hidden @endif>
        <span class="eyebrow">Enquiry received</span>
        <h2 class="text-2xl font-semibold sm:text-3xl">Thanks! We received your Omu Creek enquiry.</h2>
        <p class="mt-4 leading-7 text-ink-500" data-success-message>Our team will contact you within 24 hours to confirm availability and discuss the next step.</p>
        <dl class="mt-6 border-y border-brand-100">
            <div class="summary-row"><dt>Reference</dt><dd class="font-mono" data-success-reference>{{ $receipt['reference'] ?? '' }}</dd></div>
            <div class="summary-row"><dt>Plot preference</dt><dd data-success-plot>{{ $receipt['plot'] ?? '' }}</dd></div>
            <div class="summary-row"><dt>Timeline</dt><dd data-success-timeline>{{ $receipt['timeline'] ?? '' }}</dd></div>
        </dl>
        <p class="mt-5 text-sm leading-6 text-ink-500">Plot availability and pricing remain subject to confirmation.</p>
        <div class="mt-7 flex flex-col gap-3 sm:flex-row sm:flex-wrap">
            <x-site.button href="{{ route('inspections.create', ['interest' => 'Omu Creek']) }}">Request an Inspection</x-site.button>
            @if ($modal)
                <button type="button" class="inline-flex min-h-12 items-center justify-center rounded-xl border border-ink-200 bg-white px-5 py-3 text-sm font-semibold" data-project-enquiry-dialog-close>Done</button>
            @else
                <x-site.button href="{{ route('omu-creek') }}" variant="secondary">Return to Omu Creek</x-site.button>
            @endif
            <button type="button" class="inline-flex min-h-12 items-center justify-center px-5 py-3 text-sm font-semibold text-brand-700" data-form-reset>Submit another enquiry</button>
        </div>
    </div>
</div>
