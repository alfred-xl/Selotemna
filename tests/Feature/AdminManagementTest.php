<?php

use App\Filament\Resources\ContactEnquiries\Pages\EditContactEnquiry;
use App\Filament\Resources\InspectionRequests\Pages\EditInspectionRequest;
use App\Filament\Resources\Projects\Pages\EditProject;
use App\Models\ContactEnquiry;
use App\Models\InspectionRequest;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Str;
use Livewire\Livewire;

it('renders the project management screens for an administrator', function () {
    $admin = User::factory()->create(['is_admin' => true]);

    $this->artisan('selotemna:import-omu-creek')->assertSuccessful();
    $project = Project::query()->sole();

    $this->actingAs($admin)->get('/admin/projects')->assertOk()->assertSee('Omu Creek');
    $this->actingAs($admin)->get("/admin/projects/{$project->getKey()}")->assertOk()->assertSee('Project preview');
    $this->actingAs($admin)->get("/admin/projects/{$project->getKey()}/edit")->assertOk()->assertSee('Project content');
});

it('renders protected lead queues and their workflow screens', function () {
    $admin = User::factory()->create(['is_admin' => true]);

    $inspection = InspectionRequest::query()->create([
        'submission_token' => (string) Str::uuid(),
        'status' => InspectionRequest::STATUS_NEW,
        'project_slug' => 'omu-creek',
        'project_name' => 'Omu Creek',
        'full_name' => 'Ada Customer',
        'phone' => '+2348012345678',
        'email' => 'ada@example.test',
        'preferred_contact_method' => 'phone',
        'preferred_date' => now()->addWeek()->toDateString(),
        'preferred_time' => 'Flexible',
        'consented_at' => now(),
    ]);

    $enquiry = ContactEnquiry::query()->create([
        'submission_token' => (string) Str::uuid(),
        'status' => ContactEnquiry::STATUS_NEW,
        'full_name' => 'Tunde Prospect',
        'phone' => '+2348087654321',
        'email' => 'tunde@example.test',
        'preferred_contact_method' => 'email',
        'interest_type' => 'Both',
        'enquiry_type' => 'Real estate',
        'message' => 'I would like to discuss the available options.',
        'consented_at' => now(),
    ]);

    $this->actingAs($admin)->get('/admin')->assertOk()->assertSee('New inspections')->assertSee('New enquiries');
    $this->actingAs($admin)->get('/admin/inspection-requests')->assertOk()->assertSee($inspection->reference);
    $this->actingAs($admin)->get("/admin/inspection-requests/{$inspection->getKey()}")->assertOk()->assertSee('Inspection request');
    $this->actingAs($admin)->get("/admin/inspection-requests/{$inspection->getKey()}/edit")->assertOk()->assertSee('Lead workflow');
    $this->actingAs($admin)->get('/admin/contact-enquiries')->assertOk()->assertSee($enquiry->reference);
    $this->actingAs($admin)->get("/admin/contact-enquiries/{$enquiry->getKey()}")->assertOk()->assertSee('Enquiry');
    $this->actingAs($admin)->get("/admin/contact-enquiries/{$enquiry->getKey()}/edit")->assertOk()->assertSee('Lead workflow');
});

it('keeps lead creation and deletion outside the admin workflow', function () {
    expect(route('filament.admin.resources.inspection-requests.index'))->not->toBeEmpty()
        ->and(route('filament.admin.resources.contact-enquiries.index'))->not->toBeEmpty()
        ->and(Route::has('filament.admin.resources.inspection-requests.create'))->toBeFalse()
        ->and(Route::has('filament.admin.resources.contact-enquiries.create'))->toBeFalse()
        ->and(InspectionRequest::statusOptions())->toBe([
            'new' => 'New',
            'contacted' => 'Contacted',
            'closed' => 'Closed',
        ])
        ->and(ContactEnquiry::statusOptions())->toBe([
            'new' => 'New',
            'contacted' => 'Contacted',
            'closed' => 'Closed',
        ]);
});

it('updates lead workflow state and records the first handled time', function () {
    $admin = User::factory()->create(['is_admin' => true]);

    $inspection = InspectionRequest::query()->create([
        'submission_token' => (string) Str::uuid(),
        'status' => InspectionRequest::STATUS_NEW,
        'project_slug' => 'omu-creek',
        'project_name' => 'Omu Creek',
        'full_name' => 'Ada Customer',
        'phone' => '+2348012345678',
        'preferred_contact_method' => 'phone',
        'preferred_date' => now()->addWeek()->toDateString(),
        'consented_at' => now(),
    ]);

    $enquiry = ContactEnquiry::query()->create([
        'submission_token' => (string) Str::uuid(),
        'status' => ContactEnquiry::STATUS_NEW,
        'full_name' => 'Tunde Prospect',
        'phone' => '+2348087654321',
        'preferred_contact_method' => 'phone',
        'interest_type' => 'Land',
        'enquiry_type' => 'Real estate',
        'message' => 'Please contact me.',
        'consented_at' => now(),
    ]);

    $this->actingAs($admin);

    Livewire::test(EditInspectionRequest::class, ['record' => $inspection->getRouteKey()])
        ->fillForm([
            'status' => InspectionRequest::STATUS_CONTACTED,
            'admin_notes' => 'Called and confirmed the inspection window.',
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    Livewire::test(EditContactEnquiry::class, ['record' => $enquiry->getRouteKey()])
        ->fillForm([
            'status' => ContactEnquiry::STATUS_CLOSED,
            'admin_notes' => 'Qualified and routed to the real-estate team.',
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($inspection->fresh()->status)->toBe(InspectionRequest::STATUS_CONTACTED)
        ->and($inspection->fresh()->handled_at)->not->toBeNull()
        ->and($inspection->fresh()->admin_notes)->toContain('Called')
        ->and($enquiry->fresh()->status)->toBe(ContactEnquiry::STATUS_CLOSED)
        ->and($enquiry->fresh()->handled_at)->not->toBeNull()
        ->and($enquiry->fresh()->admin_notes)->toContain('Qualified');
});

it('saves project editorial changes without losing structured content', function () {
    $admin = User::factory()->create(['is_admin' => true]);

    $this->artisan('selotemna:import-omu-creek')->assertSuccessful();
    $project = Project::query()->withCount(['locations', 'plotOptions', 'faqGroups'])->sole();

    $this->actingAs($admin);

    Livewire::test(EditProject::class, ['record' => $project->getRouteKey()])
        ->fillForm([
            'overview' => 'Updated safely from the structured admin editor.',
            'seo_title' => 'Omu Creek Land Project',
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $updated = $project->fresh()->loadCount(['locations', 'plotOptions', 'faqGroups']);

    expect($updated->overview)->toBe('Updated safely from the structured admin editor.')
        ->and($updated->seo_title)->toBe('Omu Creek Land Project')
        ->and($updated->locations_count)->toBe($project->locations_count)
        ->and($updated->plot_options_count)->toBe($project->plot_options_count)
        ->and($updated->faq_groups_count)->toBe($project->faq_groups_count);
});
