<?php

namespace App\Filament\Resources\PaymentReceipts;

use App\Filament\Resources\PaymentReceipts\Pages\CreatePaymentReceipt;
use App\Filament\Resources\PaymentReceipts\Pages\EditPaymentReceipt;
use App\Filament\Resources\PaymentReceipts\Pages\ListPaymentReceipts;
use App\Filament\Resources\PaymentReceipts\Pages\ViewPaymentReceipt;
use App\Models\PaymentReceipt;
use App\Models\Project;
use App\Models\ProjectEnquiry;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class PaymentReceiptResource extends Resource
{
    protected static ?string $model = PaymentReceipt::class;

    protected static ?string $slug = 'e-receipts';

    protected static ?string $modelLabel = 'e-receipt';

    protected static ?string $pluralModelLabel = 'e-receipts';

    protected static ?string $navigationLabel = 'E-Receipts';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentCurrencyDollar;

    protected static string|UnitEnum|null $navigationGroup = 'Payment management';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'receipt_number';

    public static function canDelete(Model $record): bool
    {
        return false;
    }

    public static function canDeleteAny(): bool
    {
        return false;
    }

    public static function canEdit(Model $record): bool
    {
        return parent::canEdit($record) && $record instanceof PaymentReceipt && $record->isDraft();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Receipt source')
                ->description('Link an enquiry to prefill the customer and project details, or enter them manually.')
                ->columns(2)
                ->schema([
                    Select::make('project_enquiry_id')
                        ->label('Project enquiry')
                        ->relationship('projectEnquiry', 'reference')
                        ->getOptionLabelFromRecordUsing(fn (ProjectEnquiry $record): string => $record->reference.' — '.$record->full_name)
                        ->searchable(['reference', 'full_name', 'phone'])
                        ->preload()
                        ->live()
                        ->afterStateUpdated(function (mixed $state, Set $set): void {
                            $enquiry = filled($state) ? ProjectEnquiry::query()->find($state) : null;

                            if (! $enquiry) {
                                return;
                            }

                            foreach (self::formDataFromEnquiry($enquiry) as $field => $value) {
                                $set($field, $value);
                            }
                        })
                        ->columnSpanFull(),
                    Hidden::make('project_slug'),
                    Select::make('project_id')
                        ->label('Project record')
                        ->relationship('project', 'name')
                        ->searchable()
                        ->preload()
                        ->live()
                        ->afterStateUpdated(function (mixed $state, Set $set): void {
                            $project = filled($state) ? Project::query()->find($state) : null;

                            if ($project) {
                                $set('project_name', $project->name);
                                $set('project_slug', $project->slug);
                            }
                        }),
                    TextInput::make('project_name')->required()->maxLength(160),
                    TextInput::make('plot_size_sqm')->label('Plot size')->numeric()->integer()->minValue(1)->suffix('sqm'),
                    TextInput::make('plot_label')->label('Plot description')->maxLength(120),
                ]),
            Section::make('Customer')
                ->columns(2)
                ->schema([
                    TextInput::make('customer_name')->required()->maxLength(120),
                    TextInput::make('customer_phone')->tel()->required()->maxLength(40),
                    TextInput::make('customer_email')->email()->maxLength(160)->columnSpanFull(),
                ]),
            Section::make('Confirmed payment')
                ->description('Only enter funds that Selotemna has independently confirmed as received.')
                ->columns(2)
                ->schema([
                    Select::make('currency')->options(['NGN' => 'Nigerian naira (NGN)'])->default('NGN')->required(),
                    TextInput::make('amount_received')->label('Amount received')->prefix('₦')->numeric()->integer()->minValue(1)->required(),
                    TextInput::make('payment_purpose')->required()->maxLength(160)->columnSpanFull(),
                    Select::make('payment_method')
                        ->options([
                            'Bank transfer' => 'Bank transfer',
                            'Cash' => 'Cash',
                            'POS/Card' => 'POS/Card',
                            'Cheque' => 'Cheque',
                            'Other' => 'Other',
                        ])
                        ->required(),
                    DatePicker::make('payment_date')->default(today())->maxDate(today())->required(),
                    TextInput::make('transaction_reference')->label('Bank or transaction reference')->maxLength(120),
                    TextInput::make('balance_remaining')->label('Balance remaining')->prefix('₦')->numeric()->integer()->minValue(0),
                    Textarea::make('notes')->label('Receipt note')->rows(4)->maxLength(2000)->columnSpanFull(),
                ]),
        ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Receipt status')
                ->columns(3)
                ->schema([
                    TextEntry::make('receipt_number')->placeholder('Assigned when issued')->copyable(),
                    TextEntry::make('status')
                        ->badge()
                        ->formatStateUsing(fn (string $state): string => PaymentReceipt::statusOptions()[$state] ?? $state)
                        ->color(fn (string $state): string => match ($state) {
                            PaymentReceipt::STATUS_DRAFT => 'gray',
                            PaymentReceipt::STATUS_ISSUED => 'success',
                            PaymentReceipt::STATUS_VOIDED => 'danger',
                            default => 'gray',
                        }),
                    TextEntry::make('payment_date')->date('j M Y'),
                ]),
            Section::make('Confirmed payment')
                ->columns(3)
                ->schema([
                    TextEntry::make('customer_name'),
                    TextEntry::make('customer_phone')->copyable(),
                    TextEntry::make('customer_email')->copyable()->placeholder('Not supplied'),
                    TextEntry::make('project_name'),
                    TextEntry::make('plot_label')->placeholder('Not specified'),
                    TextEntry::make('plot_size_sqm')->suffix(' sqm')->placeholder('Not specified'),
                    TextEntry::make('amount_received')->money('NGN'),
                    TextEntry::make('amount_in_words')->label('Amount in words')->columnSpan(2),
                    TextEntry::make('payment_purpose')->columnSpan(2),
                    TextEntry::make('payment_method'),
                    TextEntry::make('transaction_reference')->copyable()->placeholder('Not supplied'),
                    TextEntry::make('balance_remaining')->money('NGN')->placeholder('Not supplied'),
                    TextEntry::make('notes')->placeholder('No receipt note')->columnSpanFull(),
                ]),
            Section::make('Audit history')
                ->columns(3)
                ->schema([
                    TextEntry::make('creator.name')->label('Draft created by')->placeholder('Unknown'),
                    TextEntry::make('issued_at')->dateTime('j M Y, g:i a')->placeholder('Not issued'),
                    TextEntry::make('issuer_name')->label('Issued by')->placeholder('Not issued'),
                    TextEntry::make('voided_at')->dateTime('j M Y, g:i a')->placeholder('Not voided'),
                    TextEntry::make('voidedBy.name')->label('Voided by')->placeholder('Not voided'),
                    TextEntry::make('void_reason')->placeholder('Not voided')->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('receipt_number')->label('Receipt')->placeholder('Draft')->searchable()->copyable()->weight('medium'),
                TextColumn::make('customer_name')->label('Customer')->searchable()->description(fn (PaymentReceipt $record): string => $record->customer_phone),
                TextColumn::make('project_name')->label('Project')->searchable()->description(fn (PaymentReceipt $record): ?string => $record->plot_label),
                TextColumn::make('amount_received')->label('Amount received')->money('NGN')->sortable(),
                TextColumn::make('payment_date')->date('j M Y')->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => PaymentReceipt::statusOptions()[$state] ?? $state)
                    ->color(fn (string $state): string => match ($state) {
                        PaymentReceipt::STATUS_DRAFT => 'gray',
                        PaymentReceipt::STATUS_ISSUED => 'success',
                        PaymentReceipt::STATUS_VOIDED => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')->options(PaymentReceipt::statusOptions()),
                SelectFilter::make('project_id')->label('Project')->relationship('project', 'name'),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make()->visible(fn (PaymentReceipt $record): bool => $record->isDraft()),
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
                ])->label('Actions')->button()->dropdownPlacement('bottom-end'),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function formDataFromEnquiry(ProjectEnquiry $enquiry): array
    {
        return [
            'project_enquiry_id' => $enquiry->getKey(),
            'project_id' => $enquiry->project_id,
            'customer_name' => $enquiry->full_name,
            'customer_phone' => $enquiry->phone,
            'customer_email' => $enquiry->email,
            'project_name' => $enquiry->project_name,
            'project_slug' => $enquiry->project_slug,
            'plot_size_sqm' => $enquiry->plot_size_sqm,
            'plot_label' => $enquiry->plot_label,
            'currency' => $enquiry->currency,
            'payment_purpose' => 'Payment for '.$enquiry->project_name.' land',
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPaymentReceipts::route('/'),
            'create' => CreatePaymentReceipt::route('/create'),
            'view' => ViewPaymentReceipt::route('/{record}'),
            'edit' => EditPaymentReceipt::route('/{record}/edit'),
        ];
    }
}
