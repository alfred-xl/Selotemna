@props(['groups', 'idPrefix' => 'projects'])

<div data-project-tabs>
    <div class="-mx-1 overflow-x-auto px-1 pb-1" data-project-tab-scroll>
        <div class="flex min-w-max border-b border-ink-200" role="tablist" aria-label="Project categories">
            @foreach ($groups as $key => $group)
                <button type="button" id="{{ $idPrefix }}-tab-{{ $key }}" class="project-tab min-h-12 shrink-0 whitespace-nowrap px-5 py-3 text-sm font-semibold text-ink-500" role="tab" aria-selected="{{ $loop->first ? 'true' : 'false' }}" aria-controls="{{ $idPrefix }}-panel-{{ $key }}" tabindex="{{ $loop->first ? '0' : '-1' }}" data-project-tab>{{ $group['label'] }}</button>
            @endforeach
        </div>
    </div>

    <div class="mt-8">
        @foreach ($groups as $key => $group)
            <div id="{{ $idPrefix }}-panel-{{ $key }}" class="project-panel" role="tabpanel" aria-labelledby="{{ $idPrefix }}-tab-{{ $key }}" data-project-panel>
                <h3 class="project-panel-heading mb-6 text-xl font-semibold">{{ $group['label'] }}</h3>
                @if ($group['items'])
                    <div class="grid gap-6 md:grid-cols-2">
                        @foreach ($group['items'] as $project) <x-site.project-card :project="$project" /> @endforeach
                    </div>
                @else
                    <div class="rounded-[1.25rem] border border-ink-200 bg-ink-50 px-6 py-10 text-center" data-project-empty-state>
                        <p class="mx-auto max-w-2xl leading-7 text-ink-500">{{ $group['empty_message'] }}</p>
                        <a href="{{ route('contact') }}" class="text-link mt-4">Discuss a project requirement</a>
                    </div>
                @endif
            </div>
        @endforeach
    </div>
</div>
