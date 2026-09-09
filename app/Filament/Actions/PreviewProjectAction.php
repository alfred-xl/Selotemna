<?php

namespace App\Filament\Actions;

use App\Models\Project;
use Filament\Actions\Action;
use Illuminate\Support\Facades\URL;

final class PreviewProjectAction
{
    public static function make(): Action
    {
        return Action::make('previewPublicPage')
            ->label('Preview public page')
            ->icon('heroicon-o-arrow-top-right-on-square')
            ->url(fn (Project $record): string => self::url($record))
            ->openUrlInNewTab()
            ->visible(fn (Project $record): bool => ! $record->trashed());
    }

    public static function url(Project $project): string
    {
        $route = $project->slug === 'omu-creek' ? 'omu-creek' : 'projects.show';
        $parameters = ['preview' => $project->getKey()];

        if ($route === 'projects.show') {
            $parameters['slug'] = $project->slug;
        }

        return URL::temporarySignedRoute($route, now()->addMinutes(30), $parameters);
    }
}
