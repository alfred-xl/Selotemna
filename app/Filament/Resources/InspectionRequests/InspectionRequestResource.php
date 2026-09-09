<?php

namespace App\Filament\Resources\InspectionRequests;

use App\Filament\Resources\InspectionRequests\Pages\EditInspectionRequest;
use App\Filament\Resources\InspectionRequests\Pages\ListInspectionRequests;
use App\Filament\Resources\InspectionRequests\Pages\ViewInspectionRequest;
use App\Filament\Resources\InspectionRequests\Schemas\InspectionRequestForm;
use App\Filament\Resources\InspectionRequests\Schemas\InspectionRequestInfolist;
use App\Filament\Resources\InspectionRequests\Tables\InspectionRequestsTable;
use App\Models\InspectionRequest;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class InspectionRequestResource extends Resource
{
    protected static ?string $model = InspectionRequest::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDays;

    protected static string|UnitEnum|null $navigationGroup = 'Lead management';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'reference';

    public static function getNavigationBadge(): ?string
    {
        $count = InspectionRequest::query()->where('status', InspectionRequest::STATUS_NEW)->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }

    public static function canDeleteAny(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return InspectionRequestForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return InspectionRequestInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return InspectionRequestsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListInspectionRequests::route('/'),
            'view' => ViewInspectionRequest::route('/{record}'),
            'edit' => EditInspectionRequest::route('/{record}/edit'),
        ];
    }
}
