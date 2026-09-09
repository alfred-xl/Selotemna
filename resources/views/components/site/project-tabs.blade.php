@props([
    'groups',
    'idPrefix' => 'projects',
    'featured' => false,
    'inspectionHref' => null,
    'stageNote' => null,
    'emptyStates' => [],
])

<div data-project-tabs>
    @if (count($groups) > 1)
        <div class="border-b border-ink-200" data-project-tab-scroll>
            <div class="grid grid-cols-3" role="tablist" aria-label="Project stages">
                @foreach ($groups as $key => $group)
                    <button type="button" id="{{ $idPrefix }}-tab-{{ $key }}" class="project-tab flex min-h-12 min-w-0 items-center justify-center px-1.5 py-3 text-[0.8125rem] font-semibold text-ink-800 focus-visible:ring-inset focus-visible:ring-offset-0 sm:px-5 sm:text-sm" role="tab" aria-selected="{{ $loop->first ? 'true' : 'false' }}" aria-controls="{{ $idPrefix }}-panel-{{ $key }}" tabindex="{{ $loop->first ? '0' : '-1' }}" data-project-tab data-motion-button>{{ $group['label'] }}</button>
                @endforeach
            </div>
        </div>
    @endif

    <div @class(['mt-8' => count($groups) > 1])>
        @foreach ($groups as $key => $group)
            <div id="{{ $idPrefix }}-panel-{{ $key }}" class="project-panel" @if (count($groups) > 1) role="tabpanel" aria-labelledby="{{ $idPrefix }}-tab-{{ $key }}" data-project-panel @endif>
                <h3 class="project-panel-heading mb-6 text-base font-semibold text-ink-800">{{ $group['label'] }}</h3>
                @if ($group['items'])
                    <div @class(['grid gap-6 md:grid-cols-2' => ! $featured, 'grid' => $featured]) data-reveal-group>
                        @foreach ($group['items'] as $project)
                            <x-site.project-card
                                :project="$project"
                                :featured="$featured && $key === 'upcoming' && $loop->first"
                                :inspection-href="$inspectionHref"
                                :stage-note="$stageNote"
                            />
                        @endforeach
                    </div>
                @else
                    @php
                        $emptyState = $emptyStates[$key] ?? [
                            'heading' => 'No project profiles are currently published.',
                            'description' => 'New project information will appear here when it has been approved for public release.',
                        ];
                    @endphp
                    <div class="border-y border-ink-200 py-9 md:py-10" data-project-empty-state>
                        <p class="font-display text-xl font-semibold leading-tight text-ink-950 md:text-2xl">{{ $emptyState['heading'] }}</p>
                        <p class="mt-3 max-w-3xl leading-7 text-ink-500">{{ $emptyState['description'] }}</p>
                    </div>
                @endif
            </div>
        @endforeach
    </div>
</div>
