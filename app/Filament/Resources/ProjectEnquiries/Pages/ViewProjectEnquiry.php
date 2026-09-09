<?php

namespace App\Filament\Resources\ProjectEnquiries\Pages;

use App\Filament\Resources\ProjectEnquiries\ProjectEnquiryResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewProjectEnquiry extends ViewRecord
{
    protected static string $resource = ProjectEnquiryResource::class;

    protected function getHeaderActions(): array
    {
        return [EditAction::make()];
    }
}
