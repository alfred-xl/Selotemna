<?php

namespace App\Filament\Resources\PaymentReceipts\Pages;

use App\Filament\Resources\PaymentReceipts\PaymentReceiptResource;
use App\Models\PaymentReceipt;
use App\Models\ProjectEnquiry;
use Filament\Resources\Pages\CreateRecord;

class CreatePaymentReceipt extends CreateRecord
{
    protected static string $resource = PaymentReceiptResource::class;

    protected static bool $canCreateAnother = false;

    protected function afterFill(): void
    {
        $enquiryId = request()->integer('project_enquiry');
        $enquiry = $enquiryId ? ProjectEnquiry::query()->find($enquiryId) : null;

        if ($enquiry) {
            $this->form->fill([
                ...$this->form->getRawState(),
                ...PaymentReceiptResource::formDataFromEnquiry($enquiry),
            ]);
        }
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['status'] = PaymentReceipt::STATUS_DRAFT;
        $data['created_by'] = auth()->id();

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return PaymentReceiptResource::getUrl('view', ['record' => $this->record]);
    }
}
