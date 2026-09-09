<?php

namespace App\Filament\Resources\ContactEnquiries\Tables;

use App\Models\ContactEnquiry;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ContactEnquiriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('reference')->searchable()->copyable()->weight('medium'),
                TextColumn::make('full_name')->label('Contact')->searchable()->description(fn ($record): string => $record->phone),
                TextColumn::make('enquiry_type')->badge()->searchable(),
                TextColumn::make('interest_type')->label('Interested in')->badge()->placeholder('Not specified')->toggleable(),
                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => ContactEnquiry::statusOptions()[$state] ?? str($state)->title()->toString())
                    ->color(fn (string $state): string => match ($state) {
                        ContactEnquiry::STATUS_NEW => 'warning',
                        ContactEnquiry::STATUS_CONTACTED => 'info',
                        ContactEnquiry::STATUS_CLOSED => 'success',
                        default => 'gray',
                    })
                    ->sortable(),
                TextColumn::make('created_at')->label('Received')->dateTime('j M Y, g:i a')->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')->options(ContactEnquiry::statusOptions()),
                SelectFilter::make('enquiry_type')->options([
                    'General enquiry' => 'General enquiry',
                    'Real estate' => 'Real estate',
                    'Engineering' => 'Engineering',
                ]),
                SelectFilter::make('interest_type')->options([
                    'Land' => 'Land',
                    'Building' => 'Building',
                    'Both' => 'Both',
                ]),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
