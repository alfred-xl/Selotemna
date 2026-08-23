<?php

use App\Mail\InspectionRequestMail;
use App\Models\InspectionRequest;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

function publicRoutes(): array
{
    return [
        'home' => 'Developing places. Building with purpose.',
        'about' => 'A focused company for property opportunities and project requirements.',
        'real-estate-development' => 'Explore property opportunities with the facts in view.',
        'omu-creek' => 'Omu Creek',
        'engineering-construction' => 'Bring your project requirement into focus.',
        'projects.index' => 'One published project. A clear view of its current stage.',
        'faq' => 'Clear answers before your next step.',
        'inspections.create' => 'Request an Omu Creek inspection.',
        'contact' => 'Start with the right conversation.',
    ];
}

function validInspectionData(): array
{
    return [
        'submission_token' => (string) Str::uuid(),
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

it('provides accessible desktop navigation and a viewport-level mobile drawer', function () {
    $content = $this->get(route('home'))->getContent();
    $inspectionUrl = preg_quote(route('inspections.create'), '/');

    expect($content)->toContain('data-divisions-toggle')
        ->toContain('aria-controls="desktop-divisions-menu"')
        ->toContain('aria-expanded="false"')
        ->toContain('data-divisions-menu')
        ->toContain('aria-controls="mobile-navigation"')
        ->toContain('aria-modal="true"')
        ->toContain('data-menu-root inert')
        ->not->toContain('data-mobile-contact-bar')
        ->and(strpos($content, '</header>'))->toBeLessThan(strpos($content, 'data-menu-root'))
        ->and((bool) preg_match('/href="'.$inspectionUrl.'"\s+class="mobile-nav-link"[^>]*data-menu-link/s', $content))->toBeTrue();

    $script = file_get_contents(resource_path('js/app.js'));
    expect($script)->toContain("event.key === 'Escape'")
        ->toContain("event.key !== 'ArrowDown'")
        ->toContain('!dropdown.contains(event.target)')
        ->toContain("menuRoot.removeAttribute('inert')")
        ->toContain("menuRoot.setAttribute('inert', '')")
        ->not->toContain('mobileContactBar');
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

it('uses the approved Omu Creek videos in their intended locations without autoplay', function () {
    $featuredProperty = config('selotemna.featured_property');
    $homepage = $this->get(route('home'))->assertOk()->getContent();
    $detailPage = $this->get(route('omu-creek'))->assertOk()->getContent();

    expect($featuredProperty['short_video_url'])->toBe('https://pub-0625ccae8b454afab44be786c0943de3.r2.dev/SHORT%20FORM%201.mp4')
        ->and($featuredProperty['video_url'])->toBe('https://pub-0625ccae8b454afab44be786c0943de3.r2.dev/OMU%20CREEK%202%20VIDEO%201.mp4')
        ->and($homepage)->toContain($featuredProperty['short_video_url'])
        ->toContain('data-event="omu_creek_short_video_play"')
        ->and($detailPage)->toContain($featuredProperty['video_url'])
        ->toContain('data-event="omu_creek_video_play"');

    preg_match('/<video[^>]*data-event="omu_creek_short_video_play"[^>]*>/s', $homepage, $homepageVideo);
    preg_match('/<video[^>]*data-event="omu_creek_video_play"[^>]*>/s', $detailPage, $detailVideo);

    foreach ([$homepageVideo[0], $detailVideo[0]] as $video) {
        expect($video)->toContain('controls')
            ->toContain('playsinline')
            ->toContain('preload="metadata"')
            ->not->toContain('autoplay')
            ->not->toContain('loop');
    }
});

it('does not publish obsolete Omu Creek title or survey information', function () {
    foreach (['home', 'omu-creek', 'faq'] as $routeName) {
        $content = $this->get(route($routeName))->getContent();
        expect($content)->not->toContain('Certificate of Occupancy')
            ->not->toContain('C of O')
            ->not->toContain('Registered Survey: ₦15,000,000');
    }
});

it('publishes Omu Creek with its confirmed upcoming status only', function () {
    $content = $this->get(route('omu-creek'))->assertOk()->getContent();

    expect(config('selotemna.featured_property.status'))->toBe('Upcoming Project')
        ->and($content)->toContain('Upcoming Project')
        ->not->toContain('Ongoing Project')
        ->not->toContain('Completed Project')
        ->toContain('Availability and property information are subject to confirmation.');
});

it('does not render unnecessary tab controls for the single published project category', function () {
    $content = $this->get(route('projects.index'))->getContent();
    preg_match_all('/\sdata-project-tab>/', $content, $tabs);
    preg_match_all('/\sdata-project-panel>/', $content, $panels);

    expect($tabs[0])->toHaveCount(0)
        ->and($panels[0])->toHaveCount(0)
        ->and($content)->not->toContain('role="tablist"')
        ->not->toContain('role="tabpanel"')
        ->toContain('Upcoming Projects');
});

it('publishes only Omu Creek and links to its existing detail page', function () {
    $content = $this->get(route('projects.index'))->getContent();
    preg_match_all('/<article[^>]*data-project-card[^>]*>.*?<\/article>/s', $content, $cards);

    $projects = collect(config('selotemna.projects'))->flatMap(fn (array $group): array => $group['items']);

    expect($cards[0])->toHaveCount(1)
        ->and($cards[0][0])->toContain('Omu Creek')
        ->toContain('Upcoming Project')
        ->toContain('href="'.route('omu-creek').'"')
        ->and($projects)->toHaveCount(1)
        ->and($projects->first()['name'])->toBe('Omu Creek')
        ->and(config('selotemna.temporary_projects'))->toBeNull()
        ->and($content)->not->toContain('Layout Sample')
        ->not->toContain('Development-only')
        ->and(Route::has('projects.show'))->toBeFalse();
});

it('keeps the same verified Omu Creek project in production', function () {
    $this->app->detectEnvironment(fn (): string => 'production');
    $content = $this->get(route('projects.index'))->getContent();

    expect(substr_count($content, 'data-project-card'))->toBe(1)
        ->and($content)->toContain('Omu Creek')
        ->toContain('Upcoming Project')
        ->not->toContain('Layout Sample')
        ->not->toContain('Ongoing Projects')
        ->not->toContain('Completed Projects');
});

it('renders only approved testimonials with confirmed publication permission', function () {
    config()->set('selotemna.testimonials', [
        ['quote' => 'Approved real estate feedback.', 'name' => 'Approved Client', 'division' => 'Real Estate Development', 'service' => 'Omu Creek', 'approved' => true, 'permission_confirmed' => true],
        ['quote' => 'Unapproved feedback.', 'name' => 'Unapproved Client', 'division' => 'Real Estate Development', 'approved' => false, 'permission_confirmed' => true],
        ['quote' => 'No permission feedback.', 'name' => 'Private Client', 'division' => 'Engineering & Construction', 'approved' => true, 'permission_confirmed' => false],
    ]);

    $homepage = $this->get(route('home'))->getContent();
    $engineeringPage = $this->get(route('engineering-construction'))->getContent();

    expect($homepage)->toContain('data-testimonials')
        ->toContain('Approved real estate feedback.')
        ->not->toContain('Unapproved feedback.')
        ->not->toContain('No permission feedback.')
        ->and($engineeringPage)->not->toContain('data-testimonials');
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

it('keeps the database-backed request form available when email delivery is unavailable', function () {
    config()->set('selotemna.email', 'inspections@selotemna.test');
    config()->set('mail.default', 'log');
    $response = $this->get(route('inspections.create'));

    $response->assertOk()
        ->assertSee('submitting it does not automatically confirm an appointment')
        ->assertSee('data-inspection-form', false)
        ->assertDontSee('data-inspection-contact-fallback', false)
        ->assertDontSee('Inspection confirmed');
});

it('validates inspection requests and preserves submitted input', function () {
    Mail::fake();
    config()->set('selotemna.email', 'inspections@selotemna.test');

    $this->from(route('inspections.create'))
        ->post(route('inspections.store'), ['full_name' => 'Test Visitor'])
        ->assertRedirect(route('inspections.create'))
        ->assertSessionHasErrors(['submission_token', 'phone', 'interest', 'preferred_date', 'contact_method', 'consent'])
        ->assertSessionHasInput('full_name', 'Test Visitor');

    Mail::assertNothingSent();
    expect(InspectionRequest::query()->count())->toBe(0);
});

it('emails a valid inspection request and confirms receipt without confirming an appointment', function () {
    Mail::fake();
    config()->set('selotemna.email', 'inspections@selotemna.test');
    config()->set('mail.default', 'smtp');

    $this->post(route('inspections.store'), validInspectionData())
        ->assertRedirect(route('inspections.create'))
        ->assertSessionHas('status', fn (string $status): bool => str_contains($status, 'request has been received and saved') && str_contains($status, 'does not confirm an appointment'))
        ->assertSessionHas('inspection_receipt');

    Mail::assertSent(InspectionRequestMail::class, fn (InspectionRequestMail $mail): bool => $mail->hasTo('inspections@selotemna.test') && $mail->inspection->project_name === 'Omu Creek');
    expect(InspectionRequest::query()->first())
        ->project_slug->toBe('omu-creek')
        ->status->toBe(InspectionRequest::STATUS_NEW)
        ->staff_notified_at->not->toBeNull();
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

it('saves inspection requests without a verified email destination', function () {
    Mail::fake();
    config()->set('selotemna.email', null);
    config()->set('mail.default', 'smtp');

    $this->post(route('inspections.store'), validInspectionData())
        ->assertRedirect(route('inspections.create'))
        ->assertSessionHas('inspection_receipt');

    Mail::assertNothingSent();
    expect(InspectionRequest::query()->count())->toBe(1)
        ->and(InspectionRequest::query()->first()->staff_notified_at)->toBeNull();
});

it('does not duplicate a repeated inspection submission token', function () {
    Mail::fake();
    config()->set('selotemna.email', 'inspections@selotemna.test');
    config()->set('mail.default', 'smtp');
    $data = validInspectionData();

    $this->post(route('inspections.store'), $data)->assertRedirect(route('inspections.create'));
    $this->post(route('inspections.store'), $data)->assertRedirect(route('inspections.create'));

    expect(InspectionRequest::query()->count())->toBe(1);
    Mail::assertSentCount(1);
});

it('keeps a saved inspection request when staff email delivery fails', function () {
    config()->set('selotemna.email', 'inspections@selotemna.test');
    config()->set('mail.default', 'smtp');
    Mail::shouldReceive('to')->once()->andThrow(new RuntimeException('Mail transport unavailable'));

    $this->post(route('inspections.store'), validInspectionData())
        ->assertRedirect(route('inspections.create'))
        ->assertSessionHas('inspection_receipt');

    $inspection = InspectionRequest::query()->sole();

    expect($inspection->notification_failed_at)->not->toBeNull()
        ->and($inspection->staff_notified_at)->toBeNull();
});

it('requires the selected contact channel and rejects a tampered project', function () {
    Mail::fake();
    $data = validInspectionData();
    $data['contact_method'] = 'Email';
    $data['email'] = '';
    $data['interest'] = 'Unpublished project';

    $this->post(route('inspections.store'), $data)
        ->assertSessionHasErrors(['email', 'interest']);

    expect(InspectionRequest::query()->count())->toBe(0);
});

it('renders credited editorial media without presenting it as project proof', function () {
    $this->get(route('inspections.create'))
        ->assertOk()
        ->assertSee('assets/images/selotemna-inspection-consultation.jpg', false)
        ->assertSee('pexels.com/photo/a-man-talking-to-his-clients', false)
        ->assertSee('Photo by Gustavo Fring on Pexels')
        ->assertSee('Editorial image.');

    $this->get(route('contact'))
        ->assertOk()
        ->assertSee('assets/images/selotemna-contact-meeting.jpg', false)
        ->assertSee('pexels.com/photo/business-meeting-in-lagos', false)
        ->assertSee('Photo by Ninthgrid on Pexels')
        ->assertSee('Editorial image.');
});

it('provides progressive motion hooks and reduced-motion protection', function () {
    $homepage = $this->get(route('home'))->assertOk()->getContent();
    $projects = $this->get(route('projects.index'))->assertOk()->getContent();
    $inspection = $this->get(route('inspections.create'))->assertOk()->getContent();
    $css = file_get_contents(resource_path('css/app.css'));
    $script = file_get_contents(resource_path('js/app.js'));

    expect($homepage)->toContain('data-motion-root')
        ->toContain('data-header-sentinel')
        ->toContain('data-hero-sequence')
        ->toContain('data-reveal')
        ->and($projects)->toContain('data-project-card data-reveal')
        ->and($inspection)->toContain('data-inspection-form data-submit-once data-reveal')
        ->and($css)->toContain('--motion-fast:')
        ->toContain('--motion-standard:')
        ->toContain('--motion-reveal:')
        ->toContain('--motion-hero-settle:')
        ->toContain("[data-reveal][data-reveal-state='pending']")
        ->toContain('@media (prefers-reduced-motion: reduce)')
        ->and($script)->toContain("window.matchMedia('(prefers-reduced-motion: reduce)')")
        ->toContain("document.querySelectorAll('[data-reveal]')")
        ->toContain('new IntersectionObserver')
        ->toContain('revealObserver.unobserve(entry.target)')
        ->toContain('typeof Element.prototype.animate');
});
