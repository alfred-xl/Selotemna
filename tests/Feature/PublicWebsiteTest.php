<?php

use App\Mail\InspectionRequestMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

function publicRoutes(): array
{
    return [
        'home' => 'Developing real estate. Delivering engineered solutions.',
        'about' => 'Real estate development and project delivery under one corporate direction.',
        'real-estate-development' => 'Land, property and development opportunities.',
        'omu-creek' => 'Omu Creek',
        'engineering-construction' => 'Engineering and construction shaped around clear project requirements.',
        'projects.index' => 'Projects across development and construction.',
        'faq' => 'Omu Creek questions, answered.',
        'inspections.create' => 'Request a property inspection.',
        'contact' => 'Start with the right Selotemna pathway.',
    ];
}

function validInspectionData(): array
{
    return [
        'full_name' => 'Test Visitor',
        'phone' => '+234 800 000 0000',
        'email' => 'visitor@example.test',
        'whatsapp' => '',
        'interest' => 'Omu Creek',
        'preferred_date' => now()->addDay()->toDateString(),
        'preferred_time' => 'Morning',
        'contact_method' => 'Telephone',
        'message' => 'Please follow up about this request.',
        'consent' => '1',
    ];
}

it('serves every named public GET route', function () {
    foreach (publicRoutes() as $routeName => $heading) {
        $this->get(route($routeName))->assertOk()->assertSee($heading);
    }
});

it('renders exactly one h1 and unique metadata on every page', function () {
    $titles = [];
    $descriptions = [];

    foreach (array_keys(publicRoutes()) as $routeName) {
        $content = $this->get(route($routeName))->getContent();
        expect(substr_count($content, '<h1'))->toBe(1);

        preg_match('/<title>(.*?)<\/title>/s', $content, $title);
        preg_match('/<meta name="description" content="([^"]+)">/', $content, $description);
        $titles[] = $title[1] ?? null;
        $descriptions[] = $description[1] ?? null;
        expect($content)->toContain('<meta name="robots" content="noindex, nofollow">');
    }

    expect($titles)->not->toContain(null)->toHaveCount(count(array_unique($titles)))
        ->and($descriptions)->not->toContain(null)->toHaveCount(count(array_unique($descriptions)));
});

it('uses implemented named routes throughout shared navigation', function () {
    $content = $this->get(route('home'))->getContent();

    foreach (array_keys(publicRoutes()) as $routeName) {
        if ($routeName === 'home') {
            continue;
        }

        expect($content)->toContain('href="'.route($routeName));
    }

    expect($content)->toContain('href="'.route('omu-creek').'"')
        ->not->toContain('href="#"');
});

it('marks route-appropriate navigation links as current', function () {
    foreach (['about', 'projects.index', 'faq', 'contact'] as $routeName) {
        $content = $this->get(route($routeName))->getContent();
        $url = preg_quote(route($routeName), '/');

        expect((bool) preg_match('/href="'.$url.'"\s+class="nav-link nav-link-active"\s+aria-current="page"/s', $content))->toBeTrue();
    }

    $divisionPage = $this->get(route('engineering-construction'))->getContent();
    $divisionUrl = preg_quote(route('engineering-construction'), '/');
    expect((bool) preg_match('/href="'.$divisionUrl.'"\s+class="dropdown-link"\s+data-divisions-link\s+aria-current="page"/s', $divisionPage))->toBeTrue();
});

it('provides an accessible divisions dropdown and mobile drawer hooks', function () {
    $content = $this->get(route('home'))->getContent();

    expect($content)->toContain('data-divisions-toggle')
        ->toContain('aria-controls="desktop-divisions-menu"')
        ->toContain('aria-expanded="false"')
        ->toContain('data-divisions-menu')
        ->toContain('aria-controls="mobile-navigation"')
        ->toContain('aria-modal="true"');

    $script = file_get_contents(resource_path('js/app.js'));
    expect($script)->toContain("event.key === 'Escape'")
        ->toContain("event.key !== 'ArrowDown'")
        ->toContain('!dropdown.contains(event.target)');
});

