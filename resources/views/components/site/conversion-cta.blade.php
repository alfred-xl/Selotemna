@props(['heading', 'intro', 'primaryLabel' => 'Book an Inspection', 'primaryHref' => null, 'secondaryLabel' => null, 'secondaryHref' => null])

<section class="bg-white pb-16 md:pb-20 lg:pb-28">
    <div class="site-container rounded-[1.5rem] bg-brand-700 px-6 py-14 text-white md:px-12 md:py-16 lg:px-20" data-reveal>
        <div class="max-w-3xl">
            <h2 class="text-[clamp(2rem,4vw,3rem)] font-semibold leading-tight !text-white">{{ $heading }}</h2>
            <p class="mt-5 max-w-2xl text-lg leading-8 text-white/75">{{ $intro }}</p>
            <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                <x-site.button :href="$primaryHref ?? route('inspections.create')" variant="reversed">{{ $primaryLabel }}</x-site.button>
                @if ($secondaryLabel && $secondaryHref)
                    <x-site.button :href="$secondaryHref" variant="outline-reversed">{{ $secondaryLabel }}</x-site.button>
                @endif
            </div>
            {{ $slot }}
        </div>
    </div>
</section>
