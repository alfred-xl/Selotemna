<?php

use App\Enums\ProjectPublicationStatus;
use App\Models\Project;

it('publishes project and FAQ edits made through the managed content store', function () {
    $this->artisan('selotemna:import-omu-creek')->assertSuccessful();

    $project = Project::query()->with('faqGroups.faqs')->sole();
    $project->update([
        'summary' => 'A managed catalogue summary for the Omu Creek project.',
        'overview' => 'A managed public overview maintained through the admin dashboard.',
        'seo_title' => 'Managed Omu Creek Title | Selotemna',
    ]);

    $featuredFaq = $project->faqGroups
        ->flatMap->faqs
        ->firstWhere('is_featured', true);
    $featuredFaq->update(['question' => 'What managed information is published for Omu Creek?']);

    $this->get(route('home'))
        ->assertOk()
        ->assertSee('A managed public overview maintained through the admin dashboard.')
        ->assertSee('What managed information is published for Omu Creek?');

    $this->get(route('projects.index'))
        ->assertOk()
        ->assertSee('A managed catalogue summary for the Omu Creek project.');

    $this->get(route('omu-creek'))
        ->assertOk()
        ->assertSee('<title>Managed Omu Creek Title | Selotemna</title>', false)
        ->assertSee('A managed public overview maintained through the admin dashboard.');

    $this->get(route('faq'))
        ->assertOk()
        ->assertSee('What managed information is published for Omu Creek?');
});

it('removes an unpublished managed project and its FAQs from public pages', function () {
    $this->artisan('selotemna:import-omu-creek')->assertSuccessful();

    Project::query()->sole()->update([
        'publication_status' => ProjectPublicationStatus::Draft,
    ]);

    $this->get(route('omu-creek'))->assertNotFound();
    $this->get(route('faq'))->assertNotFound();
    $this->get(route('home'))->assertOk()->assertDontSee('data-home-omu-creek', false);
    $this->get(route('projects.index'))->assertOk()->assertDontSee('data-project-card', false);
    $this->get(route('real-estate-development'))
        ->assertOk()
        ->assertSee('data-development-empty-state', false)
        ->assertSee('No property opportunity is currently published.');
});

it('does not expose a managed project before its scheduled publication time', function () {
    $this->artisan('selotemna:import-omu-creek')->assertSuccessful();

    Project::query()->sole()->update([
        'publication_status' => ProjectPublicationStatus::Published,
        'published_at' => now()->addDay(),
    ]);

    $this->get(route('omu-creek'))->assertNotFound();
    $this->get(route('projects.index'))->assertOk()->assertDontSee('data-project-card', false);
});

it('uses compact responsive project and Omu Creek navigation without horizontal overflow', function () {
    $projects = $this->get(route('projects.index'))->assertOk()->getContent();
    $omuCreek = $this->get(route('omu-creek'))->assertOk()->getContent();

    expect($projects)->toContain('data-project-tab-scroll')
        ->toContain('class="grid grid-cols-3"')
        ->toContain('min-h-12 min-w-0')
        ->not->toContain('min-w-[17.5rem]')
        ->not->toContain('overflow-x-auto pb-1')
        ->and($omuCreek)->toContain('data-page-section-nav')
        ->toContain('sticky top-20')
        ->toContain('page-section-menu group md:hidden')
        ->toContain('On this page')
        ->toContain('data-page-section-current')
        ->toContain('hidden grid-cols-3')
        ->toContain('href="#pricing"')
        ->toContain('href="#purchase-guide"')
        ->toContain('data-financial-disclosures')
        ->toContain('data-policy-disclosures')
        ->toContain('data-purchase-guide-disclosures')
        ->toContain('content-disclosure')
        ->not->toContain('href="#pricing-payments"')
        ->not->toContain('href="#documents-infrastructure"')
        ->not->toContain('href="#allocation-policies"')
        ->not->toContain('href="#payments-charges"')
        ->not->toContain('site-container overflow-x-auto')
        ->and(substr_count($omuCreek, '₦50,000 per sqm'))->toBe(1)
        ->and(substr_count($omuCreek, config('selotemna.featured_property.disclaimer')))->toBe(1);
});
