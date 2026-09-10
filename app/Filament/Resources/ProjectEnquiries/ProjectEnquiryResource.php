<?php

namespace App\Filament\Resources\ProjectEnquiries;

use App\Filament\Resources\PaymentReceipts\PaymentReceiptResource;
use App\Filament\Resources\ProjectEnquiries\Pages\EditProjectEnquiry;
use App\Filament\Resources\ProjectEnquiries\Pages\ListProjectEnquiries;
use App\Filament\Resources\ProjectEnquiries\Pages\ViewProjectEnquiry;
use App\Models\ProjectEnquiry;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class ProjectEnquiryResource extends Resource
{
    protected static ?string $model = ProjectEnquiry::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChatBubbleLeftRight;

    protected static string|UnitEnum|null $navigationGroup = 'Lead management';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'reference';

    public static function getNavigationBadge(): ?string
    {
        $count = ProjectEnquiry::query()->where('status', ProjectEnquiry::STATUS_NEW)->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }

    public static function canDeleteAny(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Lead workflow')
                ->columns(2)
                ->schema([
                    Select::make('status')->options(ProjectEnquiry::statusOptions())->required(),
                    DateTimePicker::make('handled_at')->label('First handled at'),
                    Textarea::make('admin_notes')->label('Internal notes')->rows(5)->columnSpanFull(),
                ]),
            Section::make('Submitted details')
                ->columns(2)
                ->schema([
                    TextInput::make('reference')->disabled(),
                    TextInput::make('project_name')->disabled(),
                    TextInput::make('plot_label')->disabled(),
                    TextInput::make('plot_size_sqm')->label('Requested size')->suffix('sqm')->disabled(),
                    TextInput::make('price_per_sqm_snapshot')->label('Rate when submitted')->prefix('₦')->suffix('per sqm')->disabled(),
                    TextInput::make('price_snapshot')->label('Estimated base price')->prefix('₦')->disabled(),
                    TextInput::make('payment_preference')->disabled(),
                    TextInput::make('purchase_timeline')->disabled(),
                    TextInput::make('full_name')->disabled(),
                    TextInput::make('phone')->disabled(),
                    TextInput::make('email')->disabled(),
                    TextInput::make('whatsapp')->disabled(),
                    TextInput::make('preferred_contact_method')->disabled(),
                    Textarea::make('message')->disabled()->rows(4)->columnSpanFull(),
                ]),
        ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Plot enquiry')
                ->columns(3)
                ->schema([
                    TextEntry::make('reference')->copyable(),
                    TextEntry::make('status')->badge()->formatStateUsing(fn (string $state): string => ProjectEnquiry::statusOptions()[$state] ?? $state),
                    TextEntry::make('created_at')->label('Received')->dateTime('j M Y, g:i a'),
                    TextEntry::make('project_name'),
                    TextEntry::make('plot_label'),
                    TextEntry::make('plot_size_sqm')->label('Requested size')->suffix(' sqm')->placeholder('Not selected'),
                    TextEntry::make('price_per_sqm_snapshot')->label('Rate when submitted')->money('NGN')->suffix(' per sqm')->placeholder('Not selected'),
                    TextEntry::make('price_snapshot')->label('Estimated base price')->money('NGN')->placeholder('Not selected'),
                    TextEntry::make('payment_preference'),
                    TextEntry::make('purchase_timeline'),
                    TextEntry::make('preferred_contact_method'),
                ]),
            Section::make('Contact details')
                ->columns(3)
                ->schema([
                    TextEntry::make('full_name'),
                    TextEntry::make('phone')->copyable(),
                    TextEntry::make('whatsapp')->copyable()->placeholder('Not provided'),
                    TextEntry::make('email')->copyable()->placeholder('Not provided'),
                    TextEntry::make('message')->placeholder('No additional message')->columnSpanFull(),
                ]),
            Section::make('Follow-up')
                ->schema([
                    TextEntry::make('admin_notes')->label('Internal notes')->placeholder('No internal notes'),
                    TextEntry::make('handled_at')->label('First handled')->dateTime('j M Y, g:i a')->placeholder('Not handled yet'),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('reference')->searchable()->copyable()->weight('medium'),
                TextColumn::make('full_name')->label('Contact')->searchable()->description(fn (ProjectEnquiry $record): string => $record->phone),
                TextColumn::make('plot_label')
                    ->label('Plot preference')
                    ->searchable()
                    ->description(fn (ProjectEnquiry $record): ?string => $record->price_snapshot
                        ? '₦'.number_format($record->price_snapshot).' estimate · ₦'.number_format($record->price_per_sqm_snapshot).' per sqm'
                        : null)
                    ->wrap(),
                TextColumn::make('payment_preference')->badge(),
                TextColumn::make('purchase_timeline')->label('Timeline'),
                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => ProjectEnquiry::statusOptions()[$state] ?? $state)
                    ->color(fn (string $state): string => match ($state) {
                        ProjectEnquiry::STATUS_NEW => 'warning',
                        ProjectEnquiry::STATUS_CONTACTED => 'info',
                        ProjectEnquiry::STATUS_QUALIFIED => 'primary',
                        ProjectEnquiry::STATUS_INSPECTION_SCHEDULED => 'success',
                        ProjectEnquiry::STATUS_CLOSED => 'gray',
                        default => 'gray',
                    })
                    ->sortable(),
                TextColumn::make('created_at')->label('Received')->dateTime('j M Y, g:i a')->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')->options(ProjectEnquiry::statusOptions()),
                SelectFilter::make('plot_size_sqm')->label('Plot size')->options([
                    300 => '300 sqm',
                    500 => '500 sqm',
                    1000 => '1,000 sqm',
                ]),
            ])
            ->recordActions([
                Action::make('generateReceipt')
                    ->label('Generate e-receipt')
                    ->icon('heroicon-o-document-currency-dollar')
                    ->url(fn (ProjectEnquiry $record): string => PaymentReceiptResource::getUrl('create', ['project_enquiry' => $record->getKey()])),
                ViewAction::make(),
                EditAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProjectEnquiries::route('/'),
            'view' => ViewProjectEnquiry::route('/{record}'),
            'edit' => EditProjectEnquiry::route('/{record}/edit'),
        ];
    }
}
