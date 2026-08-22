@props(['contact'])

@php
    $navigation = [
        ['label' => 'Home', 'route' => 'home', 'active' => request()->routeIs('home')],
        ['label' => 'About', 'route' => 'about', 'active' => request()->routeIs('about')],
        ['label' => 'Projects', 'route' => 'projects.index', 'active' => request()->routeIs('projects.*')],
        ['label' => 'FAQ', 'route' => 'faq', 'active' => request()->routeIs('faq')],
        ['label' => 'Contact', 'route' => 'contact', 'active' => request()->routeIs('contact')],
    ];
    $divisionsActive = request()->routeIs('real-estate-development', 'omu-creek', 'engineering-construction');
@endphp

<header class="sticky top-0 z-40 border-b border-ink-200 bg-white/95 backdrop-blur-sm" data-site-header>
    <div class="site-container-wide flex min-h-20 items-center justify-between gap-5">
        <a href="{{ route('home') }}" class="flex min-h-11 items-center" aria-label="Selotemna home">
            <img src="{{ asset('assets/logo.png') }}" alt="Selotemna, RC 7361086" class="w-[4.5rem] shrink-0 md:w-20" width="189" height="153">
        </a>

        <nav class="hidden items-center gap-1 lg:flex" aria-label="Primary navigation">
            @foreach (array_slice($navigation, 0, 2) as $item)
                <a
                    href="{{ route($item['route']) }}"
                    @class(['nav-link', 'nav-link-active' => $item['active']])
                    @if ($item['active']) aria-current="page" @endif
                >{{ $item['label'] }}</a>
            @endforeach

            <div class="relative" data-divisions-dropdown>
                <button
                    type="button"
                    @class(['nav-link gap-1.5', 'nav-link-active' => $divisionsActive])
                    aria-expanded="false"
                    aria-controls="desktop-divisions-menu"
                    data-divisions-toggle
                >
                    Divisions
                    <svg aria-hidden="true" class="size-4 transition-transform" data-divisions-icon viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m6 9 6 6 6-6" />
                    </svg>
                </button>
                <div id="desktop-divisions-menu" class="absolute left-0 top-[calc(100%+0.5rem)] hidden w-80 rounded-2xl border border-ink-200 bg-white p-2 shadow-xl" data-divisions-menu>
                    <a href="{{ route('real-estate-development') }}" class="dropdown-link" data-divisions-link @if (request()->routeIs('real-estate-development')) aria-current="page" @endif>
                        <span class="font-semibold text-ink-950">Real Estate Development</span>
                        <span class="mt-1 block text-xs leading-5 text-ink-500">Land, property and development opportunities.</span>
                    </a>
                    <a href="{{ route('engineering-construction') }}" class="dropdown-link" data-divisions-link @if (request()->routeIs('engineering-construction')) aria-current="page" @endif>
                        <span class="font-semibold text-ink-950">Engineering &amp; Construction</span>
                        <span class="mt-1 block text-xs leading-5 text-ink-500">Project discussions and construction requirements.</span>
                    </a>
                </div>
            </div>

            @foreach (array_slice($navigation, 2) as $item)
                <a
                    href="{{ route($item['route']) }}"
                    @class(['nav-link', 'nav-link-active' => $item['active']])
                    @if ($item['active']) aria-current="page" @endif
                >{{ $item['label'] }}</a>
            @endforeach
        </nav>

        <div class="hidden items-center gap-3 lg:flex">
            @if ($contact['phone_url'])
                <a href="{{ $contact['phone_url'] }}" class="inline-flex min-h-11 items-center px-2 text-sm font-semibold text-ink-950 hover:text-brand-700" data-event="call_agent_click">Call an Agent</a>
            @endif
            <x-site.button href="{{ route('inspections.create') }}" data-event="book_inspection_click" :aria-current="request()->routeIs('inspections.create') ? 'page' : null">Book an Inspection</x-site.button>
        </div>

        <button type="button" class="flex size-12 items-center justify-center rounded-xl border border-ink-200 text-ink-950 lg:hidden" aria-label="Open navigation" aria-expanded="false" aria-controls="mobile-navigation" data-menu-open>
            <svg aria-hidden="true" class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M4 7h16M4 12h16M4 17h16" /></svg>
        </button>
    </div>

    <div class="fixed inset-0 z-50 hidden lg:hidden" aria-hidden="true" data-menu-root>
        <button type="button" class="absolute inset-0 bg-ink-950/60" aria-label="Close navigation" tabindex="-1" data-menu-backdrop></button>
        <div id="mobile-navigation" class="absolute right-0 top-0 flex h-full w-[min(88vw,24rem)] translate-x-full flex-col overflow-y-auto bg-white p-5 transition-transform duration-300" role="dialog" aria-modal="true" aria-label="Mobile navigation" data-menu-panel>
            <div class="flex items-center justify-between border-b border-ink-200 pb-5">
                <img src="{{ asset('assets/logo.png') }}" alt="Selotemna" class="w-[4.5rem]" width="189" height="153">
                <button type="button" class="flex size-12 items-center justify-center rounded-xl border border-ink-200" aria-label="Close navigation" data-menu-close>
                    <svg aria-hidden="true" class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="m6 6 12 12M18 6 6 18" /></svg>
                </button>
            </div>

            <nav class="mt-6 flex flex-col" aria-label="Mobile navigation">
                <a href="{{ route('home') }}" class="mobile-nav-link" data-menu-link @if (request()->routeIs('home')) aria-current="page" @endif>Home</a>
                <a href="{{ route('about') }}" class="mobile-nav-link" data-menu-link @if (request()->routeIs('about')) aria-current="page" @endif>About</a>
                <p class="mt-5 text-xs font-bold uppercase tracking-[0.14em] text-brand-700">Divisions</p>
                <a href="{{ route('real-estate-development') }}" class="mobile-nav-link" data-menu-link @if (request()->routeIs('real-estate-development')) aria-current="page" @endif>Real Estate Development</a>
                <a href="{{ route('engineering-construction') }}" class="mobile-nav-link" data-menu-link @if (request()->routeIs('engineering-construction')) aria-current="page" @endif>Engineering &amp; Construction</a>
                @foreach (array_slice($navigation, 2) as $item)
                    <a href="{{ route($item['route']) }}" class="mobile-nav-link" data-menu-link @if ($item['active']) aria-current="page" @endif>{{ $item['label'] }}</a>
                @endforeach
            </nav>

            <div class="mt-auto space-y-3 border-t border-ink-200 pt-6">
                <x-site.button href="{{ route('inspections.create') }}" class="w-full" data-event="book_inspection_click" data-menu-link :aria-current="request()->routeIs('inspections.create') ? 'page' : null">Book an Inspection</x-site.button>
                @if ($contact['whatsapp_url'])
                    <x-site.button href="{{ $contact['whatsapp_url'] }}" variant="secondary" class="w-full" data-event="whatsapp_click" data-menu-link>Chat on WhatsApp</x-site.button>
                @endif
                @if ($contact['phone_url'])
                    <a href="{{ $contact['phone_url'] }}" class="flex min-h-12 items-center justify-center font-semibold text-brand-700" data-event="call_agent_click" data-menu-link>Call an Agent</a>
                @endif
            </div>
        </div>
    </div>
</header>
