<?php

namespace App\Filament\Resources\Projects\Tables;

use App\Enums\ProjectDivision;
use App\Enums\ProjectPublicationStatus;
use App\Enums\ProjectStatus;
use App\Filament\Actions\PreviewProjectAction;
use App\Filament\Resources\ProjectEnquiries\ProjectEnquiryResource;
use App\Filament\Resources\Projects\ProjectResource;
use App\Models\Project;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;

class ProjectsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Project')
                    ->searchable()
                    ->sortable()
                    ->description(function (Project $record): HtmlString {
                        $division = e($record->division->value);
                        $location = filled($record->location_summary)
                            ? '<span class="hidden sm:inline"> &middot; '.e($record->location_summary).'</span>'
                            : '';

                        return new HtmlString("<span>{$division}{$location}</span>");
                    })
                    ->wrap()
                    ->grow(),
                TextColumn::make('status')
                    ->label('Stage')
                    ->badge()
                    ->formatStateUsing(fn (ProjectStatus $state): string => $state->label())
                    ->color(fn (ProjectStatus $state): string => match ($state) {
                        ProjectStatus::Upcoming => 'info',
                        ProjectStatus::Ongoing => 'warning',
                        ProjectStatus::Completed => 'success',
                    })
                    ->sortable(),
                TextColumn::make('publication_status')
                    ->label('Publishing')
                    ->badge()
                    ->formatStateUsing(fn (ProjectPublicationStatus $state): string => $state->label())
                    ->color(fn (ProjectPublicationStatus $state): string => $state === ProjectPublicationStatus::Published ? 'success' : 'gray')
                    ->description(function (Project $record): ?HtmlString {
                        if (! $record->published_at) {
                            return null;
                        }

                        $prefix = match (true) {
                            $record->published_at->isFuture() => 'Scheduled for',
                            $record->publication_status === ProjectPublicationStatus::Published => 'Published',
                            default => 'Publication date',
                        };

                        return new HtmlString('<span class="hidden sm:inline">'.e($prefix.' '.$record->published_at->format('j M Y, g:i a')).'</span>');
                    })
                    ->sortable(),
                IconColumn::make('is_featured')
                    ->label('Featured')
                    ->boolean()
                    ->tooltip(fn (bool $state): string => $state ? 'Featured project' : 'Not a featured project')
                    ->sortable()
                    ->visibleFrom('sm'),
            ])
            ->filters([
                SelectFilter::make('status')->options([
                    ProjectStatus::Upcoming->value => 'Upcoming',
                    ProjectStatus::Ongoing->value => 'Ongoing',
                    ProjectStatus::Completed->value => 'Completed',
                ]),
                SelectFilter::make('division')->options([
                    ProjectDivision::RealEstateDevelopment->value => 'Real Estate Development',
                    ProjectDivision::EngineeringConstruction->value => 'Engineering & Construction',
                ]),
                SelectFilter::make('publication_status')->label('Publishing')->options([
                    ProjectPublicationStatus::Draft->value => 'Draft',
                    ProjectPublicationStatus::Published->value => 'Published',
                ]),
                TernaryFilter::make('is_featured')->label('Featured project'),
                TrashedFilter::make(),
            ])
            ->recordActions([
                ActionGroup::make([
                    Action::make('viewProject')
                        ->label('View project')
                        ->icon('heroicon-o-eye')
                        ->url(fn (Project $record): string => ProjectResource::getUrl('view', ['record' => $record])),
                    Action::make('editProject')
                        ->label('Edit project')
                        ->icon('heroicon-o-pencil-square')
                        ->url(fn (Project $record): string => ProjectResource::getUrl('edit', ['record' => $record])),
                    PreviewProjectAction::make(),
                    Action::make('viewProjectEnquiries')
                        ->label('View project enquiries')
                        ->icon('heroicon-o-chat-bubble-left-right')
                        ->url(fn (): string => ProjectEnquiryResource::getUrl('index')),
                ])
                    ->label('Actions')
                    ->icon('heroicon-o-ellipsis-horizontal-circle')
                    ->button()
                    ->dropdownPlacement('bottom-end')
                    ->visible(fn (Project $record): bool => ! $record->trashed()),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ])
            ->defaultSort('updated_at', 'desc');
    }
}
