@props(['groups', 'idPrefix' => 'projects'])

<div data-project-tabs>
    @if (count($groups) > 1)
        <div class="-mx-1 overflow-x-auto px-1 pb-1" data-project-tab-scroll>
            <div class="flex min-w-max border-b border-ink-200" role="tablist" aria-label="Project categories">
                @foreach ($groups as $key => $group)
                    <button type="button" id="{{ $idPrefix }}-tab-{{ $key }}" class="project-tab min-h-12 shrink-0 whitespace-nowrap px-5 py-3 text-sm font-semibold text-ink-500" role="tab" aria-selected="{{ $loop->first ? 'true' : 'false' }}" aria-controls="{{ $idPrefix }}-panel-{{ $key }}" tabindex="{{ $loop->first ? '0' : '-1' }}" data-project-tab data-motion-button>{{ $group['label'] }}</button>
                @endforeach
            </div>
        </div>
    @endif

    <div @class(['mt-8' => count($groups) > 1])>
        @foreach ($groups as $key => $group)
            <div id="{{ $idPrefix }}-panel-{{ $key }}" class="project-panel" @if (count($groups) > 1) role="tabpanel" aria-labelledby="{{ $idPrefix }}-tab-{{ $key }}" data-project-panel @endif>
                <h3 class="project-panel-heading mb-6 text-xl font-semibold">{{ $group['label'] }}</h3>
                <div class="grid max-w-3xl gap-6 md:grid-cols-2" data-reveal-group>
                    @foreach ($group['items'] as $project) <x-site.project-card :project="$project" /> @endforeach
                </div>
            </div>
        @endforeach
    </div>
</div>
