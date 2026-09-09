<?php

namespace App\Filament\Resources\InspectionRequests\Pages;

use App\Filament\Resources\InspectionRequests\InspectionRequestResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewInspectionRequest extends ViewRecord
{
    protected static string $resource = InspectionRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
