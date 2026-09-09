<?php

namespace App\Filament\Resources\InspectionRequests\Schemas;

use App\Models\InspectionRequest;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class InspectionRequestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Lead workflow')
                    ->description('Update the follow-up status and keep notes for the team.')
                    ->columns(2)
                    ->schema([
                        Select::make('status')->options(InspectionRequest::statusOptions())->required(),
                        DateTimePicker::make('handled_at')->label('First handled at'),
                        Textarea::make('admin_notes')->label('Internal notes')->rows(5)->columnSpanFull(),
                    ]),
                Section::make('Submitted details')
                    ->columns(2)
                    ->schema([
                        TextInput::make('reference')->disabled(),
                        TextInput::make('project_name')->disabled(),
                        TextInput::make('full_name')->disabled(),
                        TextInput::make('phone')->disabled(),
                        TextInput::make('whatsapp')->disabled(),
                        TextInput::make('email')->disabled(),
                        TextInput::make('preferred_contact_method')->disabled(),
                        DatePicker::make('preferred_date')->disabled(),
                        TextInput::make('preferred_time')->disabled(),
                        Textarea::make('message')->disabled()->rows(4)->columnSpanFull(),
                    ]),
            ]);
    }
}
