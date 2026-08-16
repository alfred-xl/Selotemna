@php
    $contactHref = $contact['whatsapp_url'] ?? $contact['email_url'] ?? $contact['phone_url'];

    $services = [
        [
            'title' => 'Property Development',
            'description' => 'Planned real-estate developments created to serve residential, investment and commercial needs.',
            'href' => '#omu-creek',
            'link' => 'Explore Developments',
        ],
        [
            'title' => 'Land Sales',
            'description' => 'Explore available land opportunities and speak with our team about documentation and inspection details.',
            'href' => '#omu-creek',
            'link' => 'View Available Land',
        ],
        [
            'title' => 'House Sales',
            'description' => 'Discover available homes and residential opportunities for personal use, family living or investment.',
            'href' => '#inspection-process',
            'link' => 'Discuss House Sales',
        ],
        [
            'title' => 'Property Management',
            'description' => 'Discuss support for the day-to-day care, coordination and oversight of your property.',
            'href' => '#property-support',
            'link' => 'Discuss Property Management',
        ],
        [
            'title' => 'Construction',
            'description' => 'Speak with Selotemna about construction requirements for residential, commercial or development projects.',
            'href' => '#property-support',
            'link' => 'Discuss a Project',
        ],
    ];

    $faqs = [
        [
            'question' => 'How can I inspect Omu Creek?',
            'answer' => 'Submit an inspection request with your preferred date and contact details. A Selotemna representative will contact you with the relevant inspection information. Submitting a request does not automatically confirm the appointment.',
        ],
        [
            'question' => 'What types of property does Selotemna offer?',
            'answer' => 'Selotemna works across land sales, house sales and property development. The exact options available will change, so contact the team for current information.',
        ],
        [
            'question' => 'Can I enquire from outside Nigeria?',
            'answer' => 'Yes. Nigerians in the diaspora can contact Selotemna to discuss available opportunities and the inspection or enquiry options currently offered. Any remote support available for a particular transaction will be explained by the team.',
        ],
        [
            'question' => 'Are payment plans available?',
            'answer' => 'Payment arrangements depend on the specific property or development. Review the information provided for the opportunity or contact Selotemna for the applicable terms. Do not assume a payment plan is available until it is confirmed.',
        ],
        [
            'question' => 'What documentation comes with a property?',
            'answer' => 'Documentation varies by property and location. Selotemna will explain the documents presented for a specific opportunity. Buyers should also complete appropriate independent legal and professional due diligence before proceeding.',
        ],
        [
            'question' => 'Does Selotemna manage properties for owners?',
            'answer' => 'Selotemna provides property-management services. Contact the team with information about your property and the support you require so the available service can be discussed.',
        ],
        [
            'question' => 'Can I engage Selotemna for a construction project?',
            'answer' => 'Yes. Send a summary of the proposed project, its location and the type of construction required. The team will review the enquiry and explain the next steps.',
        ],
    ];
@endphp

@extends('layouts.site')