it('shows exactly two primary homepage divisions and no retired architecture', function () {
    $content = $this->get(route('home'))->getContent();

    expect(substr_count($content, 'data-primary-division'))->toBe(2)
        ->and($content)->toContain('Real Estate Development')
        ->toContain('Engineering &amp; Construction')
        ->not->toContain('Property Management')
        ->not->toContain('Land Sales')
        ->not->toContain('House Sales')
        ->not->toContain('Shortlet');
});

it('shows exactly the four approved homepage faq questions', function () {
    $content = $this->get(route('home'))->getContent();
    preg_match('/data-home-faq.*?<\/section>/s', $content, $section);

    expect(substr_count($section[0], 'data-faq-trigger'))->toBe(4);

    foreach (config('selotemna.homepage_faq_ids') as $id) {
        $question = collect(config('selotemna.faq_groups'))->flatMap(fn (array $group) => $group['items'])->firstWhere('id', $id)['question'];
        expect($section[0])->toContain($question);
    }

    expect($section[0])->not->toContain('Where is Omu Creek Estate located?')
        ->not->toContain('Can I pay in installments?');
});

it('renders all fifteen grouped faq answers with globally unique ids', function () {
    $content = $this->get(route('faq'))->getContent();
    preg_match_all('/data-faq-trigger/', $content, $triggers);
    preg_match_all('/\bid="([^"]+)"/', $content, $ids);

    expect($triggers[0])->toHaveCount(15)
        ->and($ids[1])->toHaveCount(count(array_unique($ids[1])));

    foreach (config('selotemna.faq_groups') as $group) {
        expect($content)->toContain($group['label']);
        foreach ($group['items'] as $item) {
            expect($content)->toContain($item['question'])->toContain($item['answer']);
        }
    }
});

it('renders the complete verified Omu Creek facts and corrected survey charge', function () {
    $response = $this->get(route('omu-creek'));

    $response->assertOk()
        ->assertSee('Lagos State Government Allocation')
        ->assertSee('Commercial plot')
        ->assertSee('1,000 sqm')
        ->assertSee('₦50,000,000')
        ->assertSee('500 sqm')
        ->assertSee('₦25,000,000')
        ->assertSee('300 sqm')
        ->assertSee('₦15,000,000')
        ->assertSee('₦50,000 per sqm')
        ->assertSee('Registered Survey')
        ->assertSee('₦1,500,000')
        ->assertSee('Prices exclude applicable taxes. Availability and property information are subject to confirmation.');
});

it('does not publish obsolete Omu Creek title or survey information', function () {
    foreach (['home', 'omu-creek', 'faq'] as $routeName) {
        $content = $this->get(route($routeName))->getContent();
        expect($content)->not->toContain('Certificate of Occupancy')
            ->not->toContain('C of O')
            ->not->toContain('Registered Survey: ₦15,000,000');
    }
});

it('does not assign an unconfirmed project status to Omu Creek', function () {
    $content = $this->get(route('omu-creek'))->getContent();

    expect($content)->not->toContain('Ongoing Project')
        ->not->toContain('Completed Project')
        ->not->toContain('Upcoming Project');
});

it('keeps project tabs progressively enhanced and keyboard accessible', function () {
    $content = $this->get(route('projects.index'))->getContent();
    preg_match_all('/\sdata-project-tab>/', $content, $tabs);
    preg_match_all('/\sdata-project-panel>/', $content, $panels);

    expect($tabs[0])->toHaveCount(3)
        ->and($panels[0])->toHaveCount(3)
        ->and($content)->toContain('role="tablist"')
        ->toContain('role="tabpanel"')
        ->not->toContain('data-project-panel hidden');

    $script = file_get_contents(resource_path('js/app.js'));
    foreach (['ArrowLeft', 'ArrowRight', 'Home', 'End'] as $key) {
        expect($script)->toContain($key);
    }
});

it('marks temporary project records and never creates fictional detail links', function () {
    $content = $this->get(route('projects.index'))->getContent();
    preg_match_all('/<article[^>]*data-project-card[^>]*>.*?<\/article>/s', $content, $cards);

    expect($cards[0])->toHaveCount(6);
    foreach ($cards[0] as $card) {
        expect($card)->toContain('Development-only layout sample')->not->toContain('<a ');
    }

    foreach (config('selotemna.temporary_projects') as $group) {
        foreach ($group['items'] as $item) {
            expect($item['is_temporary'])->toBeTrue();
        }
    }

    expect(Route::has('projects.show'))->toBeFalse();
});

