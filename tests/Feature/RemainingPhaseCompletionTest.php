<?php

use App\Enums\ProjectDivision;
use App\Enums\ProjectMediaKind;
use App\Enums\ProjectMediaRole;
use App\Enums\ProjectPublicationStatus;
use App\Enums\ProjectStatus;
use App\Filament\Actions\PreviewProjectAction;
use App\Filament\Resources\Projects\Pages\CreateProject;
use App\Filament\Resources\Projects\Pages\EditProject;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

function createCompletedPhaseProject(array $attributes = []): Project
{
    $project = Project::query()->create(array_merge([
        'name' => 'Lakeside Residences',
        'slug' => 'lakeside-residences',
        'division' => ProjectDivision::RealEstateDevelopment,
        'project_type' => 'Residential land',
        'status' => ProjectStatus::Ongoing,
        'publication_status' => ProjectPublicationStatus::Published,
        'is_featured' => false,
        'is_publication_verified' => true,
        'summary' => 'A verified managed project prepared for public presentation.',
        'overview' => 'A complete public overview sourced from the managed project record.',
        'location_summary' => 'Lekki, Lagos',
        'land_title' => 'Verified land title',
        'title_information' => 'The approved title information for this project.',
        'price_per_sqm' => 75000,
        'currency' => 'NGN',
        'allocation_details' => 'Allocation follows completion of the documented requirements.',
        'construction_details' => 'Construction follows the approved project guidance.',
        'disclaimer' => 'Pricing and availability remain subject to confirmation.',
        'canonical_path' => '/projects/lakeside-residences',
        'seo_title' => 'Lakeside Residences | Selotemna',
        'seo_description' => 'Review the verified Lakeside Residences project information.',
        'published_at' => now()->subMinute(),
        'sort_order' => 10,
    ], $attributes));

    $project->locations()->create([
        'name' => 'Lekki',
        'region' => 'Lagos State',
        'country' => 'Nigeria',
        'sort_order' => 0,
    ]);
    $project->media()->create([
        'kind' => ProjectMediaKind::Image,
        'role' => ProjectMediaRole::HeroImage,
        'disk' => 'public',
        'external_url' => 'https://images.example.test/lakeside.jpg',
        'alt_text' => 'Approved Lakeside Residences project view',
        'sort_order' => 0,
    ]);
    $project->plotOptions()->create([
        'label' => 'Standard plot',
        'size_sqm' => 500,
        'price' => 37500000,
        'currency' => 'NGN',
        'sort_order' => 0,
    ]);

    return $project->fresh();
}

it('publishes future projects at their generic slug route from the managed record', function () {
    $project = createCompletedPhaseProject();

    $this->get(route('projects.index'))
        ->assertOk()
        ->assertSee($project->name)
        ->assertSee(route('projects.show', ['slug' => $project->slug]), false);

    $this->get(route('projects.show', ['slug' => $project->slug]))
        ->assertOk()
        ->assertSee('<title>Lakeside Residences | Selotemna</title>', false)
        ->assertSee('<link rel="canonical" href="'.url('/projects/lakeside-residences').'">', false)
        ->assertSee('A complete public overview sourced from the managed project record.')
        ->assertSee('RealEstateListing')
        ->assertSee('37500000')
        ->assertDontSee('data-project-preview-banner', false);
});

it('preserves the dedicated Omu Creek canonical route', function () {
    $this->artisan('selotemna:import-omu-creek')->assertSuccessful();

    $this->get('/projects/omu-creek')
        ->assertRedirect(route('omu-creek'))
        ->assertStatus(301);
});

it('allows only administrators with a valid temporary signature to preview drafts', function () {
    $project = createCompletedPhaseProject([
        'name' => 'Private Draft Project',
        'slug' => 'private-draft-project',
        'publication_status' => ProjectPublicationStatus::Draft,
        'is_publication_verified' => false,
        'canonical_path' => '/projects/private-draft-project',
    ]);
    $previewUrl = PreviewProjectAction::url($project);

    $this->get(route('projects.show', ['slug' => $project->slug]))->assertNotFound();
    $this->get($previewUrl)->assertForbidden();

    $ordinaryUser = User::factory()->create(['is_admin' => false]);
    $this->actingAs($ordinaryUser)->get($previewUrl)->assertForbidden();

    $administrator = User::factory()->create(['is_admin' => true]);
    $this->actingAs($administrator)
        ->get($previewUrl)
        ->assertOk()
        ->assertSee('Administrator preview')
        ->assertSee('Private Draft Project')
        ->assertSee('<meta name="robots" content="noindex, nofollow">', false)
        ->assertSee('data-project-preview-banner', false);

    $this->actingAs($administrator)
        ->get(route('projects.show', ['slug' => $project->slug, 'preview' => $project->getKey()]))
        ->assertForbidden();
});

