<?php

namespace App\Filament\Resources\ContactEnquiries\Schemas;

use App\Models\ContactEnquiry;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ContactEnquiryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Lead workflow')
                    ->description('Update the follow-up status and keep notes for the team.')
                    ->columns(2)
                    ->schema([
                        Select::make('status')->options(ContactEnquiry::statusOptions())->required(),
                        DateTimePicker::make('handled_at')->label('First handled at'),
                        Textarea::make('admin_notes')->label('Internal notes')->rows(5)->columnSpanFull(),
                    ]),
                Section::make('Submitted details')
                    ->columns(2)
                    ->schema([
                        TextInput::make('reference')->disabled(),
                        TextInput::make('full_name')->disabled(),
                        TextInput::make('phone')->disabled(),
                        TextInput::make('email')->disabled(),
                        TextInput::make('whatsapp')->disabled(),
                        TextInput::make('preferred_contact_method')->disabled(),
                        TextInput::make('interest_type')->disabled(),
                        TextInput::make('enquiry_type')->disabled(),
                        TextInput::make('project_type')->disabled(),
                        TextInput::make('proposed_location')->disabled(),
                        TextInput::make('project_stage')->disabled(),
                        Textarea::make('scope_summary')->disabled()->rows(3)->columnSpanFull(),
                        Textarea::make('message')->disabled()->rows(4)->columnSpanFull(),
                    ]),
            ]);
    }
}