it('removes temporary projects and renders useful empty states in production', function () {
    $this->app->detectEnvironment(fn (): string => 'production');
    $content = $this->get(route('projects.index'))->getContent();

    expect($content)->not->toContain('Layout Sample')
        ->and(substr_count($content, 'data-project-empty-state'))->toBe(3)
        ->and($content)->toContain('Discuss a development or construction requirement with Selotemna.');
});

it('never renders empty contact links or hash targets', function () {
    foreach (['phone', 'whatsapp', 'email', 'address', 'business_hours'] as $key) {
        config()->set("selotemna.{$key}", null);
    }

    foreach (array_keys(publicRoutes()) as $routeName) {
        $content = $this->get(route($routeName))->getContent();
        expect($content)->not->toContain('href="#"')
            ->not->toContain('tel:')
            ->not->toContain('wa.me')
            ->not->toContain('mailto:');
    }
});

it('renders configured contact channels with safe urls', function () {
    config()->set('selotemna.phone', '+234 800 000 0000');
    config()->set('selotemna.whatsapp', '+2348000000000');
    config()->set('selotemna.email', 'contact@selotemna.test');
    config()->set('selotemna.address', 'Verified office address');
    config()->set('selotemna.business_hours', 'Verified business hours');

    $this->get(route('contact'))
        ->assertSee('tel:+2348000000000', false)
        ->assertSee('https://wa.me/2348000000000', false)
        ->assertSee('mailto:contact@selotemna.test', false)
        ->assertSee('Verified office address')
        ->assertSee('Verified business hours');
});

it('uses request language and a safe fallback when delivery is unavailable', function () {
    config()->set('selotemna.email', 'inspections@selotemna.test');
    config()->set('mail.default', 'log');
    $response = $this->get(route('inspections.create'));

    $response->assertOk()
        ->assertSee('submitting this request does not automatically confirm an appointment')
        ->assertSee('data-inspection-contact-fallback', false)
        ->assertDontSee('data-inspection-form', false)
        ->assertDontSee('Inspection confirmed');
});

it('validates inspection requests and preserves submitted input', function () {
    Mail::fake();
    config()->set('selotemna.email', 'inspections@selotemna.test');

    $this->from(route('inspections.create'))
        ->post(route('inspections.store'), ['full_name' => 'Test Visitor'])
        ->assertRedirect(route('inspections.create'))
        ->assertSessionHasErrors(['phone', 'interest', 'preferred_date', 'consent'])
        ->assertSessionHasInput('full_name', 'Test Visitor');

    Mail::assertNothingSent();
});

it('emails a valid inspection request and confirms receipt without confirming an appointment', function () {
    Mail::fake();
    config()->set('selotemna.email', 'inspections@selotemna.test');
    config()->set('mail.default', 'smtp');

    $this->post(route('inspections.store'), validInspectionData())
        ->assertRedirect(route('inspections.create'))
        ->assertSessionHas('status', fn (string $status): bool => str_contains($status, 'request has been received') && str_contains($status, 'does not confirm an appointment'));

    Mail::assertSent(InspectionRequestMail::class, fn (InspectionRequestMail $mail): bool => $mail->hasTo('inspections@selotemna.test') && $mail->details['interest'] === 'Omu Creek');
});

it('rate limits inspection submissions', function () {
    Mail::fake();
    config()->set('selotemna.email', 'inspections@selotemna.test');
    config()->set('mail.default', 'smtp');

    foreach (range(1, 5) as $attempt) {
        $this->post(route('inspections.store'), validInspectionData())->assertRedirect(route('inspections.create'));
    }

    $this->post(route('inspections.store'), validInspectionData())->assertStatus(429);
    Mail::assertSentCount(5);
});

it('does not accept inspection posts without a verified delivery destination', function () {
    Mail::fake();
    config()->set('selotemna.email', null);
    config()->set('mail.default', 'smtp');

    $this->post(route('inspections.store'), validInspectionData())->assertNotFound();
    Mail::assertNothingSent();
});
