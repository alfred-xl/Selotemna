<?php

namespace App\Filament\Resources\PaymentReceipts\Pages;

use App\Filament\Resources\PaymentReceipts\PaymentReceiptResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPaymentReceipts extends ListRecords
{
    protected static string $resource = PaymentReceiptResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()->label('Create receipt')];
    }
}
