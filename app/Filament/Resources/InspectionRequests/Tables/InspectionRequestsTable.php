<?php

namespace App\Filament\Resources\InspectionRequests\Tables;

use App\Models\InspectionRequest;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class InspectionRequestsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('reference')->searchable()->copyable()->weight('medium'),
                TextColumn::make('full_name')->label('Contact')->searchable()->description(fn ($record): string => $record->phone),
                TextColumn::make('project_name')->searchable()->toggleable(),
                TextColumn::make('preferred_date')->date('j M Y')->placeholder('Flexible')->sortable(),
                TextColumn::make('preferred_time')->placeholder('Flexible')->toggleable(),
                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => InspectionRequest::statusOptions()[$state] ?? str($state)->title()->toString())
                    ->color(fn (string $state): string => match ($state) {
                        InspectionRequest::STATUS_NEW => 'warning',
                        InspectionRequest::STATUS_CONTACTED => 'info',
                        InspectionRequest::STATUS_CLOSED => 'success',
                        default => 'gray',
                    })
                    ->sortable(),
                TextColumn::make('created_at')->label('Received')->dateTime('j M Y, g:i a')->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')->options(InspectionRequest::statusOptions()),
                SelectFilter::make('preferred_contact_method')->options([
                    'phone' => 'Phone',
                    'whatsapp' => 'WhatsApp',
                    'email' => 'Email',
                ]),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
