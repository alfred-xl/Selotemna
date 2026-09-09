<?php

namespace App\Filament\Resources\ProjectEnquiries\Pages;

use App\Filament\Resources\ProjectEnquiries\ProjectEnquiryResource;
use Filament\Resources\Pages\ListRecords;

class ListProjectEnquiries extends ListRecords
{
    protected static string $resource = ProjectEnquiryResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
