@props(['contact'])

<footer id="contact" class="scroll-mt-20 bg-ink-950 text-white" data-site-footer>
    <div class="site-container py-16 md:py-20">
        <div class="grid gap-12 border-b border-white/15 pb-12 md:grid-cols-2 lg:grid-cols-[1.4fr_0.8fr_0.8fr_0.9fr]">
            <div class="max-w-sm">
                <a href="{{ route('home') }}" class="inline-flex rounded-xl bg-white p-3" aria-label="Selotemna home">
                    <img src="{{ asset('assets/logo.png') }}" alt="Selotemna, RC 7361086" class="w-24" width="189" height="153" loading="lazy">
                </a>
                <p class="mt-6 leading-7 text-white/70">Selotemna works across property development, land and house sales, property management and construction.</p>
                <p class="mt-4 text-sm font-semibold text-brand-100">RC 7361086</p>
            </div>

            <div>
                <h2 class="!text-sm font-semibold uppercase tracking-[0.14em] !text-white">Company</h2>
                <ul class="mt-5 space-y-3 text-white/70">
                    <li><a href="{{ route('home').'#about' }}" class="inline-flex min-h-11 items-center hover:text-white">About Us</a></li>
                    <li><a href="{{ route('home').'#faq' }}" class="inline-flex min-h-11 items-center hover:text-white">Frequently Asked Questions</a></li>
                    <li><a href="{{ route('home').'#contact' }}" class="inline-flex min-h-11 items-center hover:text-white">Contact</a></li>
                </ul>
            </div>

            <div>
                <h2 class="!text-sm font-semibold uppercase tracking-[0.14em] !text-white">Explore</h2>
                <ul class="mt-5 space-y-3 text-white/70">
                    <li><a href="{{ route('home').'#omu-creek' }}" class="inline-flex min-h-11 items-center hover:text-white">Properties</a></li>
                    <li><a href="{{ route('home').'#services' }}" class="inline-flex min-h-11 items-center hover:text-white">Services</a></li>
                    <li><a href="{{ route('home').'#inspection-process' }}" class="inline-flex min-h-11 items-center hover:text-white">Book an Inspection</a></li>
                </ul>
            </div>

            @if (collect($contact)->filter()->isNotEmpty())
                <div>
                    <h2 class="!text-sm font-semibold uppercase tracking-[0.14em] !text-white">Contact</h2>
                    <ul class="mt-5 space-y-4 text-sm leading-6 text-white/70">
                        @if ($contact['phone_url'])
                            <li><a href="{{ $contact['phone_url'] }}" class="hover:text-white">{{ $contact['phone'] }}</a></li>
                        @endif
                        @if ($contact['whatsapp_url'])
                            <li><a href="{{ $contact['whatsapp_url'] }}" class="hover:text-white">Chat on WhatsApp</a></li>
                        @endif
                        @if ($contact['email_url'])
                            <li><a href="{{ $contact['email_url'] }}" class="break-words hover:text-white">{{ $contact['email'] }}</a></li>
                        @endif
                        @if ($contact['address'])
                            <li>{{ $contact['address'] }}</li>
                        @endif
                        @if ($contact['business_hours'])
                            <li>{{ $contact['business_hours'] }}</li>
                        @endif
                    </ul>
                </div>
            @endif
        </div>

        <div class="flex flex-col gap-3 pt-7 text-sm text-white/55 md:flex-row md:items-center md:justify-between">
            <p>&copy; {{ now()->year }} Selotemna. All rights reserved.</p>
            <p>Property development, sales, construction and management.</p>
        </div>
    </div>
</footer>
