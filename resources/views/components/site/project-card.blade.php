@props(['project'])

<article class="overflow-hidden rounded-[1.25rem] border border-ink-200 bg-white" data-project-card>
    <div class="relative aspect-[16/9] overflow-hidden border-b border-ink-200 bg-brand-950" aria-hidden="true">
        <div class="absolute -left-10 -top-16 size-44 rounded-full border border-white/15"></div>
        <div class="absolute -bottom-20 right-6 size-52 rounded-full border border-white/10"></div>
        <div class="absolute inset-x-8 bottom-8 h-px bg-white/20"></div>
        <svg class="absolute bottom-8 left-8 size-11 text-brand-100" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.35" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21h18" /><path d="M5 21V8l7-5 7 5v13" /><path d="M9 21v-6h6v6" /></svg>
    </div>
    <div class="p-6 md:p-7">
        @if (! empty($project['is_temporary'])) <p class="mb-3 text-xs font-bold uppercase tracking-[0.14em] text-ink-500">Development-only layout sample</p> @endif
        <p class="text-xs font-bold uppercase tracking-[0.14em] text-brand-700">{{ $project['status'] }}</p>
        <h3 class="mt-3 text-xl font-semibold leading-snug md:text-2xl">{{ $project['name'] }}</h3>
        <p class="mt-3 text-sm font-semibold text-ink-800">{{ $project['division'] }}</p>
        <p class="mt-4 leading-7 text-ink-500">{{ $project['summary'] }}</p>
    </div>
</article>
