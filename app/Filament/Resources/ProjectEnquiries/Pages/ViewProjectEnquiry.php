<?php

namespace App\Filament\Resources\ProjectEnquiries\Pages;

use App\Filament\Resources\PaymentReceipts\PaymentReceiptResource;
use App\Filament\Resources\ProjectEnquiries\ProjectEnquiryResource;
use App\Models\ProjectEnquiry;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewProjectEnquiry extends ViewRecord
{
    protected static string $resource = ProjectEnquiryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('generateReceipt')
                ->label('Generate e-receipt')
                ->icon('heroicon-o-document-currency-dollar')
                ->url(fn (ProjectEnquiry $record): string => PaymentReceiptResource::getUrl('create', ['project_enquiry' => $record->getKey()])),
            EditAction::make(),
        ];
    }
}
