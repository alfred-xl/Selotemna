@props(['contact'])

<footer class="bg-ink-950 text-white" data-site-footer>
    <div class="site-container py-16 md:py-20">
        <div class="grid gap-12 border-b border-white/15 pb-12 md:grid-cols-2 lg:grid-cols-[1.25fr_0.85fr_1fr_0.9fr]">
            <div class="max-w-sm">
                <a href="{{ route('home') }}" class="inline-flex rounded-xl bg-white p-3" aria-label="Selotemna home">
                    <img src="{{ asset('assets/logo.png') }}" alt="Selotemna, RC 7361086" class="w-24" width="189" height="153" loading="lazy">
                </a>
                <p class="mt-6 leading-7 text-white/70">Explore Selotemna’s development opportunities or begin an engineering or construction enquiry.</p>
                <p class="mt-4 text-sm font-semibold text-brand-100">RC 7361086</p>
            </div>

            <div>
                <h2 class="!text-sm font-semibold uppercase tracking-[0.14em] !text-white">Company</h2>
                <ul class="mt-5 space-y-1 text-white/70">
                    <li><a href="{{ route('about') }}" class="footer-link">About</a></li>
                    <li><a href="{{ route('projects.index') }}" class="footer-link">Projects</a></li>
                    <li><a href="{{ route('faq') }}" class="footer-link">FAQ</a></li>
                    <li><a href="{{ route('contact') }}" class="footer-link">Contact</a></li>
                </ul>
            </div>

            <div>
                <h2 class="!text-sm font-semibold uppercase tracking-[0.14em] !text-white">Services &amp; projects</h2>
                <ul class="mt-5 space-y-1 text-white/70">
                    <li><a href="{{ route('real-estate-development') }}" class="footer-link">Real Estate Development</a></li>
                    <li><a href="{{ route('omu-creek') }}" class="footer-link">Omu Creek</a></li>
                    <li><a href="{{ route('engineering-construction') }}" class="footer-link">Engineering &amp; Construction</a></li>
                    <li><a href="{{ route('inspections.create') }}" class="footer-link">Book an Inspection</a></li>
                </ul>
            </div>

            @if (collect($contact)->filter()->isNotEmpty())
                <div>
                    <h2 class="!text-sm font-semibold uppercase tracking-[0.14em] !text-white">Contact</h2>
                    <ul class="mt-5 space-y-4 text-sm leading-6 text-white/70">
                        @forelse ($contact['phones'] ?? [] as $phone)
                            <li><a href="{{ $phone['url'] }}" class="hover:text-white">{{ $phone['display'] }}</a></li>
                        @empty
                            @if ($contact['phone_url']) <li><a href="{{ $contact['phone_url'] }}" class="hover:text-white">{{ $contact['phone'] }}</a></li> @endif
                        @endforelse
                        @if ($contact['whatsapp_url']) <li><a href="{{ $contact['whatsapp_url'] }}" class="hover:text-white">Chat on WhatsApp</a></li> @endif
                        @forelse ($contact['emails'] ?? [] as $email)
                            <li><a href="{{ $email['url'] }}" class="break-words hover:text-white">{{ $email['address'] }}</a></li>
                        @empty
                            @if ($contact['email_url']) <li><a href="{{ $contact['email_url'] }}" class="break-words hover:text-white">{{ $contact['email'] }}</a></li> @endif
                        @endforelse
                        @if ($contact['address']) <li>{{ $contact['address'] }}</li> @endif
                        @if ($contact['business_hours']) <li>{{ $contact['business_hours'] }}</li> @endif
                    </ul>
                </div>
            @endif
        </div>

        <div class="flex flex-col gap-3 pt-7 text-sm text-white/55 md:flex-row md:items-center md:justify-between">
            <p>&copy; {{ now()->year }} Selotemna. All rights reserved.</p>
            <p>Real Estate Development <span aria-hidden="true">·</span> Engineering &amp; Construction</p>
        </div>
    </div>
</footer>
