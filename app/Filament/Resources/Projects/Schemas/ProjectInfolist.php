<?php

namespace App\Filament\Resources\Projects\Schemas;

use App\Enums\ProjectPublicationStatus;
use App\Enums\ProjectStatus;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

class ProjectInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Project preview')
                    ->persistTabInQueryString()
                    ->tabs([
                        Tab::make('Overview')
                            ->schema([
                                Section::make('Project facts')
                                    ->columns(3)
                                    ->schema([
                                        TextEntry::make('name'),
                                        TextEntry::make('division')->badge(),
                                        TextEntry::make('status')
                                            ->badge()
                                            ->formatStateUsing(fn (ProjectStatus $state): string => $state->label()),
                                        TextEntry::make('project_type')->placeholder('Not set'),
                                        TextEntry::make('location_summary')->placeholder('Not set'),
                                        TextEntry::make('land_title')->placeholder('Not set'),
                                        TextEntry::make('summary')->columnSpanFull(),
                                        TextEntry::make('overview')->columnSpanFull()->placeholder('Not set'),
                                        TextEntry::make('marketing_summary')->columnSpanFull()->placeholder('Not set'),
                                    ]),
                                RepeatableEntry::make('locations')
                                    ->columns(3)
                                    ->schema([
                                        TextEntry::make('name'),
                                        TextEntry::make('region')->placeholder('—'),
                                        TextEntry::make('country'),
                                    ]),
                            ]),
                        Tab::make('Pricing')
                            ->schema([
                                Section::make('Pricing summary')
                                    ->columns(2)
                                    ->schema([
                                        TextEntry::make('price_per_sqm')->money(fn ($record): string => $record->currency)->placeholder('Not set'),
                                        TextEntry::make('currency'),
                                    ]),
                                RepeatableEntry::make('plotOptions')
                                    ->columns(3)
                                    ->schema([
                                        TextEntry::make('label'),
                                        TextEntry::make('size_sqm')->label('Size (sqm)'),
                                        TextEntry::make('price')->money(fn ($record): string => $record->currency),
                                    ]),
                            ]),
                        Tab::make('Payments & charges')
                            ->schema([
                                Section::make('Payment plan')
                                    ->columns(3)
                                    ->schema([
                                        TextEntry::make('paymentPlan.initial_deposit')->money('NGN')->placeholder('Not set'),
                                        TextEntry::make('paymentPlan.balance_period')->placeholder('Not set'),
                                        TextEntry::make('paymentPlan.note')->placeholder('Not set'),
                                    ]),
                                RepeatableEntry::make('charges')
                                    ->columns(2)
                                    ->schema([
                                        TextEntry::make('label'),
                                        TextEntry::make('value'),
                                    ]),
                            ]),
                        Tab::make('Documents & infrastructure')
                            ->schema([
                                RepeatableEntry::make('documentStages')
                                    ->schema([
                                        TextEntry::make('stage')->weight('bold'),
                                        RepeatableEntry::make('documents')
                                            ->schema([
                                                TextEntry::make('name')->hiddenLabel(),
                                            ]),
                                    ]),
                                RepeatableEntry::make('infrastructure')
                                    ->schema([
                                        TextEntry::make('name')->hiddenLabel(),
                                    ]),
                            ]),
                        Tab::make('Allocation & policies')
                            ->schema([
                                Section::make('Delivery details')
                                    ->schema([
                                        TextEntry::make('title_information')->placeholder('Not set'),
                                        TextEntry::make('allocation_details')->placeholder('Not set'),
                                        TextEntry::make('construction_details')->placeholder('Not set'),
                                    ]),
                                RepeatableEntry::make('policies')
                                    ->schema([
                                        TextEntry::make('heading')->weight('bold'),
                                        TextEntry::make('body'),
                                    ]),
                            ]),
                        Tab::make('Media')
                            ->schema([
                                RepeatableEntry::make('media')
                                    ->columns(3)
                                    ->schema([
                                        TextEntry::make('role')->badge(),
                                        TextEntry::make('kind')->badge(),
                                        TextEntry::make('source_url')->label('Source')->copyable()->columnSpanFull(),
                                        TextEntry::make('alt_text')->placeholder('No alt text'),
                                        TextEntry::make('caption')->placeholder('No caption'),
                                        TextEntry::make('credit')->placeholder('No credit'),
                                    ]),
                            ]),
                        Tab::make('FAQs')
                            ->schema([
                                RepeatableEntry::make('faqGroups')
                                    ->schema([
                                        TextEntry::make('label')->weight('bold'),
                                        RepeatableEntry::make('faqs')
                                            ->schema([
                                                TextEntry::make('question')->weight('bold'),
                                                TextEntry::make('answer'),
                                                TextEntry::make('points')->listWithLineBreaks()->bulleted()->placeholder('No supporting points'),
                                                TextEntry::make('note')->placeholder('No note'),
                                            ]),
                                    ]),
                            ]),
                        Tab::make('Publishing')
                            ->schema([
                                Section::make('Visibility and SEO')
                                    ->columns(2)
                                    ->schema([
                                        TextEntry::make('publication_status')
                                            ->badge()
                                            ->formatStateUsing(fn (ProjectPublicationStatus $state): string => $state->label()),
                                        TextEntry::make('published_at')->dateTime('j M Y, g:i a')->placeholder('Not scheduled'),
                                        TextEntry::make('canonical_path')->copyable()->placeholder('Not set'),
                                        TextEntry::make('is_featured')->formatStateUsing(fn (bool $state): string => $state ? 'Yes' : 'No'),
                                        TextEntry::make('is_publication_verified')->label('Publication verified')->formatStateUsing(fn (bool $state): string => $state ? 'Yes' : 'No'),
                                        TextEntry::make('seo_title')->placeholder('Not set')->columnSpanFull(),
                                        TextEntry::make('seo_description')->placeholder('Not set')->columnSpanFull(),
                                        TextEntry::make('disclaimer')->placeholder('Not set')->columnSpanFull(),
                                    ]),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
