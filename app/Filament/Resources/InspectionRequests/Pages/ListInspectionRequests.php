<?php

namespace App\Filament\Resources\InspectionRequests\Pages;

use App\Filament\Resources\InspectionRequests\InspectionRequestResource;
use Filament\Resources\Pages\ListRecords;

class ListInspectionRequests extends ListRecords
{
    protected static string $resource = InspectionRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
