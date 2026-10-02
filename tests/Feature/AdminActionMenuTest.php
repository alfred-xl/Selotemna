<?php

use App\Filament\Resources\ContactEnquiries\Pages\ListContactEnquiries;
use App\Filament\Resources\ContactEnquiries\Pages\ViewContactEnquiry;
use App\Filament\Resources\InspectionRequests\Pages\ListInspectionRequests;
use App\Filament\Resources\InspectionRequests\Pages\ViewInspectionRequest;
use App\Filament\Resources\PaymentReceipts\Pages\EditPaymentReceipt;
use App\Filament\Resources\PaymentReceipts\Pages\ListPaymentReceipts;
use App\Filament\Resources\PaymentReceipts\Pages\ViewPaymentReceipt;
use App\Filament\Resources\ProjectEnquiries\Pages\EditProjectEnquiry;
use App\Filament\Resources\ProjectEnquiries\Pages\ListProjectEnquiries;
use App\Filament\Resources\ProjectEnquiries\Pages\ViewProjectEnquiry;
use App\Filament\Resources\Projects\Pages\EditProject;
use App\Filament\Resources\Projects\Pages\ListProjects;
use App\Filament\Resources\Projects\Pages\ViewProject;
use App\Filament\Widgets\LatestContactEnquiries;
use App\Filament\Widgets\LatestInspectionRequests;
use App\Models\ContactEnquiry;
use App\Models\InspectionRequest;
use App\Models\PaymentReceipt;
use App\Models\Project;
use App\Models\ProjectEnquiry;
use App\Models\User;
use Filament\Actions\ActionGroup;
use Illuminate\Support\Str;
use Livewire\Features\SupportTesting\Testable;
use Livewire\Livewire;

it('uses one consistent dropdown for every multi-action table row', function () {
    $this->actingAs(User::factory()->create(['is_admin' => true]));

    $tables = [
        ListProjects::class => ['viewProject', 'editProject', 'previewPublicPage', 'viewProjectEnquiries'],
        ListProjectEnquiries::class => ['generateReceipt', 'view', 'edit'],
        ListInspectionRequests::class => ['view', 'edit'],
        ListContactEnquiries::class => ['view', 'edit'],
        ListPaymentReceipts::class => ['view', 'edit', 'previewReceipt', 'downloadReceipt'],
    ];

    foreach ($tables as $page => $expectedActions) {
        $recordActions = Livewire::test($page)->instance()->getTable()->getRecordActions();
        $menu = $recordActions[0] ?? null;

        expect($recordActions)->toHaveCount(1)
            ->and($menu)->toBeInstanceOf(ActionGroup::class)
            ->and($menu->getLabel())->toBe('Actions')
            ->and($menu->getIcon())->toBe('heroicon-o-ellipsis-horizontal-circle')
            ->and($menu->isButton())->toBeTrue()
            ->and($menu->getDropdownPlacement())->toBe('bottom-end')
            ->and(array_keys($menu->getFlatActions()))->toBe($expectedActions);
    }
});

