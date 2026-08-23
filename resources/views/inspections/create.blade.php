@extends('layouts.site')

@section('content')
    <x-site.page-hero eyebrow="Book Inspection" heading="Request an Omu Creek inspection." intro="Choose a preferred date and share the best way to reach you. Selotemna will review the request and follow up; submitting it does not automatically confirm an appointment." :breadcrumbs="[['label' => 'Home', 'href' => route('home')], ['label' => 'Book Inspection']]" :media="$inspectionMedia" />

    <section class="section-space bg-white">
        <div class="site-container grid items-start gap-12 lg:grid-cols-[0.7fr_1.3fr] lg:gap-20">
            <aside class="lg:sticky lg:top-28" data-reveal>
                <x-site.section-heading eyebrow="What happens next" heading="A request first. Confirmation follows." intro="Your request is saved before any optional email notification is attempted, so a mail-delivery issue will not discard it." />

                <ol class="mt-8 space-y-5 text-ink-500" data-reveal-group>
                    <li class="numbered-step" data-reveal><span>1</span><p>Provide your contact details and select a preferred inspection date.</p></li>
                    <li class="numbered-step" data-reveal><span>2</span><p>Receive a request reference after the form has been saved.</p></li>
                    <li class="numbered-step" data-reveal><span>3</span><p>Wait for a Selotemna representative to confirm availability and the next step.</p></li>
                </ol>

                <div class="mt-10 border-t border-ink-200 pt-7">
                    <span class="eyebrow">Before you submit</span>
                    <ul class="space-y-3">
                        <li class="feature-line">The request is for Omu Creek.</li>
                        <li class="feature-line">The selected date and period are preferences.</li>
                        <li class="feature-line">No payment is collected through this form.</li>
                    </ul>
                </div>

                @if ($contact['phone_url'] || $contact['whatsapp_url'] || $contact['email_url'])
                    <div class="mt-10 border-t border-ink-200 pt-7">
                        <span class="eyebrow">Published contact channels</span>
                        <x-site.contact-channels :contact="$contact" :show-details="false" />
                    </div>
                @endif
            </aside>

            <div>
                @if ($receipt)
                    <div class="rounded-[1.5rem] border border-brand-100 bg-brand-50 p-6 md:p-9" role="status" data-inspection-receipt data-reveal>
                        <span class="eyebrow">Request received</span>
                        <h2 class="text-3xl font-semibold md:text-4xl">Your request has been saved.</h2>
                        <p class="mt-5 max-w-2xl leading-7 text-ink-500">A Selotemna representative will review your preferred date and contact you about availability. This receipt does not confirm an inspection appointment.</p>

                        <dl class="mt-8 rounded-2xl border border-brand-100 bg-white px-5 md:px-7">
                            <div class="summary-row"><dt>Reference</dt><dd class="font-mono tracking-wide">{{ $receipt['reference'] }}</dd></div>
                            <div class="summary-row"><dt>Opportunity</dt><dd>{{ $receipt['project'] }}</dd></div>
                            <div class="summary-row"><dt>Preferred date</dt><dd>{{ $receipt['preferred_date'] }}</dd></div>
                            <div class="summary-row"><dt>Preferred period</dt><dd>{{ $receipt['preferred_time'] }}</dd></div>
                        </dl>

                        <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                            <x-site.button href="{{ route('omu-creek') }}">Review Omu Creek</x-site.button>
                            <x-site.button href="{{ route('inspections.create') }}" variant="secondary">Submit another request</x-site.button>
                        </div>
                    </div>
                @else
                    <form method="POST" action="{{ route('inspections.store') }}" class="rounded-[1.5rem] border border-ink-200 bg-ink-50 p-6 md:p-8" data-inspection-form data-submit-once data-reveal novalidate>
                        @csrf
                        <input type="hidden" name="submission_token" value="{{ $submissionToken }}">
                        <input type="hidden" name="interest" value="Omu Creek">

                        <div class="absolute -left-[10000px] top-auto size-px overflow-hidden" aria-hidden="true">
                            <label for="website">Leave this field empty</label>
                            <input id="website" name="website" type="text" value="" tabindex="-1" autocomplete="off">
                        </div>

                        <div class="flex flex-col gap-4 border-b border-ink-200 pb-7 sm:flex-row sm:items-start sm:justify-between">
                            <div>
                                <span class="eyebrow">Inspection request</span>
                                <h2 class="text-2xl font-semibold md:text-3xl">Tell us when and how to reach you.</h2>
                            </div>
                            <div class="shrink-0 rounded-xl border border-brand-100 bg-white px-4 py-3">
                                <span class="block text-xs font-bold uppercase tracking-[0.14em] text-brand-700">Opportunity</span>
                                <span class="mt-1 block font-semibold text-ink-950">Omu Creek</span>
                            </div>
                        </div>

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

                        <div class="mt-8 grid gap-6 md:grid-cols-2">
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
                                <select id="contact_method" name="contact_method" class="form-control" required @error('contact_method') aria-describedby="contact_method_error" aria-invalid="true" @enderror>
                                    <option value="">Select a contact method</option>
                                    @foreach (['Telephone', 'WhatsApp', 'Email'] as $method)
                                        <option value="{{ $method }}" @selected(old('contact_method') === $method)>{{ $method }}</option>
                                    @endforeach
                                </select>
                                @error('contact_method') <p id="contact_method_error" class="form-error">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="whatsapp" class="form-label">WhatsApp number <span class="font-normal text-ink-500">(required when selected)</span></label>
                                <input id="whatsapp" name="whatsapp" type="tel" value="{{ old('whatsapp') }}" autocomplete="tel" class="form-control" inputmode="tel" @error('whatsapp') aria-describedby="whatsapp_error" aria-invalid="true" @enderror>
                                @error('whatsapp') <p id="whatsapp_error" class="form-error">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="email" class="form-label">Email address <span class="font-normal text-ink-500">(required when selected)</span></label>
                                <input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" class="form-control" @error('email') aria-describedby="email_error" aria-invalid="true" @enderror>
                                @error('email') <p id="email_error" class="form-error">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="preferred_date" class="form-label">Preferred date <span aria-hidden="true">*</span></label>
                                <input id="preferred_date" name="preferred_date" type="date" min="{{ now()->toDateString() }}" value="{{ old('preferred_date') }}" class="form-control" required @error('preferred_date') aria-describedby="preferred_date_error" aria-invalid="true" @enderror>
                                @error('preferred_date') <p id="preferred_date_error" class="form-error">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="preferred_time" class="form-label">Preferred period <span class="font-normal text-ink-500">(optional)</span></label>
                                <select id="preferred_time" name="preferred_time" class="form-control" @error('preferred_time') aria-describedby="preferred_time_error" aria-invalid="true" @enderror>
                                    <option value="">Select a period</option>
                                    @foreach (['Morning', 'Afternoon', 'No preference'] as $period)
                                        <option value="{{ $period }}" @selected(old('preferred_time') === $period)>{{ $period }}</option>
                                    @endforeach
                                </select>
                                @error('preferred_time') <p id="preferred_time_error" class="form-error">{{ $message }}</p> @enderror
                            </div>
                            <div class="md:col-span-2">
                                <label for="message" class="form-label">Additional information <span class="font-normal text-ink-500">(optional)</span></label>
                                <textarea id="message" name="message" rows="5" class="form-control" maxlength="2000" @error('message') aria-describedby="message_error" aria-invalid="true" @enderror>{{ old('message') }}</textarea>
                                <p class="mt-2 text-sm leading-6 text-ink-500">Include only details that will help Selotemna respond to this inspection request.</p>
                                @error('message') <p id="message_error" class="form-error">{{ $message }}</p> @enderror
                            </div>
                            <div class="md:col-span-2">
                                <label class="flex items-start gap-3 leading-7 text-ink-800" for="consent">
                                    <input id="consent" name="consent" type="checkbox" value="1" class="mt-1 size-5 shrink-0 accent-brand-700" @checked(old('consent')) required @error('consent') aria-describedby="consent_error" aria-invalid="true" @enderror>
                                    <span>I understand that my details will be used to review and respond to this request, and that submitting it does not automatically confirm an inspection appointment. <span aria-hidden="true">*</span></span>
                                </label>
                                @error('consent') <p id="consent_error" class="form-error">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <button type="submit" class="mt-8 inline-flex min-h-12 w-full items-center justify-center rounded-xl bg-brand-700 px-5 py-3 text-sm font-semibold text-white transition hover:bg-brand-800 disabled:cursor-wait disabled:opacity-70 sm:w-auto" data-submit-button data-pending-label="Saving request…">
                            <span data-submit-label>Submit Inspection Request</span>
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </section>
@endsection
