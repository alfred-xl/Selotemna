<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\ContactEnquiries\ContactEnquiryResource;
use App\Models\ContactEnquiry;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class LatestContactEnquiries extends TableWidget
{
    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->heading('Latest contact enquiries')
            ->query(fn (): Builder => ContactEnquiry::query()->latest()->limit(5))
            ->columns([
                TextColumn::make('reference')->copyable(),
                TextColumn::make('full_name')->label('Contact')->description(fn (ContactEnquiry $record): string => $record->phone),
                TextColumn::make('enquiry_type')->badge(),
                TextColumn::make('interest_type')->label('Interested in')->badge()->placeholder('Not specified'),
                TextColumn::make('status')->badge()->color(fn (string $state): string => match ($state) {
                    ContactEnquiry::STATUS_NEW => 'warning',
                    ContactEnquiry::STATUS_CONTACTED => 'info',
                    ContactEnquiry::STATUS_CLOSED => 'success',
                    default => 'gray',
                }),
                TextColumn::make('created_at')->label('Received')->since(),
            ])
            ->headerActions([
                Action::make('viewAll')->label('View all')->url(ContactEnquiryResource::getUrl('index')),
            ])
            ->recordUrl(fn (ContactEnquiry $record): string => ContactEnquiryResource::getUrl('view', ['record' => $record]))
            ->paginated(false);
    }
}
