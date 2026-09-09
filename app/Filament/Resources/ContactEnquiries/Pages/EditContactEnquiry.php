<?php

namespace App\Filament\Resources\ContactEnquiries\Pages;

use App\Filament\Resources\ContactEnquiries\ContactEnquiryResource;
use App\Models\ContactEnquiry;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditContactEnquiry extends EditRecord
{
    protected static string $resource = ContactEnquiryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (($data['status'] ?? null) !== ContactEnquiry::STATUS_NEW && blank($data['handled_at'] ?? null)) {
            $data['handled_at'] = now();
        }

        return $data;
    }
}