@section('content')
    <section class="relative overflow-hidden bg-white">
        <div class="site-container-wide grid min-h-[calc(100svh-5rem)] items-center gap-10 py-16 md:min-h-[42rem] md:grid-cols-12 md:py-20 lg:gap-16">
            <div class="md:col-span-7 lg:col-span-6">
                <span class="eyebrow">Property development · Sales · Management · Construction</span>
                <h1 class="max-w-3xl text-[clamp(2.35rem,6vw,4rem)] font-semibold leading-[1.06] tracking-[-0.045em]">
                    Property solutions built around your next move.
                </h1>
                <p class="mt-6 max-w-2xl text-lg leading-8 text-ink-500 md:text-xl">
                    From land and homes to property development, construction and management, Selotemna helps individuals, families and businesses take the next step with confidence.
                </p>
                <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:items-center">
                    <x-site.button href="#inspection-process" data-event="book_inspection_click">
                        Book an Inspection
                    </x-site.button>
                    <x-site.button href="#omu-creek" variant="secondary">
                        Explore Properties
                    </x-site.button>
                </div>
                @if ($contact['whatsapp_url'])
                    <a href="{{ $contact['whatsapp_url'] }}" class="text-link mt-5" data-event="whatsapp_click">Chat on WhatsApp</a>
                @endif
                <p class="mt-6 max-w-xl text-sm leading-6 text-ink-500">Tell us what you are looking for, and a Selotemna representative will guide you through the available options.</p>
            </div>

            <div class="brand-media flex aspect-[4/5] min-h-[26rem] items-center justify-center p-8 md:col-span-5 lg:col-span-6 lg:aspect-[5/4]" aria-hidden="true">
                <div class="relative flex size-56 items-center justify-center rounded-full border border-brand-100 bg-white/70 md:size-64">
                    <div class="absolute inset-6 rounded-full border border-brand-100"></div>
                    <img src="{{ asset('assets/logo.png') }}" alt="" class="relative w-28 md:w-32" width="189" height="153">
                </div>
            </div>
        </div>
    </section>

    <section id="services" class="section-space scroll-mt-20 bg-ink-50">
        <div class="site-container grid gap-12 lg:grid-cols-[0.75fr_1.25fr] lg:gap-20">
            <x-site.section-heading
                eyebrow="What we do"
                heading="One company. More ways to move forward with property."
                intro="Whether you want to buy, develop, build or manage a property, start with the service that matches your goal."
            />
            <div class="grid md:grid-cols-2">
                @foreach ($services as $service)
                    <x-site.service-pathway
                        :title="$service['title']"
                        :description="$service['description']"
                        :href="$service['href']"
                        :link-label="$service['link']"
                    />
                @endforeach
            </div>
        </div>
    </section>

    <section id="omu-creek" class="section-space scroll-mt-20 bg-white">
        <div class="site-container">
            <div class="mb-10 max-w-3xl md:mb-14">
                <span class="eyebrow">Featured land opportunity</span>
                <h2 class="text-[clamp(2.25rem,5vw,3.75rem)] font-semibold leading-[1.08] tracking-[-0.04em]">{{ $featuredProperty['name'] }}</h2>
                <p class="mt-5 text-xl leading-8 text-ink-800">Your next property investment could be waiting on the other side.</p>
            </div>

            <div class="grid items-start gap-10 lg:grid-cols-[minmax(0,1.15fr)_minmax(22rem,0.85fr)] lg:gap-14">
                <div class="aspect-video min-w-0 overflow-hidden rounded-[1.5rem] border border-brand-100 bg-brand-950">
                    @if ($featuredProperty['video_url'])
                        <video
                            class="h-full w-full bg-ink-950 object-contain"
                            controls
                            playsinline
                            preload="metadata"
                            @if ($featuredProperty['video_poster']) poster="{{ $featuredProperty['video_poster'] }}" @endif
                            data-event="omu_creek_video_play"
                        >
                            <source src="{{ $featuredProperty['video_url'] }}">
                            Your browser does not support embedded video. Contact Selotemna for Omu Creek property information.
                        </video>
                    @else
                        <div class="relative flex h-full items-center justify-center overflow-hidden" aria-hidden="true">
                            <div class="absolute -left-16 top-1/2 size-56 -translate-y-1/2 rounded-full border border-white/15"></div>
                            <div class="absolute -right-20 -top-20 size-72 rounded-full border border-white/10"></div>
                            <div class="absolute bottom-8 right-8 h-px w-1/2 bg-white/20"></div>
                            <img src="{{ asset('assets/logo.png') }}" alt="" class="relative w-24 rounded-xl bg-white p-3 sm:w-32" width="189" height="153">
                        </div>
                    @endif
                </div>

                <div class="min-w-0">
                    <p class="leading-7 text-ink-500 md:text-lg md:leading-8">Explore available land allocations at Omu Creek, with options for individual buyers, families and property investors. Choose a plot size that matches your plans and request an inspection with the Selotemna team.</p>

                    <dl class="mt-8 border-y border-ink-200">
                        <div class="grid gap-1 border-b border-ink-200 py-5 sm:grid-cols-[8rem_1fr] sm:gap-5">
                            <dt class="text-sm font-bold uppercase tracking-[0.12em] text-ink-500">Property type</dt>
                            <dd class="font-semibold text-ink-950">{{ $featuredProperty['type'] }}</dd>
                        </div>
                        <div class="grid gap-1 border-b border-ink-200 py-5 sm:grid-cols-[8rem_1fr] sm:gap-5">
                            <dt class="text-sm font-bold uppercase tracking-[0.12em] text-ink-500">Title</dt>
                            <dd class="font-semibold text-ink-950">{{ $featuredProperty['title'] }}</dd>
                        </div>
                        <div class="grid gap-1 py-5 sm:grid-cols-[8rem_1fr] sm:gap-5">
                            <dt class="text-sm font-bold uppercase tracking-[0.12em] text-ink-500">Rate</dt>
                            <dd class="font-display text-xl font-semibold text-brand-700">₦{{ number_format($featuredProperty['price_per_sqm']) }} per sqm</dd>
                        </div>
                    </dl>

                    <div class="mt-8">
                        <h3 class="text-lg font-semibold">Available allocation sizes</h3>
                        <dl class="mt-3 border-t border-ink-200">
                            @foreach ($featuredProperty['options'] as $option)
                                <div class="flex items-baseline justify-between gap-5 border-b border-ink-200 py-4">
                                    <dt class="font-semibold text-ink-800 tabular-nums">{{ number_format($option['size_sqm']) }} sqm</dt>
                                    <dd class="font-display text-lg font-semibold text-ink-950 tabular-nums">₦{{ number_format($option['price']) }}</dd>
                                </div>
                            @endforeach
                        </dl>
                    </div>

                    <p class="mt-5 text-sm leading-6 text-ink-500">{{ $featuredProperty['disclaimer'] }}</p>

                    <div class="mt-8 flex flex-col gap-3">
                        <x-site.button
                            href="#inspection-process"
                            class="w-full"
                            data-event="omu_creek_inspection_click"
                            data-property-interest="{{ $featuredProperty['name'] }}"
                        >
                            Request an Omu Creek Inspection
                        </x-site.button>
                        @if ($contact['whatsapp_url'])
                            <x-site.button
                                :href="$contact['whatsapp_url']"
                                variant="secondary"
                                class="w-full"
                                data-event="whatsapp_click"
                                data-property-interest="{{ $featuredProperty['name'] }}"
                            >
                                Ask About Omu Creek on WhatsApp
                            </x-site.button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="about" class="section-space scroll-mt-20 bg-brand-50">
        <div class="site-container grid items-center gap-12 lg:grid-cols-12 lg:gap-20">
            <div class="brand-media flex aspect-[4/3] items-center justify-center p-8 lg:col-span-7" aria-hidden="true">
                <svg class="size-20 text-brand-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 21h18M5 21V5h10v16M15 9h4v12M8 9h4M8 13h4M8 17h4" />
                </svg>
            </div>
            <div class="lg:col-span-5">
                <span class="eyebrow">About Selotemna</span>
                <h2 class="text-[clamp(1.875rem,4vw,2.75rem)] font-semibold leading-[1.14] tracking-[-0.03em]">Property expertise for every stage of the journey.</h2>
                <div class="mt-6 space-y-5 text-base leading-7 text-ink-500 md:text-lg md:leading-8">
                    <p>Selotemna is a real-estate company working across property development, land and house sales, property management and construction. We serve individuals, families, investors and businesses looking for practical property solutions that match their needs.</p>
                    <p>From the first enquiry to inspection and the next stage of a property decision, our role is to make the available options easier to understand and act on.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="diaspora" class="section-space scroll-mt-20 bg-brand-950 text-white">
        <div class="site-container grid items-center gap-12 lg:grid-cols-2 lg:gap-20">
            <div>
                <x-site.section-heading
                    eyebrow="For Nigerians in the diaspora"
                    heading="Explore property opportunities from wherever you are."
                    theme="dark"
                />
                <div class="mt-6 max-w-xl space-y-5 leading-7 text-white/75 md:text-lg md:leading-8">
                    <p>If you are considering property in Nigeria while living abroad, our team can explain available opportunities, inspection arrangements, property information and the next steps based on your needs.</p>
                    <p>Start with a conversation. Tell us your preferred property type and budget, and we will help you understand the relevant options currently available.</p>
                </div>
                @if ($contactHref)
                    <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                        <x-site.button :href="$contactHref" variant="reversed">Speak With an Adviser</x-site.button>
                        @if ($contact['whatsapp_url'])
                            <x-site.button :href="$contact['whatsapp_url']" variant="outline-reversed" data-event="whatsapp_click">Chat on WhatsApp</x-site.button>
                        @endif
                    </div>
                @endif
            </div>
            <div class="relative flex aspect-[4/3] items-center justify-center overflow-hidden rounded-[1.5rem] border border-white/20 bg-white/5 p-8" aria-hidden="true">
                <div class="absolute inset-x-8 top-1/2 h-px bg-white/15"></div>
                <div class="absolute inset-y-8 left-1/2 w-px bg-white/15"></div>
                <svg class="relative size-20 text-brand-100" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="9" />
                    <path d="M3 12h18M12 3a14 14 0 0 1 0 18M12 3a14 14 0 0 0 0 18" />
                </svg>
            </div>
        </div>
    </section>

    <section id="inspection-process" class="section-space scroll-mt-20 bg-ink-50">
        <div class="site-container">
            <x-site.section-heading
                eyebrow="Book an inspection"
                heading="See the property before making your next decision."
                intro="Our inspection process starts with understanding what interests you and arranging the most appropriate next step."
                align="center"
            />

            <ol class="mt-12 grid gap-8 md:grid-cols-3 md:gap-0">
                @foreach ([
                    ['Choose an opportunity', 'Review Omu Creek or tell our team the type of property you need.'],
                    ['Share your details', 'Provide your preferred date, contact information and the property you want to inspect.'],
                    ['Receive the next step', 'A Selotemna representative will contact you with the relevant inspection information.'],
                ] as $step)
                    <li class="relative flex gap-5 border-t border-ink-200 pt-6 md:block md:border-l md:border-t-0 md:px-8 md:pt-0 first:md:border-l-0">
                        <span class="font-display text-4xl font-semibold text-brand-500" aria-hidden="true">0{{ $loop->iteration }}</span>
                        <div class="md:mt-8">
                            <h3 class="text-xl font-semibold">{{ $step[0] }}</h3>
                            <p class="mt-3 leading-7 text-ink-500">{{ $step[1] }}</p>
                        </div>
                    </li>
                @endforeach
            </ol>

            @if ($contactHref)
                <div class="mt-12 flex justify-center">
                    <x-site.button :href="$contactHref" data-event="book_inspection_click">Request an Inspection</x-site.button>
                </div>
            @endif
        </div>
    </section>

    <section id="property-support" class="section-space scroll-mt-20 bg-white">
        <div class="site-container grid gap-6 lg:grid-cols-2">
            <article class="overflow-hidden rounded-[1.5rem] bg-ink-950 text-white">
                <div class="relative flex aspect-[16/8] items-center justify-center overflow-hidden border-b border-white/15 bg-white/5" aria-hidden="true">
                    <div class="absolute left-1/4 h-full w-px rotate-12 bg-white/10"></div>
                    <div class="absolute right-1/4 h-full w-px -rotate-12 bg-white/10"></div>
                    <svg class="size-16 text-brand-100" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 21V10l8-6 8 6v11M8 21v-6h8v6M3 21h18" />
                    </svg>
                </div>
                <div class="p-8 md:p-10">
                    <span class="eyebrow !text-brand-100">Construction</span>
                    <h2 class="text-3xl font-semibold !text-white">Bring your property plans closer to reality.</h2>
                    <p class="mt-4 leading-7 text-white/70">Speak with Selotemna about your construction requirements, project scope and the type of property you want to create.</p>
                    @if ($contactHref)
                        <x-site.button :href="$contactHref" variant="reversed" class="mt-7">Discuss a Construction Project</x-site.button>
                    @endif
                </div>
            </article>

            <article class="overflow-hidden rounded-[1.5rem] border border-brand-100 bg-brand-50">
                <div class="relative flex aspect-[16/8] items-center justify-center overflow-hidden border-b border-brand-100" aria-hidden="true">
                    <div class="absolute size-44 rounded-full border border-brand-100"></div>
                    <div class="absolute size-28 rounded-full border border-brand-100"></div>
                    <svg class="relative size-16 text-brand-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M5 21V9l7-5 7 5v12M9 21v-6h6v6M3 21h18" />
                    </svg>
                </div>
                <div class="p-8 md:p-10">
                    <span class="eyebrow">Property management</span>
                    <h2 class="text-3xl font-semibold">Support for your property beyond the transaction.</h2>
                    <p class="mt-4 leading-7 text-ink-500">Tell us about the property you own and the management support you require. Our team will explain the services available for your situation.</p>
                    @if ($contactHref)
                        <x-site.button :href="$contactHref" class="mt-7">Discuss Property Management</x-site.button>
                    @endif
                </div>
            </article>
        </div>
    </section>

    <section class="section-space bg-brand-50">
        <div class="site-container grid gap-12 lg:grid-cols-[0.7fr_1.3fr] lg:gap-20">
            <x-site.section-heading eyebrow="Why work with us" heading="A practical approach to different property needs." />
            <div class="grid sm:grid-cols-2">
                @foreach ([
                    ['Multiple property services', 'Access development, sales, construction and management services through one company.'],
                    ['Guidance based on your goal', 'Begin with what you want to achieve, whether that is buying, developing, building, investing or managing.'],
                    ['Inspection-led decisions', 'Move from online interest to a direct conversation and a properly arranged inspection where applicable.'],
                    ['Support for different customer types', 'Selotemna serves individuals, families, investors, diaspora customers and businesses with different property requirements.'],
                ] as $benefit)
                    <article class="border-t border-brand-100 py-7 sm:px-5">
                        <h3 class="text-xl font-semibold">{{ $benefit[0] }}</h3>
                        <p class="mt-3 leading-7 text-ink-500">{{ $benefit[1] }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section id="faq" class="section-space scroll-mt-20 bg-white">
        <div class="site-container grid gap-12 lg:grid-cols-[0.75fr_1.25fr] lg:gap-20">
            <div>
                <x-site.section-heading
                    eyebrow="Frequently asked questions"
                    heading="What would you like to know?"
                    intro="Review the details that commonly shape a property, construction or management enquiry."
                />
                @if ($contactHref)
                    <a href="{{ $contactHref }}" class="text-link mt-7">Speak with the Selotemna team</a>
                @endif
            </div>
            <x-site.faq :items="$faqs" />
        </div>
    </section>

    <section class="bg-white pb-16 md:pb-20 lg:pb-28">
        <div class="site-container rounded-[1.5rem] bg-brand-700 px-6 py-14 text-white md:px-12 md:py-16 lg:px-20">
            <div class="max-w-3xl">
                <h2 class="text-[clamp(2rem,4vw,3rem)] font-semibold leading-tight !text-white">Ready to take the next step with property?</h2>
                <p class="mt-5 max-w-2xl text-lg leading-8 text-white/75">Explore Omu Creek, review the inspection process or speak directly with the Selotemna team about what you need.</p>
                <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                    <x-site.button href="#inspection-process" variant="reversed" data-event="book_inspection_click">Book an Inspection</x-site.button>
                    @if ($contact['whatsapp_url'])
                        <x-site.button :href="$contact['whatsapp_url']" variant="outline-reversed" data-event="whatsapp_click">Chat on WhatsApp</x-site.button>
                    @endif
                </div>
                @if ($contact['phone_url'])
                    <a href="{{ $contact['phone_url'] }}" class="mt-5 inline-flex min-h-11 items-center font-semibold text-white underline decoration-white/50 underline-offset-4 hover:decoration-white" data-event="call_agent_click">Call an Agent</a>
                @endif
            </div>
        </div>
    </section>
@endsection
