<?php

namespace App\Filament\Resources\PaymentReceipts\Pages;

use App\Filament\Resources\PaymentReceipts\PaymentReceiptResource;
use App\Models\PaymentReceipt;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditPaymentReceipt extends EditRecord
{
    protected static string $resource = PaymentReceiptResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('previewReceipt')
                ->label('Preview receipt')
                ->icon('heroicon-o-eye')
                ->url(fn (PaymentReceipt $record): string => route('admin.e-receipts.preview', $record))
                ->openUrlInNewTab(),
            ViewAction::make(),
        ];
    }
}
