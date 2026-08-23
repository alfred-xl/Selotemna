@props([
    'items',
    'eyebrow' => 'Client experiences',
    'heading' => 'Relevant feedback from verified clients.',
    'intro' => null,
])

@if ($items)
    <section class="section-space bg-brand-50" data-testimonials>
        <div class="site-container">
            <x-site.section-heading :eyebrow="$eyebrow" :heading="$heading" :intro="$intro" />
            <div class="mt-10 grid gap-6 lg:grid-cols-3" data-reveal-group>
                @foreach ($items as $testimonial)
                    <figure class="flex h-full flex-col border-t-2 border-brand-700 bg-white p-6 md:p-8" data-reveal>
                        <blockquote class="text-lg leading-8 text-ink-800">
                            <p>“{{ $testimonial['quote'] }}”</p>
                        </blockquote>
                        <figcaption class="mt-8 border-t border-ink-200 pt-5">
                            <p class="font-semibold text-ink-950">{{ $testimonial['name'] }}</p>
                            @if (! empty($testimonial['context']))
                                <p class="mt-1 text-sm leading-6 text-ink-500">{{ $testimonial['context'] }}</p>
                            @endif
                            <p class="mt-3 text-xs font-bold uppercase tracking-[0.14em] text-brand-700">{{ $testimonial['service'] ?? $testimonial['division'] }}</p>
                        </figcaption>
                    </figure>
                @endforeach
            </div>
        </div>
    </section>
@endif