it('keeps unverified published records private and reports exact readiness issues', function () {
    $project = createCompletedPhaseProject([
        'slug' => 'unverified-project',
        'canonical_path' => '/projects/unverified-project',
        'is_publication_verified' => false,
    ]);

    expect(Project::query()->published()->whereKey($project)->doesntExist())->toBeTrue()
        ->and($project->publicationIssues())->toContain('Confirm that the project information is verified and approved for publication.');

    $this->get(route('projects.show', ['slug' => $project->slug]))->assertNotFound();
});

it('blocks an administrator from saving an incomplete project as published', function () {
    $administrator = User::factory()->create(['is_admin' => true]);
    $this->artisan('selotemna:import-omu-creek')->assertSuccessful();
    $project = Project::query()->sole();

    $this->actingAs($administrator);

    Livewire::test(EditProject::class, ['record' => $project->getRouteKey()])
        ->fillForm([
            'overview' => '',
            'is_publication_verified' => false,
        ])
        ->call('save')
        ->assertHasFormErrors(['publication_status']);

    expect($project->fresh()->publication_status)->toBe(ProjectPublicationStatus::Draft)
        ->and($project->fresh()->is_publication_verified)->toBeFalse()
        ->and(Project::query()->published()->whereKey($project->getKey())->doesntExist())->toBeTrue();
});

it('uploads and persists project media through the structured admin form', function () {
    Storage::fake('public');
    $administrator = User::factory()->create(['is_admin' => true]);
    $image = UploadedFile::fake()->image('project-hero.jpg', 1200, 800);

    $this->actingAs($administrator);

    Livewire::test(CreateProject::class)
        ->fillForm([
            'name' => 'Uploaded Media Project',
            'slug' => 'uploaded-media-project',
            'division' => ProjectDivision::EngineeringConstruction->value,
            'project_type' => 'Construction',
            'status' => ProjectStatus::Upcoming->value,
            'publication_status' => ProjectPublicationStatus::Draft->value,
            'summary' => 'A private project used to verify managed media uploads.',
            'currency' => 'NGN',
            'locations' => [[
                'name' => 'Lagos',
                'region' => 'Lagos State',
                'country' => 'Nigeria',
                'sort_order' => 0,
            ]],
            'media' => [[
                'kind' => ProjectMediaKind::Image->value,
                'role' => ProjectMediaRole::HeroImage->value,
                'path' => [$image],
                'alt_text' => 'Uploaded project hero',
                'sort_order' => 0,
            ]],
            'plotOptions' => [],
            'charges' => [],
            'documentStages' => [],
            'infrastructure' => [],
            'policies' => [],
            'faqGroups' => [],
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $project = Project::query()->where('slug', 'uploaded-media-project')->with('media')->firstOrFail();
    $storedMedia = $project->media->sole();

    expect($storedMedia->role)->toBe(ProjectMediaRole::HeroImage)
        ->and($storedMedia->alt_text)->toBe('Uploaded project hero');
    Storage::disk('public')->assertExists($storedMedia->path);
});

it('retains responsive and accessible navigation semantics in source and rendered markup', function () {
    $projects = $this->get(route('projects.index'))->assertOk()->getContent();
    $omuCreek = $this->get(route('omu-creek'))->assertOk()->getContent();
    $javascript = file_get_contents(resource_path('js/app.js'));

    expect($projects)->toContain('role="tablist"')
        ->toContain('role="tab"')
        ->toContain('aria-selected="true"')
        ->toContain('tabindex="-1"')
        ->toContain('grid grid-cols-3')
        ->and($omuCreek)->toContain('sticky top-20')
        ->toContain('data-page-section-current')
        ->toContain('data-page-section-link')
        ->toContain('href="#overview"')
        ->toContain('href="#pricing"')
        ->toContain('href="#purchase-guide"')
        ->and($javascript)->toContain("event.key === 'ArrowRight'")
        ->toContain("event.key === 'ArrowLeft'")
        ->toContain("event.key === 'Home'")
        ->toContain("event.key === 'End'")
        ->toContain("setAttribute('aria-current', 'location')")
        ->toContain('new IntersectionObserver');
});