it('uses the same dropdown for every multi-action page header', function () {
    $administrator = User::factory()->create(['is_admin' => true]);
    $this->actingAs($administrator);

    $this->artisan('selotemna:import-omu-creek')->assertSuccessful();
    $project = Project::query()->sole();
    $enquiry = ProjectEnquiry::query()->create([
        'submission_token' => (string) Str::uuid(),
        'status' => ProjectEnquiry::STATUS_NEW,
        'project_id' => $project->getKey(),
        'project_slug' => $project->slug,
        'project_name' => $project->name,
        'plot_size_sqm' => 500,
        'plot_label' => '500 sqm — Standard plot',
        'price_snapshot' => 25000000,
        'currency' => 'NGN',
        'payment_preference' => 'Outright',
        'purchase_timeline' => 'Immediately',
        'full_name' => 'Ada Customer',
        'phone' => '+2348012345678',
        'preferred_contact_method' => 'Telephone',
        'consented_at' => now(),
    ]);
    $receipt = PaymentReceipt::query()->create([
        'project_enquiry_id' => $enquiry->getKey(),
        'project_id' => $project->getKey(),
        'status' => PaymentReceipt::STATUS_DRAFT,
        'customer_name' => 'Ada Customer',
        'customer_phone' => '+2348012345678',
        'project_name' => $project->name,
        'project_slug' => $project->slug,
        'currency' => 'NGN',
        'amount_received' => 1000000,
        'payment_purpose' => 'Deposit for land',
        'payment_method' => 'Bank transfer',
        'payment_date' => today(),
        'created_by' => $administrator->getKey(),
    ]);

    /** @var array<int, array{0: Testable, 1: array<int, string>}> $pages */
    $pages = [
        [Livewire::test(ViewProject::class, ['record' => $project->getRouteKey()]), ['previewPublicPage', 'edit']],
        [Livewire::test(EditProject::class, ['record' => $project->getRouteKey()]), ['previewPublicPage', 'view', 'delete', 'forceDelete', 'restore']],
        [Livewire::test(ViewProjectEnquiry::class, ['record' => $enquiry->getRouteKey()]), ['generateReceipt', 'edit']],
        [Livewire::test(EditProjectEnquiry::class, ['record' => $enquiry->getRouteKey()]), ['generateReceipt', 'view']],
        [Livewire::test(ViewPaymentReceipt::class, ['record' => $receipt->getRouteKey()]), ['previewReceipt', 'downloadReceipt', 'issueReceipt', 'voidReceipt', 'edit']],
        [Livewire::test(EditPaymentReceipt::class, ['record' => $receipt->getRouteKey()]), ['previewReceipt', 'view']],
    ];

    foreach ($pages as [$component, $expectedActions]) {
        $headerActions = $component->instance()->getCachedHeaderActions();
        $menu = $headerActions[0] ?? null;

        expect($headerActions)->toHaveCount(1)
            ->and($menu)->toBeInstanceOf(ActionGroup::class)
            ->and($menu->getLabel())->toBe('Actions')
            ->and($menu->getIcon())->toBe('heroicon-o-ellipsis-horizontal-circle')
            ->and($menu->isButton())->toBeTrue()
            ->and($menu->getDropdownPlacement())->toBe('bottom-end')
            ->and(array_keys($menu->getFlatActions()))->toBe($expectedActions);
    }

    $pages[1][0]
        ->assertActionVisible('delete')
        ->assertActionHidden('forceDelete')
        ->assertActionHidden('restore');

    $pages[4][0]
        ->assertActionVisible('issueReceipt')
        ->assertActionVisible('edit')
        ->assertActionHidden('downloadReceipt')
        ->assertActionHidden('voidReceipt');
});

it('keeps single page and widget actions outside dropdowns', function () {
    $this->actingAs(User::factory()->create(['is_admin' => true]));

    $contact = ContactEnquiry::query()->create([
        'submission_token' => (string) Str::uuid(),
        'status' => ContactEnquiry::STATUS_NEW,
        'full_name' => 'Tunde Prospect',
        'phone' => '+2348087654321',
        'preferred_contact_method' => 'phone',
        'enquiry_type' => 'Real estate',
        'message' => 'Please contact me.',
        'consented_at' => now(),
    ]);
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

    $singleActionSets = [
        Livewire::test(ListProjects::class)->instance()->getCachedHeaderActions(),
        Livewire::test(ListPaymentReceipts::class)->instance()->getCachedHeaderActions(),
        Livewire::test(ViewContactEnquiry::class, ['record' => $contact->getRouteKey()])->instance()->getCachedHeaderActions(),
        Livewire::test(ViewInspectionRequest::class, ['record' => $inspection->getRouteKey()])->instance()->getCachedHeaderActions(),
        Livewire::test(LatestContactEnquiries::class)->instance()->getTable()->getHeaderActions(),
        Livewire::test(LatestInspectionRequests::class)->instance()->getTable()->getHeaderActions(),
    ];

    foreach ($singleActionSets as $actions) {
        expect($actions)->toHaveCount(1)
            ->and($actions[0])->not->toBeInstanceOf(ActionGroup::class);
    }
});
