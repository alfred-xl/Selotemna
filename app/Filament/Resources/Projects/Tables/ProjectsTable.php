<?php

namespace App\Filament\Resources\Projects\Tables;

use App\Enums\ProjectDivision;
use App\Enums\ProjectPublicationStatus;
use App\Enums\ProjectStatus;
use App\Filament\Actions\PreviewProjectAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class ProjectsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->description(fn ($record): ?string => $record->location_summary),
                TextColumn::make('division')
                    ->badge()
                    ->formatStateUsing(fn (ProjectDivision $state): string => $state->value)
                    ->toggleable(),
                TextColumn::make('status')
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
                    ->sortable(),
                IconColumn::make('is_featured')->label('Featured')->boolean()->sortable(),
                IconColumn::make('is_publication_verified')->label('Verified')->boolean()->sortable(),
                TextColumn::make('published_at')->dateTime('j M Y, g:i a')->sortable()->toggleable(),
                TextColumn::make('updated_at')->since()->sortable()->toggleable(isToggledHiddenByDefault: true),
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
                PreviewProjectAction::make(),
                ViewAction::make(),
                EditAction::make(),
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
