<?php

use App\Enums\ProjectDivision;
use App\Enums\ProjectMediaRole;
use App\Enums\ProjectPublicationStatus;
use App\Enums\ProjectStatus;
use App\Models\Project;

it('imports the complete verified Omu Creek project graph', function () {
    $this->artisan('selotemna:import-omu-creek')
        ->expectsOutputToContain('Omu Creek was imported')
        ->assertSuccessful();

    $project = Project::query()->with([
        'locations',
        'media',
        'plotOptions',
        'paymentPlan',
        'charges',
        'documentStages.documents',
        'infrastructure',
        'policies',
        'faqGroups.faqs',
    ])->sole();

    expect($project->name)->toBe('Omu Creek')
        ->and($project->slug)->toBe('omu-creek')
        ->and($project->division)->toBe(ProjectDivision::RealEstateDevelopment)
        ->and($project->status)->toBe(ProjectStatus::Upcoming)
        ->and($project->status_label)->toBe('Upcoming Project')
        ->and($project->publication_status)->toBe(ProjectPublicationStatus::Published)
        ->and($project->is_featured)->toBeTrue()
        ->and($project->canonical_path)->toBe('/real-estate-development/omu-creek')
        ->and($project->price_per_sqm)->toBe(50000)
        ->and($project->currency)->toBe('NGN')
        ->and($project->locations)->toHaveCount(3)
        ->and($project->plotOptions)->toHaveCount(3)
        ->and($project->plotOptions->pluck('price')->all())->toBe([50000000, 25000000, 15000000])
        ->and($project->paymentPlan->initial_deposit)->toBe(5000000)
        ->and($project->charges)->toHaveCount(3)
        ->and($project->documentStages)->toHaveCount(3)
        ->and($project->documentStages->sum(fn ($stage): int => $stage->documents->count()))->toBe(7)
        ->and($project->infrastructure)->toHaveCount(6)
        ->and($project->policies)->toHaveCount(3)
        ->and($project->faqGroups)->toHaveCount(count(config('selotemna.faq_groups')))
        ->and($project->faqGroups->sum(fn ($group): int => $group->faqs->count()))->toBe(15)
        ->and($project->faqGroups->flatMap->faqs->where('is_featured', true))->toHaveCount(4)
        ->and($project->media->pluck('role'))->toContain(
            ProjectMediaRole::HeroImage,
            ProjectMediaRole::GalleryImage,
            ProjectMediaRole::DetailVideo,
            ProjectMediaRole::PreviewVideo,
        )
        ->and($project->media->firstWhere('role', ProjectMediaRole::HeroImage)->path)->toBe('assets/images/omu-creek.png')
        ->and($project->media->firstWhere('role', ProjectMediaRole::GalleryImage)->path)->toBe('assets/images/omu-creek-2.png');

    expect(Project::query()->published()->featured()->sole()->is($project))->toBeTrue();
});

it('does not overwrite an existing project unless force is explicitly requested', function () {
    $this->artisan('selotemna:import-omu-creek')->assertSuccessful();

    $project = Project::query()->where('slug', 'omu-creek')->sole();
    $project->update(['overview' => 'Editorial work in progress.']);

    $this->artisan('selotemna:import-omu-creek')
        ->expectsOutputToContain('No content was changed')
        ->assertSuccessful();

    expect($project->fresh()->overview)->toBe('Editorial work in progress.')
        ->and(Project::query()->count())->toBe(1);

    $this->artisan('selotemna:import-omu-creek', ['--force' => true])
        ->expectsOutputToContain('Omu Creek was imported')
        ->assertSuccessful();

    expect($project->fresh()->overview)->toBe(config('selotemna.featured_property.overview'))
        ->and($project->plotOptions()->count())->toBe(3)
        ->and($project->faqGroups()->withCount('faqs')->get()->sum('faqs_count'))->toBe(15);
});

it('keeps editorial publication state separate from the project stage', function () {
    $this->artisan('selotemna:import-omu-creek')->assertSuccessful();

    Project::query()->create([
        'name' => 'Draft Project',
        'slug' => 'draft-project',
        'division' => ProjectDivision::EngineeringConstruction,
        'project_type' => 'Construction',
        'status' => ProjectStatus::Ongoing,
        'publication_status' => ProjectPublicationStatus::Draft,
        'summary' => 'Private editorial draft.',
        'currency' => 'NGN',
    ]);

    Project::query()->create([
        'name' => 'Scheduled Project',
        'slug' => 'scheduled-project',
        'division' => ProjectDivision::RealEstateDevelopment,
        'project_type' => 'Land allocation',
        'status' => ProjectStatus::Upcoming,
        'publication_status' => ProjectPublicationStatus::Published,
        'summary' => 'Scheduled for later.',
        'currency' => 'NGN',
        'published_at' => now()->addDay(),
    ]);

    expect(Project::query()->forStatus(ProjectStatus::Upcoming)->count())->toBe(2)
        ->and(Project::query()->forDivision(ProjectDivision::RealEstateDevelopment)->count())->toBe(2)
        ->and(Project::query()->published()->pluck('slug')->all())->toBe(['omu-creek']);
});

it('cascades related project records only when a project is permanently deleted', function () {
    $this->artisan('selotemna:import-omu-creek')->assertSuccessful();

    $project = Project::query()->sole();
    $project->delete();

    expect($project->locations()->count())->toBe(3)
        ->and(Project::query()->count())->toBe(0)
        ->and(Project::withTrashed()->count())->toBe(1);

    $project->forceDelete();

    $this->assertDatabaseCount('project_locations', 0);
    $this->assertDatabaseCount('project_plot_options', 0);
    $this->assertDatabaseCount('project_faq_groups', 0);
    $this->assertDatabaseCount('project_faqs', 0);
});
