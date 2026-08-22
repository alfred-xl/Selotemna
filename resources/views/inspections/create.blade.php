@extends('layouts.site')

@section('content')
    <x-site.page-hero eyebrow="Book Inspection" heading="Request a property inspection." intro="Share your preferred date and contact information. A Selotemna representative will follow up; submitting this request does not automatically confirm an appointment." :breadcrumbs="[['label' => 'Home', 'href' => route('home')], ['label' => 'Book Inspection']]" />

    <section class="section-space bg-white">
        <div class="site-container grid items-start gap-12 lg:grid-cols-[0.72fr_1.28fr] lg:gap-20">
            <div>
                <x-site.section-heading eyebrow="How it works" heading="A request first, then follow-up." intro="Choose the opportunity, provide a preferred date and tell the team how to contact you. A representative will follow up about availability and confirmation." />
                <ol class="mt-8 space-y-5 text-ink-500">
                    <li class="numbered-step"><span>1</span><p>Share your contact information and opportunity of interest.</p></li>
                    <li class="numbered-step"><span>2</span><p>Choose a preferred date and, optionally, a time period.</p></li>
                    <li class="numbered-step"><span>3</span><p>Wait for a Selotemna representative to follow up. The submission itself is not an appointment confirmation.</p></li>
                </ol>
            </div>

            <div>
                @if (session('status'))
                    <div class="mb-8 rounded-xl border border-brand-100 bg-brand-50 px-5 py-4 leading-7 text-brand-950" role="status">{{ session('status') }}</div>
                @endif

                @if ($canSubmit)
                    <form method="POST" action="{{ route('inspections.store') }}" class="rounded-[1.5rem] border border-ink-200 bg-ink-50 p-6 md:p-8" data-inspection-form>
                        @csrf
                        <div class="grid gap-6 md:grid-cols-2">
                            <div class="md:col-span-2">
                                <label for="full_name" class="form-label">Full name <span aria-hidden="true">*</span></label>
                                <input id="full_name" name="full_name" type="text" value="{{ old('full_name') }}" autocomplete="name" class="form-control" required @error('full_name') aria-describedby="full_name_error" aria-invalid="true" @enderror>
                                @error('full_name') <p id="full_name_error" class="form-error">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="phone" class="form-label">Telephone number <span aria-hidden="true">*</span></label>
                                <input id="phone" name="phone" type="tel" value="{{ old('phone') }}" autocomplete="tel" class="form-control" required @error('phone') aria-describedby="phone_error" aria-invalid="true" @enderror>
                                @error('phone') <p id="phone_error" class="form-error">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="whatsapp" class="form-label">WhatsApp number <span class="font-normal text-ink-500">(optional)</span></label>
                                <input id="whatsapp" name="whatsapp" type="tel" value="{{ old('whatsapp') }}" autocomplete="tel" class="form-control" @error('whatsapp') aria-describedby="whatsapp_error" aria-invalid="true" @enderror>
                                @error('whatsapp') <p id="whatsapp_error" class="form-error">{{ $message }}</p> @enderror
                            </div>
                            <div class="md:col-span-2">
                                <label for="email" class="form-label">Email <span class="font-normal text-ink-500">(optional)</span></label>
                                <input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" class="form-control" @error('email') aria-describedby="email_error" aria-invalid="true" @enderror>
                                @error('email') <p id="email_error" class="form-error">{{ $message }}</p> @enderror
                            </div>
                            <div class="md:col-span-2">
                                <label for="interest" class="form-label">Property or opportunity of interest <span aria-hidden="true">*</span></label>
                                <input id="interest" name="interest" type="text" value="{{ old('interest', request('interest', 'Omu Creek')) }}" class="form-control" required @error('interest') aria-describedby="interest_error" aria-invalid="true" @enderror>
                                @error('interest') <p id="interest_error" class="form-error">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="preferred_date" class="form-label">Preferred date <span aria-hidden="true">*</span></label>
                                <input id="preferred_date" name="preferred_date" type="date" min="{{ now()->toDateString() }}" value="{{ old('preferred_date') }}" class="form-control" required @error('preferred_date') aria-describedby="preferred_date_error" aria-invalid="true" @enderror>
                                @error('preferred_date') <p id="preferred_date_error" class="form-error">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="preferred_time" class="form-label">Preferred time period <span class="font-normal text-ink-500">(optional)</span></label>
                                <select id="preferred_time" name="preferred_time" class="form-control" @error('preferred_time') aria-describedby="preferred_time_error" aria-invalid="true" @enderror>
                                    <option value="">Select a time period</option>
                                    @foreach (['Morning', 'Afternoon', 'No preference'] as $period)<option value="{{ $period }}" @selected(old('preferred_time') === $period)>{{ $period }}</option>@endforeach
                                </select>
                                @error('preferred_time') <p id="preferred_time_error" class="form-error">{{ $message }}</p> @enderror
                            </div>
                            <div class="md:col-span-2">
                                <label for="contact_method" class="form-label">Preferred contact method <span class="font-normal text-ink-500">(optional)</span></label>
                                <select id="contact_method" name="contact_method" class="form-control" @error('contact_method') aria-describedby="contact_method_error" aria-invalid="true" @enderror>
                                    <option value="">Select a contact method</option>
                                    @foreach (['Telephone', 'WhatsApp', 'Email'] as $method)<option value="{{ $method }}" @selected(old('contact_method') === $method)>{{ $method }}</option>@endforeach
                                </select>
                                @error('contact_method') <p id="contact_method_error" class="form-error">{{ $message }}</p> @enderror
                            </div>
                            <div class="md:col-span-2">
                                <label for="message" class="form-label">Message <span class="font-normal text-ink-500">(optional)</span></label>
                                <textarea id="message" name="message" rows="5" class="form-control" @error('message') aria-describedby="message_error" aria-invalid="true" @enderror>{{ old('message') }}</textarea>
                                @error('message') <p id="message_error" class="form-error">{{ $message }}</p> @enderror
                            </div>
                            <div class="md:col-span-2">
                                <label class="flex items-start gap-3 leading-7 text-ink-800" for="consent">
                                    <input id="consent" name="consent" type="checkbox" value="1" class="mt-1 size-5 shrink-0 accent-brand-700" @checked(old('consent')) required @error('consent') aria-describedby="consent_error" aria-invalid="true" @enderror>
                                    <span>I understand that this submission is an inspection request and does not automatically confirm an appointment. <span aria-hidden="true">*</span></span>
                                </label>
                                @error('consent') <p id="consent_error" class="form-error">{{ $message }}</p> @enderror
                            </div>
                        </div>
                        <button type="submit" class="mt-8 inline-flex min-h-12 w-full items-center justify-center rounded-xl bg-brand-700 px-5 py-3 text-sm font-semibold text-white transition hover:bg-brand-800 sm:w-auto">Submit Inspection Request</button>
                    </form>
                @else
                    <div class="rounded-[1.5rem] border border-ink-200 bg-ink-50 p-6 md:p-8" data-inspection-contact-fallback>
                        <h2 class="text-2xl font-semibold">Request through a verified contact channel.</h2>
                        <p class="mt-4 leading-7 text-ink-500">An online request form is not active because a verified delivery email is not configured. Use any available option below; no submission will be silently discarded.</p>
                        <div class="mt-7"><x-site.contact-channels :contact="$contact" :show-details="false" /></div>
                        @if (! $contact['phone_url'] && ! $contact['whatsapp_url'] && ! $contact['email_url'])
                            <p class="mt-6 rounded-xl border border-brand-100 bg-white px-5 py-4 leading-7 text-ink-500">Verified direct contact channels are not currently published. You can still review the Omu Creek opportunity and prepare your preferred date and contact information.</p>
                            <a href="{{ route('omu-creek') }}" class="text-link mt-5">Review Omu Creek</a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection
