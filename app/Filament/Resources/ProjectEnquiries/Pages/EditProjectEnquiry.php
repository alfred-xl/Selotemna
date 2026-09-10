<?php

namespace App\Filament\Resources\ProjectEnquiries\Pages;

use App\Filament\Resources\PaymentReceipts\PaymentReceiptResource;
use App\Filament\Resources\ProjectEnquiries\ProjectEnquiryResource;
use App\Models\ProjectEnquiry;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditProjectEnquiry extends EditRecord
{
    protected static string $resource = ProjectEnquiryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('generateReceipt')
                ->label('Generate e-receipt')
                ->icon('heroicon-o-document-currency-dollar')
                ->url(fn (ProjectEnquiry $record): string => PaymentReceiptResource::getUrl('create', ['project_enquiry' => $record->getKey()])),
            ViewAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (($data['status'] ?? null) !== ProjectEnquiry::STATUS_NEW && blank($data['handled_at'] ?? null)) {
            $data['handled_at'] = now();
        }

        return $data;
    }
}
