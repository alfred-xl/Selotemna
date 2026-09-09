<?php

namespace App\Filament\Widgets;

use App\Enums\ProjectPublicationStatus;
use App\Enums\ProjectStatus;
use App\Models\ContactEnquiry;
use App\Models\InspectionRequest;
use App\Models\Project;
use App\Models\ProjectEnquiry;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ProjectAndLeadStats extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected static bool $isLazy = false;

    protected int|string|array $columnSpan = 'full';

    protected function getStats(): array
    {
        return [
            Stat::make('All projects', Project::query()->count())
                ->description('Managed project records')
                ->icon('heroicon-o-building-office-2'),
            Stat::make('Published', Project::query()->where('publication_status', ProjectPublicationStatus::Published)->count())
                ->description('Visible or scheduled projects')
                ->color('success')
                ->icon('heroicon-o-eye'),
            Stat::make('Drafts', Project::query()->where('publication_status', ProjectPublicationStatus::Draft)->count())
                ->description('Not publicly visible')
                ->color('gray')
                ->icon('heroicon-o-pencil-square'),
            Stat::make('Upcoming', Project::query()->where('status', ProjectStatus::Upcoming)->count())
                ->description('Upcoming projects')
                ->color('info')
                ->icon('heroicon-o-clock'),
            Stat::make('Ongoing', Project::query()->where('status', ProjectStatus::Ongoing)->count())
                ->description('Projects in progress')
                ->color('warning')
                ->icon('heroicon-o-arrow-path'),
            Stat::make('Completed', Project::query()->where('status', ProjectStatus::Completed)->count())
                ->description('Delivered projects')
                ->color('success')
                ->icon('heroicon-o-check-circle'),
            Stat::make('New plot enquiries', ProjectEnquiry::query()->where('status', ProjectEnquiry::STATUS_NEW)->count())
                ->description('Awaiting first follow-up')
                ->color('warning')
                ->icon('heroicon-o-chat-bubble-left-right'),
            Stat::make('New inspections', InspectionRequest::query()->where('status', InspectionRequest::STATUS_NEW)->count())
                ->description('Awaiting first follow-up')
                ->color('warning')
                ->icon('heroicon-o-calendar-days'),
            Stat::make('New enquiries', ContactEnquiry::query()->where('status', ContactEnquiry::STATUS_NEW)->count())
                ->description('Awaiting first follow-up')
                ->color('warning')
                ->icon('heroicon-o-chat-bubble-left-right'),
        ];
    }
}
