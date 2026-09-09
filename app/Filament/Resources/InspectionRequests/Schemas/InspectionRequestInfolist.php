<?php

namespace App\Filament\Resources\InspectionRequests\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class InspectionRequestInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Inspection request')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('reference')->copyable(),
                        TextEntry::make('status')->badge()->color(fn (string $state): string => match ($state) {
                            'new' => 'warning',
                            'contacted' => 'info',
                            'closed' => 'success',
                            default => 'gray',
                        }),
                        TextEntry::make('created_at')->label('Received')->dateTime('j M Y, g:i a'),
                        TextEntry::make('project_name'),
                        TextEntry::make('preferred_date')->date('j M Y')->placeholder('Flexible'),
                        TextEntry::make('preferred_time')->placeholder('Flexible'),
                    ]),
                Section::make('Contact details')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('full_name'),
                        TextEntry::make('phone')->copyable(),
                        TextEntry::make('whatsapp')->copyable()->placeholder('Not provided'),
                        TextEntry::make('email')->copyable()->placeholder('Not provided'),
                        TextEntry::make('preferred_contact_method')->badge(),
                        TextEntry::make('consented_at')->label('Consent recorded')->dateTime('j M Y, g:i a'),
                    ]),
                Section::make('Message and follow-up')
                    ->schema([
                        TextEntry::make('message')->placeholder('No additional message'),
                        TextEntry::make('admin_notes')->label('Internal notes')->placeholder('No internal notes'),
                        TextEntry::make('handled_at')->label('First handled')->dateTime('j M Y, g:i a')->placeholder('Not handled yet'),
                    ]),
            ]);
    }
}
