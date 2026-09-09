<?php

namespace App\Filament\Resources\InspectionRequests\Pages;

use App\Filament\Resources\InspectionRequests\InspectionRequestResource;
use App\Models\InspectionRequest;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditInspectionRequest extends EditRecord
{
    protected static string $resource = InspectionRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (($data['status'] ?? null) !== InspectionRequest::STATUS_NEW && blank($data['handled_at'] ?? null)) {
            $data['handled_at'] = now();
        }

        return $data;
    }
}
