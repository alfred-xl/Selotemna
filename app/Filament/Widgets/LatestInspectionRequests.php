<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\InspectionRequests\InspectionRequestResource;
use App\Models\InspectionRequest;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class LatestInspectionRequests extends TableWidget
{
    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->heading('Latest inspection requests')
            ->query(fn (): Builder => InspectionRequest::query()->latest()->limit(5))
            ->columns([
                TextColumn::make('reference')->copyable(),
                TextColumn::make('full_name')->label('Contact')->description(fn (InspectionRequest $record): string => $record->phone),
                TextColumn::make('project_name'),
                TextColumn::make('preferred_date')->date('j M Y')->placeholder('Flexible'),
                TextColumn::make('status')->badge()->color(fn (string $state): string => match ($state) {
                    InspectionRequest::STATUS_NEW => 'warning',
                    InspectionRequest::STATUS_CONTACTED => 'info',
                    InspectionRequest::STATUS_CLOSED => 'success',
                    default => 'gray',
                }),
                TextColumn::make('created_at')->label('Received')->since(),
            ])
            ->headerActions([
                Action::make('viewAll')->label('View all')->url(InspectionRequestResource::getUrl('index')),
            ])
            ->recordUrl(fn (InspectionRequest $record): string => InspectionRequestResource::getUrl('view', ['record' => $record]))
            ->paginated(false);
    }
}
