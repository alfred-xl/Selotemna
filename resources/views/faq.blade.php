@extends('layouts.site')

@section('content')
    <x-site.page-hero variant="overlay" heading="Frequently Asked Questions" intro="Clear answers about Omu Creek’s location, title, prices, payments, allocation and policies." :background-image="$heroImage" image-position="center 48%" :show-breadcrumbs="false" :show-eyebrow="false" alignment="center" overlay-size="compact" />

    @php
        $questionContact = collect([
            ['url' => $contact['email_url'] ?? null, 'label' => 'Email Selotemna'],
            ['url' => $contact['whatsapp_url'] ?? null, 'label' => 'Ask on WhatsApp'],
            ['url' => $contact['phone_url'] ?? null, 'label' => 'Call Selotemna'],
        ])->first(fn (array $channel): bool => filled($channel['url']));
    @endphp

    <section class="section-space bg-white" data-faq-page>
        <div class="site-container">
            <div class="grid min-w-0 gap-12 lg:grid-cols-[minmax(0,15rem)_minmax(0,48rem)] lg:justify-between lg:gap-16 xl:gap-20">
                <aside class="min-w-0 lg:self-start">
                    <div class="lg:sticky lg:top-28">
                        <p class="mb-4 font-display text-sm font-semibold text-ink-950">FAQ categories</p>
                        <nav class="min-w-0" aria-label="FAQ categories" data-faq-category-nav>
                            <div class="overflow-x-auto border-y border-ink-200 lg:overflow-visible lg:border-y-0 lg:border-l">
                                <ul class="flex w-max min-w-full lg:w-full lg:min-w-0 lg:flex-col">
                                    @foreach ($faqGroups as $key => $group)
                                        <li class="shrink-0 lg:w-full">
                                            <a href="#faq-group-{{ $key }}" class="faq-category-link" @if ($loop->first) aria-current="location" @endif data-faq-category-link>
                                                {{ $group['label'] }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </nav>
                    </div>
                </aside>

                <div class="min-w-0 space-y-16 md:space-y-20">
                    @foreach ($faqGroups as $key => $group)
                        <section id="faq-group-{{ $key }}" class="scroll-mt-28" aria-labelledby="faq-group-{{ $key }}-heading" data-faq-category-section>
                            <h2 id="faq-group-{{ $key }}-heading" class="mb-7 text-[clamp(1.65rem,3vw,2.25rem)] font-semibold leading-tight tracking-[-0.025em]">{{ $group['label'] }}</h2>
                            <x-site.faq :items="$group['items']" :id-prefix="'faq-'.$key" :collapsed-by-default="true" :single-open="true" />
                        </section>
                    @endforeach
                </div>
            </div>

            @if ($questionContact)
                <div class="mt-16 flex flex-col gap-3 border-t border-ink-200 pt-8 sm:flex-row sm:items-center sm:justify-between" data-reveal>
                    <p class="font-display text-lg font-semibold text-ink-950">Still have a question?</p>
                    <a href="{{ $questionContact['url'] }}" class="text-link w-fit">
                        {{ $questionContact['label'] }}
                        <span aria-hidden="true">→</span>
                    </a>
                </div>
            @endif
        </div>
    </section>

    <x-site.conversion-cta heading="Ready to continue with Omu Creek?" intro="Review the complete project information or submit an inspection request with your preferred date. Submitting an inspection request does not confirm an appointment; a representative will follow up." primary-label="Request an Inspection" :primary-href="route('inspections.create', ['interest' => 'Omu Creek'])" secondary-label="View Omu Creek" :secondary-href="route('omu-creek')" />
@endsection
