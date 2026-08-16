@props(['contact'])

@php
    $navigation = [
        ['label' => 'Home', 'href' => route('home')],
        ['label' => 'About Us', 'href' => route('home').'#about'],
        ['label' => 'Properties', 'href' => route('home').'#omu-creek'],
        ['label' => 'Services', 'href' => route('home').'#services'],
        ['label' => 'Contact', 'href' => route('home').'#contact'],
    ];
@endphp

<header class="sticky top-0 z-40 border-b border-ink-200 bg-white/95" data-site-header>
    <div class="site-container-wide flex min-h-20 items-center justify-between gap-6">
        <a href="{{ route('home') }}" class="flex min-h-11 items-center" aria-label="Selotemna home">
            <img src="{{ asset('assets/logo.png') }}" alt="Selotemna, RC 7361086" class="w-[4.5rem] shrink-0 md:w-20" width="189" height="153">
        </a>

        <nav class="hidden items-center gap-6 lg:flex" aria-label="Primary navigation">
            @foreach ($navigation as $item)
                <a
                    href="{{ $item['href'] }}"
                    @class([
                        'inline-flex min-h-11 items-center text-sm font-semibold transition-colors hover:text-brand-700',
                        'text-brand-700' => $loop->first,
                        'text-ink-800' => ! $loop->first,
                    ])
                    @if ($loop->first) aria-current="page" @endif
                >{{ $item['label'] }}</a>
            @endforeach
        </nav>

        <div class="hidden items-center gap-3 lg:flex">
            @if ($contact['phone_url'])
                <a href="{{ $contact['phone_url'] }}" class="inline-flex min-h-11 items-center gap-2 px-2 text-sm font-semibold text-ink-950 hover:text-brand-700" data-event="call_agent_click">
                    <svg aria-hidden="true" class="size-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.79 19.79 0 0 1 2.12 4.18 2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.12.9.33 1.78.62 2.63a2 2 0 0 1-.45 2.11L8 9.73a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.85.29 1.73.5 2.63.62A2 2 0 0 1 22 16.92z" />
                    </svg>
                    Call an Agent
                </a>
            @endif
            <x-site.button href="{{ route('home').'#inspection-process' }}" data-event="book_inspection_click">
                Book an Inspection
            </x-site.button>
        </div>

        <button
            type="button"
            class="flex size-12 items-center justify-center rounded-xl border border-ink-200 text-ink-950 lg:hidden"
            aria-label="Open navigation"
            aria-expanded="false"
            aria-controls="mobile-navigation"
            data-menu-open
        >
            <svg aria-hidden="true" class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                <path d="M4 7h16M4 12h16M4 17h16" />
            </svg>
        </button>
    </div>

    <div class="fixed inset-0 z-50 hidden lg:hidden" aria-hidden="true" data-menu-root>
        <button type="button" class="absolute inset-0 bg-ink-950/60" aria-label="Close navigation" tabindex="-1" data-menu-backdrop></button>
        <div id="mobile-navigation" class="absolute right-0 top-0 flex h-full w-[min(88vw,24rem)] translate-x-full flex-col bg-white p-5 transition-transform duration-300" role="dialog" aria-modal="true" aria-label="Mobile navigation" data-menu-panel>
            <div class="flex items-center justify-between border-b border-ink-200 pb-5">
                <img src="{{ asset('assets/logo.png') }}" alt="Selotemna" class="w-[4.5rem]" width="189" height="153">
                <button type="button" class="flex size-12 items-center justify-center rounded-xl border border-ink-200" aria-label="Close navigation" data-menu-close>
                    <svg aria-hidden="true" class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                        <path d="m6 6 12 12M18 6 6 18" />
                    </svg>
                </button>
            </div>

            <nav class="mt-7 flex flex-col" aria-label="Mobile navigation">
                @foreach ($navigation as $item)
                    <a href="{{ $item['href'] }}" class="flex min-h-12 items-center border-b border-ink-200 py-3 font-semibold text-ink-950" data-menu-link>{{ $item['label'] }}</a>
                @endforeach
            </nav>

            <div class="mt-auto space-y-3 border-t border-ink-200 pt-6">
                <x-site.button href="{{ route('home').'#inspection-process' }}" class="w-full" data-event="book_inspection_click" data-menu-link>
                    Book an Inspection
                </x-site.button>
                @if ($contact['whatsapp_url'])
                    <x-site.button href="{{ $contact['whatsapp_url'] }}" variant="secondary" class="w-full" data-event="whatsapp_click" data-menu-link>
                        Chat on WhatsApp
                    </x-site.button>
                @endif
                @if ($contact['phone_url'])
                    <a href="{{ $contact['phone_url'] }}" class="flex min-h-12 items-center justify-center font-semibold text-brand-700" data-event="call_agent_click" data-menu-link>Call an Agent</a>
                @endif
            </div>
        </div>
    </div>
</header>
