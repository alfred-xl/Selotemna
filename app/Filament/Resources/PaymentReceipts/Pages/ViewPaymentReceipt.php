<?php

namespace App\Filament\Resources\PaymentReceipts\Pages;

use App\Filament\Resources\PaymentReceipts\PaymentReceiptResource;
use App\Models\PaymentReceipt;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewPaymentReceipt extends ViewRecord
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
            Action::make('downloadReceipt')
                ->label('Download PDF')
                ->icon('heroicon-o-arrow-down-tray')
                ->url(fn (PaymentReceipt $record): string => route('admin.e-receipts.download', $record))
                ->openUrlInNewTab()
                ->visible(fn (PaymentReceipt $record): bool => ! $record->isDraft()),
            Action::make('issueReceipt')
                ->label('Issue receipt')
                ->icon('heroicon-o-check-badge')
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading('Issue this confirmed-payment receipt?')
                ->modalDescription('Only continue after Selotemna has independently confirmed that this payment was received. Issued receipt details become read-only.')
                ->action(function (PaymentReceipt $record): void {
                    /** @var User $administrator */
                    $administrator = auth()->user();
                    $record->issue($administrator);

                    Notification::make()->success()->title('Receipt issued')->body($record->receipt_number)->send();
                    $this->redirect(PaymentReceiptResource::getUrl('view', ['record' => $record]));
                })
                ->visible(fn (PaymentReceipt $record): bool => $record->isDraft()),
            Action::make('voidReceipt')
                ->label('Void receipt')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->requiresConfirmation()
                ->schema([
                    Textarea::make('reason')->label('Reason for voiding')->required()->maxLength(2000),
                ])
                ->action(function (PaymentReceipt $record, array $data): void {
                    /** @var User $administrator */
                    $administrator = auth()->user();
                    $record->void($administrator, $data['reason']);

                    Notification::make()->success()->title('Receipt voided')->send();
                    $this->redirect(PaymentReceiptResource::getUrl('view', ['record' => $record]));
                })
                ->visible(fn (PaymentReceipt $record): bool => $record->isIssued()),
            EditAction::make()->visible(fn (PaymentReceipt $record): bool => $record->isDraft()),
        ];
    }
}
