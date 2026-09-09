<?php

namespace App\Filament\Resources\Projects\Schemas;

use App\Enums\ProjectDivision;
use App\Enums\ProjectMediaKind;
use App\Enums\ProjectMediaRole;
use App\Enums\ProjectPublicationStatus;
use App\Enums\ProjectStatus;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

class ProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Project content')
                    ->persistTabInQueryString()
                    ->tabs([
                        Tab::make('Basic info')
                            ->schema([
                                Section::make('Identity')
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('name')->required()->maxLength(160),
                                        TextInput::make('slug')
                                            ->required()
                                            ->unique(ignoreRecord: true)
                                            ->maxLength(180)
                                            ->helperText('Used in public URLs. Use lowercase words separated by hyphens.'),
                                        Select::make('division')
                                            ->options([
                                                ProjectDivision::RealEstateDevelopment->value => 'Real Estate Development',
                                                ProjectDivision::EngineeringConstruction->value => 'Engineering & Construction',
                                            ])
                                            ->required(),
                                        TextInput::make('project_type')->maxLength(120),
                                        Select::make('status')
                                            ->options([
                                                ProjectStatus::Upcoming->value => 'Upcoming',
                                                ProjectStatus::Ongoing->value => 'Ongoing',
                                                ProjectStatus::Completed->value => 'Completed',
                                            ])
                                            ->required(),
                                        TextInput::make('location_summary')->maxLength(255),
                                    ]),
                                Section::make('Project copy')
                                    ->schema([
                                        Textarea::make('summary')->required()->rows(3),
                                        Textarea::make('overview')->rows(6),
                                        Textarea::make('marketing_summary')->rows(4),
                                    ]),
                                Repeater::make('locations')
                                    ->relationship()
                                    ->orderColumn('sort_order')
                                    ->reorderable()
                                    ->collapsible()
                                    ->columns(3)
                                    ->schema([
                                        TextInput::make('name')->required()->maxLength(160),
                                        TextInput::make('region')->maxLength(160),
                                        TextInput::make('country')->required()->default('Nigeria')->maxLength(100),
                                    ])
                                    ->itemLabel(fn (array $state): ?string => $state['name'] ?? null)
                                    ->addActionLabel('Add location'),
                            ]),
                        Tab::make('Media')
                            ->schema([
                                Repeater::make('media')
                                    ->relationship()
                                    ->orderColumn('sort_order')
                                    ->reorderable()
                                    ->collapsible()
                                    ->columns(2)
                                    ->schema([
                                        Select::make('kind')
                                            ->options([
                                                ProjectMediaKind::Image->value => 'Image',
                                                ProjectMediaKind::Video->value => 'Video',
                                                ProjectMediaKind::Document->value => 'Document',
                                            ])
                                            ->required(),
                                        Select::make('role')
                                            ->options([
                                                ProjectMediaRole::HeroImage->value => 'Hero image',
                                                ProjectMediaRole::GalleryImage->value => 'Gallery image',
                                                ProjectMediaRole::DetailVideo->value => 'Detail video',
                                                ProjectMediaRole::DetailVideoPoster->value => 'Detail video poster',
                                                ProjectMediaRole::PreviewVideo->value => 'Preview video',
                                                ProjectMediaRole::PreviewVideoPoster->value => 'Preview video poster',
                                                ProjectMediaRole::Brochure->value => 'Brochure',
                                            ])
                                            ->required(),
                                        FileUpload::make('path')
                                            ->disk('public')
                                            ->directory('projects')
                                            ->visibility('public')
                                            ->downloadable()
                                            ->openable()
                                            ->helperText('Upload a file, or enter an external URL below.'),
                                        TextInput::make('external_url')->url()->maxLength(2048),
                                        TextInput::make('mime_type')->maxLength(120),
                                        TextInput::make('alt_text')->maxLength(255),
                                        Textarea::make('caption')->rows(2),
                                        TextInput::make('credit')->maxLength(255),
                                    ])
                                    ->itemLabel(fn (array $state): ?string => isset($state['role']) ? str($state['role'])->replace('_', ' ')->title()->toString() : null)
                                    ->addActionLabel('Add media'),
                            ]),
                        Tab::make('Pricing & plots')
                            ->schema([
                                Section::make('Base pricing')
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('price_per_sqm')->numeric()->prefix('₦')->minValue(0),
                                        TextInput::make('currency')->required()->default('NGN')->length(3),
                                    ]),
                                Repeater::make('plotOptions')
                                    ->relationship()
                                    ->orderColumn('sort_order')
                                    ->reorderable()
                                    ->collapsible()
                                    ->columns(4)
                                    ->schema([
                                        TextInput::make('label')->required()->maxLength(120),
                                        TextInput::make('size_sqm')->label('Size (sqm)')->required()->numeric()->minValue(0),
                                        TextInput::make('price')->required()->numeric()->prefix('₦')->minValue(0),
                                        TextInput::make('currency')->required()->default('NGN')->length(3),
                                    ])
                                    ->itemLabel(fn (array $state): ?string => $state['label'] ?? null)
                                    ->addActionLabel('Add plot option'),
                            ]),
                        Tab::make('Payments & charges')
                            ->schema([
                                Section::make('Payment plan')
                                    ->relationship('paymentPlan')
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('initial_deposit')->numeric()->prefix('₦')->minValue(0),
                                        TextInput::make('currency')->required()->default('NGN')->length(3),
                                        TextInput::make('balance_period')->maxLength(120),
                                        Textarea::make('note')->rows(3),
                                    ]),
                                Repeater::make('charges')
                                    ->relationship()
                                    ->orderColumn('sort_order')
                                    ->reorderable()
                                    ->collapsible()
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('label')->required()->maxLength(160),
                                        TextInput::make('value')->required()->maxLength(160),
                                    ])
                                    ->itemLabel(fn (array $state): ?string => $state['label'] ?? null)
                                    ->addActionLabel('Add charge'),
                            ]),
                        Tab::make('Documents & infrastructure')
                            ->schema([
                                Repeater::make('documentStages')
                                    ->relationship()
                                    ->orderColumn('sort_order')
                                    ->reorderable()
                                    ->collapsible()
                                    ->schema([
                                        TextInput::make('stage')->required()->maxLength(200),
                                        Repeater::make('documents')
                                            ->relationship()
                                            ->orderColumn('sort_order')
                                            ->reorderable()
                                            ->simple(TextInput::make('name')->required()->maxLength(180))
                                            ->addActionLabel('Add document'),
                                    ])
                                    ->itemLabel(fn (array $state): ?string => $state['stage'] ?? null)
                                    ->addActionLabel('Add document stage'),
                                Repeater::make('infrastructure')
                                    ->relationship()
                                    ->orderColumn('sort_order')
                                    ->reorderable()
                                    ->simple(TextInput::make('name')->required()->maxLength(180))
                                    ->addActionLabel('Add infrastructure item'),
                            ]),
                        Tab::make('Allocation & policies')
                            ->schema([
                                Section::make('Ownership and delivery')
                                    ->schema([
                                        TextInput::make('land_title')->maxLength(255),
                                        Textarea::make('title_information')->rows(4),
                                        Textarea::make('allocation_details')->rows(5),
                                        Textarea::make('construction_details')->rows(5),
                                    ]),
                                Repeater::make('policies')
                                    ->relationship()
                                    ->orderColumn('sort_order')
                                    ->reorderable()
                                    ->collapsible()
                                    ->schema([
                                        TextInput::make('heading')->required()->maxLength(180),
                                        Textarea::make('body')->required()->rows(4),
                                    ])
                                    ->itemLabel(fn (array $state): ?string => $state['heading'] ?? null)
                                    ->addActionLabel('Add policy'),
                            ]),
                        Tab::make('FAQs')
                            ->schema([
                                Repeater::make('faqGroups')
                                    ->relationship()
                                    ->orderColumn('sort_order')
                                    ->reorderable()
                                    ->collapsible()
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('label')->required()->maxLength(200),
                                        TextInput::make('slug')->required()->maxLength(160),
                                        Repeater::make('faqs')
                                            ->relationship()
                                            ->orderColumn('sort_order')
                                            ->reorderable()
                                            ->collapsible()
                                            ->columnSpanFull()
                                            ->schema([
                                                TextInput::make('key')->required()->maxLength(180),
                                                Toggle::make('is_featured')->label('Show on homepage'),
                                                TextInput::make('question')->required()->columnSpanFull(),
                                                Textarea::make('answer')->required()->rows(4)->columnSpanFull(),
                                                Repeater::make('points')
                                                    ->simple(TextInput::make('point')->required())
                                                    ->columnSpanFull(),
                                                Textarea::make('note')->rows(2)->columnSpanFull(),
                                            ])
                                            ->columns(2)
                                            ->itemLabel(fn (array $state): ?string => $state['question'] ?? null)
                                            ->addActionLabel('Add FAQ'),
                                    ])
                                    ->itemLabel(fn (array $state): ?string => $state['label'] ?? null)
                                    ->addActionLabel('Add FAQ group'),
                            ]),
                        Tab::make('SEO & publishing')
                            ->schema([
                                Section::make('Publishing')
                                    ->columns(2)
                                    ->schema([
                                        Select::make('publication_status')
                                            ->options([
                                                ProjectPublicationStatus::Draft->value => 'Draft',
                                                ProjectPublicationStatus::Published->value => 'Published',
                                            ])
                                            ->required()
                                            ->default(ProjectPublicationStatus::Draft->value),
                                        DateTimePicker::make('published_at')
                                            ->helperText('A published project only becomes public from this date.'),
                                        Toggle::make('is_featured'),
                                        Toggle::make('is_publication_verified')
                                            ->label('Information verified for publication')
                                            ->helperText('Required before a published project can appear on the public website.'),
                                        TextInput::make('sort_order')->numeric()->default(0)->minValue(0),
                                    ]),
                                Section::make('Search metadata')
                                    ->schema([
                                        TextInput::make('canonical_path')->unique(ignoreRecord: true)->maxLength(255),
                                        TextInput::make('seo_title')->maxLength(70),
                                        Textarea::make('seo_description')->rows(3)->maxLength(170),
                                        Textarea::make('disclaimer')->rows(4),
                                    ]),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
