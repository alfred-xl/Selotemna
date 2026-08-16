@props(['contact'])

<nav class="fixed inset-x-0 bottom-0 z-30 border-t border-ink-200 bg-white px-3 pb-[max(0.75rem,env(safe-area-inset-bottom))] pt-3 shadow-[0_-8px_24px_rgba(10,10,11,0.08)] transition-transform lg:hidden" aria-label="Quick contact" data-mobile-contact-bar>
    <div class="mx-auto flex max-w-md items-center gap-2">
        <a href="{{ route('home').'#inspection-process' }}" class="flex min-h-12 flex-1 items-center justify-center rounded-xl bg-brand-700 px-3 text-sm font-semibold text-white hover:bg-brand-800" data-event="book_inspection_click">
            Book Inspection
        </a>
        @if ($contact['whatsapp_url'])
            <a href="{{ $contact['whatsapp_url'] }}" class="flex size-12 shrink-0 items-center justify-center rounded-xl border border-ink-200 text-brand-700 hover:bg-brand-50" aria-label="Chat on WhatsApp" data-event="whatsapp_click">
                <svg aria-hidden="true" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 11.5a8.4 8.4 0 0 1-9 8.5 9.5 9.5 0 0 1-4-.9L3 21l1.7-4.7A8.5 8.5 0 1 1 21 11.5Z" />
                    <path d="M8.6 8.3c.2 3 2.1 5 5.1 5.8" />
                </svg>
            </a>
        @endif
        @if ($contact['phone_url'])
            <a href="{{ $contact['phone_url'] }}" class="flex size-12 shrink-0 items-center justify-center rounded-xl border border-ink-200 text-brand-700 hover:bg-brand-50" aria-label="Call an Agent" data-event="call_agent_click">
                <svg aria-hidden="true" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.79 19.79 0 0 1 2.12 4.18 2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.12.9.33 1.78.62 2.63a2 2 0 0 1-.45 2.11L8 9.73a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.85.29 1.73.5 2.63.62A2 2 0 0 1 22 16.92z" />
                </svg>
            </a>
        @endif
    </div>
</nav>
