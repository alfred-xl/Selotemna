<?php

use App\Mail\ContactEnquiryMail;
use App\Mail\InspectionRequestMail;
use App\Models\ContactEnquiry;
use App\Models\InspectionRequest;
use App\Support\SelotemnaContent;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

function publicRoutes(): array
{
    return [
        'home' => 'Developing places. Building with purpose.',
        'about' => 'About',
        'real-estate-development' => 'Real Estate Development',
        'omu-creek' => 'Omu Creek',
        'engineering-construction' => 'Engineering & Construction',
        'projects.index' => 'Projects',
        'faq' => 'Frequently Asked Questions',
        'inspections.create' => 'Request an Inspection',
        'contact' => 'Contact Selotemna',
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

function validContactEnquiryData(): array
{
    return [
        'submission_token' => (string) Str::uuid(),
        'full_name' => 'Contact Visitor',
        'phone' => '+234 800 000 0000',
        'email' => 'visitor@example.test',
        'whatsapp' => '',
        'contact_method' => 'Telephone',
        'interest_type' => 'Both',
        'enquiry_type' => 'General enquiry',
        'project_type' => '',
        'proposed_location' => '',
        'project_stage' => '',
        'scope_summary' => '',
        'message' => 'Please contact me about this enquiry.',
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

it('uses the reusable centred image overlay hero on the approved pages', function () {
    $about = $this->get(route('about'))->assertOk()->getContent();
    preg_match('/<section[^>]*data-page-hero[^>]*>.*?<\/section>/s', $about, $hero);

    expect($hero)->not->toBeEmpty()
        ->and(substr_count($hero[0], '<h1'))->toBe(1)
        ->and($hero[0])->toContain('data-page-hero-variant="overlay"')
        ->toContain('data-page-hero-image')
        ->toContain(asset('assets/images/selotemna-development-aerial.jpg'))
        ->toContain('object-cover')
        ->toContain('data-page-hero-focal-position')
        ->toContain('style="object-position: 62% 52%;"')
        ->toContain('data-page-hero-overlay')
        ->toContain('bg-ink-950/50')
        ->toContain('bg-linear-to-r')
        ->toContain('from-ink-950/80')
        ->toContain('data-page-hero-alignment="center"')
        ->toContain('data-page-hero-size="compact"')
        ->toContain('min-h-[22.5rem]')
        ->toContain('md:min-h-[27.5rem]')
        ->toContain('lg:min-h-[29rem]')
        ->toContain('mx-auto max-w-[43.75rem] text-center')
        ->toContain('text-[clamp(2.5rem,12vw,2.75rem)]')
        ->toContain('md:text-[clamp(3.5rem,7vw,4rem)]')
        ->toContain('max-w-[43.75rem] text-lg leading-8 md:text-xl')
        ->toContain('data-hero-sequence')
        ->toContain('data-hero-item')
        ->toContain('fetchpriority="high"')
        ->toContain('>About</h1>')
        ->toContain('Real estate development, engineering and construction under one company.')
        ->not->toContain('data-page-hero-breadcrumb')
        ->not->toContain('aria-label="Breadcrumb"')
        ->not->toContain('About Selotemna')
        ->not->toContain('Selotemna Limited develops property opportunities');

    config()->set('selotemna.featured_property.short_video_poster', 'https://media.example.test/omu-creek-short-poster.jpg');

    $development = $this->get(route('real-estate-development'))->assertOk()->getContent();
    preg_match('/<section[^>]*data-page-hero[^>]*>.*?<\/section>/s', $development, $developmentHero);

    expect($developmentHero)->not->toBeEmpty()
        ->and(substr_count($developmentHero[0], '<h1'))->toBe(1)
        ->and($developmentHero[0])->toContain('data-page-hero-variant="overlay"')
        ->toContain(asset('assets/images/omu-creek.png'))
        ->toContain('style="object-position: 50% 50%;"')
        ->toContain('data-page-hero-alignment="center"')
        ->toContain('data-page-hero-size="compact"')
        ->toContain('min-h-[22.5rem]')
        ->toContain('lg:min-h-[29rem]')
        ->toContain('>Real Estate Development</h1>')
        ->toContain('Explore Selotemna’s development work and current property opportunities.')
        ->toContain('data-hero-sequence')
        ->toContain('data-hero-item')
        ->toContain('data-page-hero-overlay')
        ->not->toContain('data-page-hero-breadcrumb')
        ->not->toContain('class="eyebrow')
        ->not->toContain('<video')
        ->not->toContain('autoplay');

    $engineering = $this->get(route('engineering-construction'))->assertOk()->getContent();
    preg_match('/<section[^>]*data-page-hero[^>]*>.*?<\/section>/s', $engineering, $engineeringHero);

    expect($engineeringHero)->not->toBeEmpty()
        ->and(substr_count($engineeringHero[0], '<h1'))->toBe(1)
        ->and($engineeringHero[0])->toContain('data-page-hero-variant="overlay"')
        ->toContain(asset('assets/images/selotemna-building-construction.jpg'))
        ->toContain('style="object-position: 38% 48%;"')
        ->toContain('data-page-hero-alignment="center"')
        ->toContain('data-page-hero-size="compact"')
        ->toContain('min-h-[22.5rem]')
        ->toContain('lg:min-h-[29rem]')
        ->toContain('>Engineering &amp; Construction</h1>')
        ->toContain('Share your project requirements and begin a focused engineering or construction conversation.')
        ->toContain('data-hero-sequence')
        ->toContain('data-hero-item')
        ->toContain('data-page-hero-overlay')
        ->not->toContain('data-page-hero-breadcrumb')
        ->not->toContain('class="eyebrow');

    $projects = $this->get(route('projects.index'))->assertOk()->getContent();
    preg_match('/<section[^>]*data-page-hero[^>]*>.*?<\/section>/s', $projects, $projectsHero);

    expect($projectsHero)->not->toBeEmpty()
        ->and(substr_count($projectsHero[0], '<h1'))->toBe(1)
        ->and($projectsHero[0])->toContain('data-page-hero-variant="overlay"')
        ->toContain(asset('assets/images/omu-creek.png'))
        ->toContain('style="object-position: 50% 50%;"')
        ->toContain('data-page-hero-alignment="center"')
        ->toContain('data-page-hero-size="compact"')
        ->toContain('min-h-[22.5rem]')
        ->toContain('lg:min-h-[29rem]')
        ->toContain('>Projects</h1>')
        ->toContain('Explore Selotemna’s development work by project stage.')
        ->toContain('data-hero-sequence')
        ->toContain('data-hero-item')
        ->toContain('data-page-hero-overlay')
        ->not->toContain('data-page-hero-breadcrumb')
        ->not->toContain('class="eyebrow')
        ->not->toContain('<video')
        ->not->toContain('autoplay');

    $faq = $this->get(route('faq'))->assertOk()->getContent();
    expect($faq)->toContain('data-page-hero-variant="overlay"')
        ->toContain('data-page-hero-alignment="center"')
        ->toContain('data-page-hero-size="compact"')
        ->toContain('data-page-hero-overlay');
});

it('uses the branded hero fallback when the construction image is unavailable', function () {
    config()->set('selotemna.media.building_construction', 'assets/images/missing-building-construction.jpg');

    $content = $this->get(route('engineering-construction'))->assertOk()->getContent();
    preg_match('/<section[^>]*data-page-hero[^>]*>.*?<\/section>/s', $content, $hero);

    expect($hero[0])->toContain('data-page-hero-fallback')
        ->toContain('bg-brand-950')
        ->toContain(asset('assets/logo.png'))
        ->toContain('data-page-hero-overlay')
        ->not->toContain('data-page-hero-image');
});

it('uses the development aerial when the Omu Creek hero imagery is unavailable', function () {
    config()->set('selotemna.featured_property.hero_image', null);
    config()->set('selotemna.featured_property.short_video_poster', null);

    foreach (['real-estate-development', 'projects.index'] as $routeName) {
        $content = $this->get(route($routeName))->assertOk()->getContent();
        preg_match('/<section[^>]*data-page-hero[^>]*>.*?<\/section>/s', $content, $hero);

        expect($hero[0])->toContain(asset('assets/images/selotemna-development-aerial.jpg'))
            ->toContain('data-page-hero-image')
            ->not->toContain('data-page-hero-fallback');
    }
});

it('uses the branded About hero fallback when the configured aerial image is unavailable', function () {
    config()->set('selotemna.media.development_aerial', 'assets/images/missing-development-aerial.jpg');

    $content = $this->get(route('about'))->assertOk()->getContent();
    preg_match('/<section[^>]*data-page-hero[^>]*>.*?<\/section>/s', $content, $hero);

    expect($hero[0])->toContain('data-page-hero-variant="overlay"')
        ->toContain('data-page-hero-fallback')
        ->toContain('bg-brand-950')
        ->toContain(asset('assets/logo.png'))
        ->toContain('data-page-hero-overlay')
        ->not->toContain('data-page-hero-image')
        ->not->toContain('fetchpriority="high"');
});

it('renders the simplified About page content, services, audiences and closing actions', function () {
    $content = $this->get(route('about'))->assertOk()->getContent();
    $text = preg_replace('/\s+/', ' ', html_entity_decode(preg_replace('/<[^>]+>/', ' ', $content)));

    expect($content)->toContain('<meta name="description" content="Learn about Selotemna’s real estate development, engineering and construction work, including current property opportunities and project enquiry pathways.">')
        ->toContain('data-about-company')
        ->toContain('data-about-divisions')
        ->toContain('data-about-audiences')
        ->not->toContain('data-about-process')
        ->and($text)->toContain('About Real estate development, engineering and construction under one company.')
        ->toContain('Who we are')
        ->toContain('A company working across property development, engineering and construction.')
        ->toContain('Selotemna Limited is a Nigerian company working across real estate development, engineering and construction. Through its development work, the company presents land and property opportunities, including Omu Creek, its latest project. Through engineering and construction, Selotemna provides a direct starting point for people and organisations preparing project requirements.')
        ->toContain('The available information is presented clearly so visitors can understand the opportunity or requirement and continue towards an appropriate inspection or project conversation.')
        ->toContain('Company registration RC 7361086')
        ->toContain('What we do From property development opportunities to engineering and construction requirements.')
        ->toContain('Explore Selotemna’s development work and published property opportunities. Omu Creek is the latest project and the current opportunity with detailed public information available.')
        ->toContain('Share the proposed site, scope and current stage of an engineering or construction requirement so Selotemna can understand the project and identify the appropriate next conversation.')
        ->toContain('Who we support')
        ->toContain('People and organisations making property or project decisions.')
        ->toContain('Selotemna supports different property interests and project requirements through clear, relevant starting points.')
        ->toContain('Property and land buyers')
        ->toContain('Families and investors')
        ->toContain('Nigerians in the diaspora')
        ->toContain('Businesses and organisations')
        ->toContain('Engineering and construction clients')
        ->toContain('What would you like to discuss?')
        ->toContain('Explore Selotemna’s current development opportunities or continue with an engineering or construction requirement.')
        ->toContain('Explore Real Estate Development')
        ->toContain('Discuss an Engineering or Construction Project')
        ->not->toContain('How to begin')
        ->not->toContain('Choose a division')
        ->not->toContain('two focused public divisions')
        ->and(substr_count($content, 'data-primary-division'))->toBe(2)
        ->and($content)->toContain('href="'.route('real-estate-development').'"')
        ->toContain('href="'.route('engineering-construction').'"')
        ->not->toContain('data-stat');

    $orderedContent = [
        'data-page-hero-variant="overlay"',
        'data-about-company',
        'data-about-divisions',
        'data-about-audiences',
        'What would you like to discuss?',
    ];
    $positions = array_map(fn (string $needle): int|false => strpos($content, $needle), $orderedContent);

    expect($positions)->not->toContain(false)
        ->and($positions)->toBe(collect($positions)->sort()->values()->all());
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
        ->toContain('Services &amp; projects')
        ->toContain('Explore Selotemna’s development opportunities or begin an engineering or construction enquiry.')
        ->not->toContain('data-mobile-contact-bar')
        ->and(strpos($content, '</header>'))->toBeLessThan(strpos($content, 'data-menu-root'))
        ->and((bool) preg_match('/data-divisions-toggle[^>]*>\s*Services/s', $content))->toBeTrue()
        ->and((bool) preg_match('/tracking-\[0\.14em\][^>]*>Services<\/p>/', $content))->toBeTrue()
        ->and((bool) preg_match('/href="'.$inspectionUrl.'"\s+class="mobile-nav-link"[^>]*data-menu-link/s', $content))->toBeTrue();

    $script = file_get_contents(resource_path('js/app.js'));
    expect($script)->toContain("event.key === 'Escape'")
        ->toContain("event.key !== 'ArrowDown'")
        ->toContain('!dropdown.contains(event.target)')
        ->toContain("menuRoot.removeAttribute('inert')")
        ->toContain("menuRoot.setAttribute('inert', '')")
        ->not->toContain('mobileContactBar');
});

it('uses visitor-facing service labels on the service introductions', function () {
    $development = $this->get(route('real-estate-development'))->assertOk()->getContent();
    $engineering = $this->get(route('engineering-construction'))->assertOk()->getContent();

    expect($development)->toContain('Our development work')
        ->not->toContain('Division overview')
        ->and($engineering)->toContain('Engineering &amp; Construction')
        ->not->toContain('Division introduction');
});

it('renders the streamlined Real Estate Development page and one final conversion choice', function () {
    $content = $this->get(route('real-estate-development'))->assertOk()->getContent();
    $text = preg_replace('/\s+/', ' ', html_entity_decode(preg_replace('/<[^>]+>/', ' ', $content)));

    expect($content)->toContain('<meta name="description" content="Explore Selotemna’s real estate development work and Omu Creek, its latest project and current property opportunity with detailed public information.">')
        ->toContain('data-development-introduction')
        ->toContain('data-home-omu-creek')
        ->and(substr_count($content, 'data-home-omu-creek'))->toBe(1)
        ->and($text)->toContain('Our development work Development opportunities presented with the facts in view.')
        ->toContain('Selotemna has undertaken previous real estate development projects. Omu Creek is the latest project and the current opportunity with detailed public information available.')
        ->toContain('What you can review Published project information Current plot sizes and outright prices Title and documentation information Inspection and enquiry options')
        ->toContain('View plot sizes and current outright prices')
        ->toContain('Interested in Omu Creek?')
        ->toContain('Review the complete project information or request an inspection with your preferred date and contact details.')
        ->toContain('Request an Inspection')
        ->toContain('View Full Project Details')
        ->toContain('Submitting an inspection request does not automatically confirm an appointment.')
        ->not->toContain('Evaluate the opportunity')
        ->not->toContain('Enquiries and inspections')
        ->not->toContain('Move from interest to an informed next step.')
        ->not->toContain('Continue with Omu Creek or a direct enquiry.')
        ->and($content)->not->toContain('plain-panel')
        ->toContain('href="'.route('inspections.create', ['interest' => 'Omu Creek']).'"')
        ->toContain('href="'.route('omu-creek').'"');
});

it('renders the focused Engineering and Construction enquiry page', function () {
    config()->set('selotemna.whatsapp', '+2348000000000');

    $content = $this->get(route('engineering-construction'))->assertOk()->getContent();
    $text = preg_replace('/\s+/', ' ', html_entity_decode(preg_replace('/<[^>]+>/', ' ', $content)));

    expect($content)->toContain('<meta name="description" content="Share an engineering or construction project requirement with Selotemna, including the proposed location, current stage, site and available scope information.">')
        ->toContain('data-engineering-introduction')
        ->toContain('data-engineering-process')
        ->toContain(asset('assets/images/selotemna-building-construction.jpg'))
        ->toContain(asset('assets/images/selotemna-earthworks-truck.jpg'))
        ->and(substr_count($content, asset('assets/images/selotemna-building-construction.jpg')))->toBe(1)
        ->and($text)->toContain('Starting a project enquiry Start with the information you already have.')
        ->toContain('Share the proposed project, location, current stage and available scope information so Selotemna can understand the requirement and identify the important follow-up questions.')
        ->toContain('Information that helps begin the discussion Project type Proposed location Current project stage Available scope information Site information Preferred contact method')
        ->toContain('What happens next From an initial enquiry to a clearer project conversation.')
        ->toContain('The first conversation is intended to establish context. It does not promise a scope, programme or delivery outcome before the available information has been reviewed.')
        ->toContain('Share the requirement Provide the project type, proposed location, current stage and information already available.')
        ->toContain('The information is reviewed Selotemna reviews the requirement and identifies any important follow-up questions.')
        ->toContain('Continue the conversation Continue through the appropriate contact channel with a clearer understanding of what should be discussed next.')
        ->toContain('Have a project requirement to discuss?')
        ->toContain('Share the proposed project type, location, current stage and available scope information with Selotemna.')
        ->toContain('Start a Project Enquiry')
        ->toContain('Chat on WhatsApp')
        ->not->toContain('Enquiry pathway')
        ->not->toContain('Move from an initial requirement to a focused next step.')
        ->not->toContain('Engineering & Construction experiences')
        ->not->toContain('Feedback relevant to project requirements.')
        ->not->toContain('>About Selotemna</a>')
        ->not->toContain('Structural engineering')
        ->not->toContain('Civil engineering')
        ->not->toContain('Turnkey construction')
        ->and($content)->toContain('href="'.route('contact').'"')
        ->toContain('href="https://wa.me/2348000000000"');
});

it('uses one verified secondary contact action or hides it when unavailable', function () {
    config()->set('selotemna.whatsapp', null);
    config()->set('selotemna.phone', '+234 800 000 0000');

    $phoneContent = $this->get(route('engineering-construction'))->assertOk()->getContent();
    expect($phoneContent)->toContain('Call Selotemna')
        ->toContain('href="tel:+2348000000000"')
        ->not->toContain('Chat on WhatsApp');

    config()->set('selotemna.phone', null);
    config()->set('selotemna.phones', []);

    $noContactContent = $this->get(route('engineering-construction'))->assertOk()->getContent();
    expect($noContactContent)->not->toContain('Call Selotemna')
        ->not->toContain('Chat on WhatsApp');
});

it('renders the approved asymmetric what we do pathways and no retired architecture', function () {
    $content = $this->get(route('home'))->getContent();
    preg_match('/<section[^>]*data-home-divisions[^>]*>.*?<\/section>/s', $content, $section);
    $copy = html_entity_decode(strip_tags($section[0]));

    expect($section)->not->toBeEmpty()
        ->and(substr_count($section[0], 'data-primary-division'))->toBe(2)
        ->and(substr_count($section[0], 'data-division-number'))->toBe(2)
        ->and($section[0])->toContain('division-pathway-numbered')
        ->toContain('data-division-number>01</span>')
        ->toContain('data-division-number>02</span>')
        ->toContain('href="'.route('real-estate-development').'"')
        ->toContain('href="'.route('engineering-construction').'"')
        ->not->toContain('rounded-xl border border-brand-100 bg-brand-50')
        ->and($copy)->toContain('What we do')
        ->toContain('Explore our developments. Discuss your next project.')
        ->toContain('Selotemna operates through Real Estate Development and Engineering & Construction, giving visitors a clear way to explore our development work, review current opportunities or begin a project conversation.')
        ->toContain('Explore Selotemna’s real-estate developments and property opportunities. Omu Creek, our latest project, is the current featured opportunity for buyers and investors to review before making an enquiry or requesting an inspection.')
        ->toContain('Explore Real Estate Development')
        ->toContain('Bring an engineering or construction requirement to Selotemna. Share the site, scope and current stage so the team can understand the project and identify the appropriate next step.')
        ->toContain('Explore Engineering & Construction')
        ->not->toContain('Property Management')
        ->not->toContain('Land Sales')
        ->not->toContain('House Sales')
        ->not->toContain('Shortlet');
});

it('renders an immersive accessible homepage hero with protected actions and motion hooks', function () {
    config()->set('selotemna.whatsapp', '+2348000000000');

    $content = $this->get(route('home'))->assertOk()->getContent();
    preg_match('/<section[^>]*data-home-hero[^>]*>.*?<\/section>/s', $content, $hero);

    expect($hero)->not->toBeEmpty()
        ->and(substr_count($hero[0], '<h1'))->toBe(1)
        ->and(strip_tags($hero[0]))->toContain('Developing places. Building with purpose.')
        ->and($hero[0])->toContain('data-hero-sequence')
        ->toContain('data-hero-actions')
        ->toContain('data-hero-item')
        ->toContain('data-hero-media')
        ->toContain('data-hero-overlay')
        ->toContain('fetchpriority="high"')
        ->toContain('w-full overflow-hidden bg-ink-950')
        ->toContain('class="site-container relative z-20')
        ->toContain('href="'.route('inspections.create', ['interest' => 'Omu Creek']).'"')
        ->toContain('href="'.route('omu-creek').'"')
        ->toContain('href="https://wa.me/2348000000000"')
        ->toContain('data-event="book_inspection_click"')
        ->toContain('data-event="whatsapp_click"')
        ->not->toContain('site-container-wide')
        ->not->toContain('rounded-[1.25rem]')
        ->not->toContain('autoplay');
});

it('omits the homepage hero contact shortcut when WhatsApp is unavailable', function () {
    config()->set('selotemna.whatsapp', null);

    $content = $this->get(route('home'))->assertOk()->getContent();
    preg_match('/<section[^>]*data-home-hero[^>]*>.*?<\/section>/s', $content, $hero);

    expect($hero[0])->not->toContain('data-hero-whatsapp')
        ->not->toContain('data-event="whatsapp_click"');
});

it('keeps the homepage hero readable when the aerial image is unavailable', function () {
    config()->set('selotemna.media.development_aerial', 'assets/images/missing-development-aerial.jpg');

    $content = $this->get(route('home'))->assertOk()->getContent();
    preg_match('/<section[^>]*data-home-hero[^>]*>.*?<\/section>/s', $content, $hero);

    expect($hero[0])->toContain('Developing places. Building with purpose.')
        ->toContain('data-hero-media')
        ->toContain('aria-hidden="true"')
        ->toContain('bg-brand-950')
        ->toContain('data-hero-overlay')
        ->toContain('data-event="book_inspection_click"')
        ->not->toContain('fetchpriority="high"');
});

it('renders the approved Omu Creek homepage project hierarchy and actions', function () {
    config()->set('selotemna.whatsapp', '+2348000000000');

    $content = $this->get(route('home'))->assertOk()->getContent();
    preg_match('/<section[^>]*data-home-omu-creek[^>]*>.*?<\/section>/s', $content, $section);
    $text = preg_replace('/\s+/', ' ', html_entity_decode(preg_replace('/<[^>]+>/', ' ', $section[0] ?? '')));

    expect($section)->not->toBeEmpty()
        ->and($section[0])->toContain('aspect-video')
        ->toContain('data-event="omu_creek_short_video_play"')
        ->toContain('data-reveal-group')
        ->toContain('data-property-interest="Omu Creek"')
        ->toContain('data-event="whatsapp_click"')
        ->toContain('href="'.route('inspections.create', ['interest' => 'Omu Creek']).'"')
        ->toContain('href="'.route('omu-creek').'"')
        ->and($text)->toContain('LATEST PROJECT · REAL ESTATE DEVELOPMENT')
        ->toContain('Omu Creek')
        ->toContain(config('selotemna.featured_property.overview'))
        ->toContain('Status Upcoming Project')
        ->toContain('Opportunity Land allocation')
        ->toContain('Title Lagos State Government Allocation')
        ->toContain('Rate ₦50,000 per sqm')
        ->toContain('Request an Omu Creek Inspection')
        ->toContain('View Full Project Details')
        ->not->toContain('data-price-disclosure')
        ->not->toContain('300 sqm ₦15,000,000');

    $orderedHooks = [
        'data-omu-creek-media',
        'data-omu-creek-summary',
        'data-omu-creek-facts',
        'data-omu-creek-actions',
    ];
    $positions = array_map(fn (string $hook): int|false => strpos($section[0], $hook), $orderedHooks);

    expect($positions)->not->toContain(false)
        ->and($positions)->toBe(collect($positions)->sort()->values()->all())
        ->and(strpos($section[0], 'Request an Omu Creek Inspection'))->toBeLessThan(strpos($section[0], 'View Full Project Details'));
});

it('renders the compact asymmetric homepage about transition', function () {
    $content = $this->get(route('home'))->assertOk()->getContent();
    preg_match('/<section[^>]*data-home-about[^>]*>.*?<\/section>/s', $content, $section);
    $text = preg_replace('/\s+/', ' ', html_entity_decode(preg_replace('/<[^>]+>/', ' ', $section[0] ?? '')));

    expect($section)->not->toBeEmpty()
        ->and($section[0])->toContain('bg-brand-50')
        ->toContain('border-y border-brand-100')
        ->toContain('lg:grid-cols-[minmax(0,0.42fr)_minmax(0,1fr)]')
        ->toContain('data-reveal')
        ->toContain('class="text-link mt-6"')
        ->toContain('href="'.route('about').'"')
        ->not->toContain('<img')
        ->not->toContain('<svg')
        ->and($text)->toContain('About Selotemna')
        ->toContain('Property opportunities and project requirements, brought under one company.')
        ->toContain('Selotemna operates through Real Estate Development and Engineering & Construction. We help clients review published development opportunities and begin focused conversations about engineering or construction requirements.')
        ->toContain('Learn About Selotemna')
        ->not->toContain('A focused route from requirement to next step.');
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

it('renders every configured faq value in an accessible grouped layout', function () {
    $content = $this->get(route('faq'))->getContent();
    preg_match_all('/data-faq-trigger/', $content, $triggers);
    preg_match_all('/\bid="([^"]+)"/', $content, $ids);
    preg_match_all('/<button[^>]*data-faq-trigger[^>]*>/s', $content, $buttons);

    expect($triggers[0])->toHaveCount(15)
        ->and($buttons[0])->toHaveCount(15)
        ->and($ids[1])->toHaveCount(count(array_unique($ids[1])))
        ->and(collect($buttons[0])->every(fn (string $button): bool => str_contains($button, 'type="button"') && str_contains($button, 'aria-expanded="false"') && str_contains($button, 'aria-controls=')))->toBeTrue()
        ->and(substr_count($content, 'data-faq-single-open="true"'))->toBe(count(config('selotemna.faq_groups')))
        ->and(substr_count($content, 'data-faq-category-link'))->toBe(count(config('selotemna.faq_groups')))
        ->and($content)->toContain('data-page-hero-variant="overlay"')
        ->toContain('data-page-hero-alignment="center"')
        ->toContain('Frequently Asked Questions')
        ->toContain('Clear answers about Omu Creek’s location, title, prices, payments, allocation and policies.')
        ->not->toContain('data-page-hero-breadcrumb')
        ->toContain('Ready to continue with Omu Creek?')
        ->toContain('Request an Inspection')
        ->toContain('View Omu Creek')
        ->toContain('Submitting an inspection request does not confirm an appointment');

    foreach (config('selotemna.faq_groups') as $key => $group) {
        expect($content)->toContain($group['label'])
            ->toContain('href="#faq-group-'.$key.'"')
            ->toContain('id="faq-group-'.$key.'"');

        foreach ($group['items'] as $item) {
            expect($content)->toContain($item['question'])->toContain($item['answer']);

            foreach ($item['points'] ?? [] as $point) {
                expect($content)->toContain($point);
            }

            if (isset($item['note'])) {
                expect($content)->toContain($item['note']);
            }
        }
    }
});

it('keeps faq accordion mouse and native keyboard activation scoped to each group', function () {
    $content = $this->get(route('faq'))->getContent();
    $script = file_get_contents(resource_path('js/app.js'));

    expect($content)->toContain('data-faq-collapse-default="true"')
        ->toContain('data-faq-single-open="true"')
        ->toContain('hidden')
        ->and($script)->toContain("trigger.addEventListener('click'")
        ->toContain("const singleOpen = group.dataset.faqSingleOpen !== 'false'")
        ->toContain('if (singleOpen && willOpen)')
        ->toContain("trigger.setAttribute('aria-expanded', 'true')")
        ->toContain("trigger.setAttribute('aria-expanded', 'false')");
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
        ->assertSee('assets/images/omu-creek.png', false)
        ->assertSee('assets/images/omu-creek-2.png', false)
        ->assertSee('Prices exclude applicable taxes. Availability and property information are subject to confirmation.');
});

it('autoplays only the muted short Omu Creek preview and keeps the detailed video user initiated', function () {
    $featuredProperty = config('selotemna.featured_property');
    $homepage = $this->get(route('home'))->assertOk()->getContent();
    $detailPage = $this->get(route('omu-creek'))->assertOk()->getContent();

    expect($featuredProperty['short_video_url'])->not->toBeNull()
        ->and($featuredProperty['video_url'])->not->toBeNull()
        ->and($homepage)->toContain($featuredProperty['short_video_url'])
        ->toContain('data-event="omu_creek_short_video_play"')
        ->and($detailPage)->toContain($featuredProperty['video_url'])
        ->toContain('data-event="omu_creek_video_play"');

    preg_match('/<video[^>]*data-event="omu_creek_short_video_play"[^>]*>/s', $homepage, $homepageVideo);
    preg_match('/<video[^>]*data-event="omu_creek_video_play"[^>]*>/s', $detailPage, $detailVideo);

    expect($homepageVideo[0])->toContain('controls')
        ->toContain('autoplay')
        ->toContain('muted')
        ->toContain('playsinline')
        ->toContain('data-autoplay-preview')
        ->not->toContain('loop')
        ->and($detailVideo[0])->toContain('controls')
        ->toContain('playsinline')
        ->toContain('preload="metadata"')
        ->not->toContain('autoplay')
        ->not->toContain('loop');
});

it('publishes accurate Omu Creek real-estate listing data without claiming availability', function () {
    $content = $this->get(route('omu-creek'))->assertOk()->getContent();
    preg_match('/<script type="application\/ld\+json">(.*?)<\/script>/s', $content, $schemaMatch);
    $schema = json_decode($schemaMatch[1] ?? '', true, flags: JSON_THROW_ON_ERROR);

    expect($schema['@type'])->toBe('RealEstateListing')
        ->and($schema['name'])->toBe('Omu Creek')
        ->and($schema['offers'])->toHaveCount(3)
        ->and(collect($schema['offers'])->pluck('price')->all())->toBe([50000000, 25000000, 15000000])
        ->and(collect($schema['offers'])->pluck('priceCurrency')->unique()->all())->toBe(['NGN'])
        ->and($schema['primaryImageOfPage']['url'])->toBe(asset('assets/images/omu-creek.png'))
        ->and($schema)->not->toHaveKey('availability')
        ->and($content)->not->toContain('schema.org/InStock');
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

it('keeps project group filtering backward compatible while allowing permanent page stages', function () {
    $content = app(SelotemnaContent::class);
    $publishedGroups = $content->projectGroups();
    $allStages = $content->projectGroups(includeEmptyGroups: true);

    expect(array_keys($publishedGroups))->toBe(['upcoming'])
        ->and(array_keys($allStages))->toBe(['upcoming', 'ongoing', 'completed'])
        ->and($allStages['upcoming']['label'])->toBe('Upcoming')
        ->and($allStages['ongoing']['label'])->toBe('Ongoing')
        ->and($allStages['completed']['label'])->toBe('Completed')
        ->and($allStages['upcoming']['items'])->toHaveCount(1)
        ->and($allStages['ongoing']['items'])->toBe([])
        ->and($allStages['completed']['items'])->toBe([]);
});

it('renders three ordered and accessible project stage tabs', function () {
    $content = $this->get(route('projects.index'))->assertOk()->getContent();
    $script = file_get_contents(resource_path('js/app.js'));
    preg_match_all('/<button[^>]*data-project-tab[^>]*>.*?<\/button>/s', $content, $tabs);
    preg_match_all('/<div[^>]*role="tabpanel"[^>]*>/', $content, $panels);

    expect($tabs[0])->toHaveCount(3)
        ->and($panels[0])->toHaveCount(3)
        ->and($content)->toContain('role="tablist"')
        ->toContain('aria-label="Project stages"')
        ->toContain('id="all-projects-tab-upcoming"')
        ->toContain('aria-selected="true"')
        ->toContain('aria-controls="all-projects-panel-upcoming"')
        ->toContain('tabindex="0"')
        ->toContain('id="all-projects-tab-ongoing"')
        ->toContain('aria-selected="false"')
        ->toContain('aria-controls="all-projects-panel-ongoing"')
        ->toContain('tabindex="-1"')
        ->toContain('id="all-projects-panel-completed"')
        ->toContain('aria-labelledby="all-projects-tab-completed"')
        ->not->toContain('All Projects')
        ->not->toContain('Completed (0)')
        ->and($tabs[0][0])->toContain('aria-selected="true"')->toContain('tabindex="0"')
        ->and($tabs[0][1])->toContain('aria-selected="false"')->toContain('tabindex="-1"')
        ->and($tabs[0][2])->toContain('aria-selected="false"')->toContain('tabindex="-1"')
        ->and(collect($panels[0])->every(fn (string $panel): bool => ! str_contains($panel, ' hidden')))->toBeTrue()
        ->and(strpos($content, '>Upcoming</button>'))->toBeLessThan(strpos($content, '>Ongoing</button>'))
        ->and(strpos($content, '>Ongoing</button>'))->toBeLessThan(strpos($content, '>Completed</button>'))
        ->and($script)->toContain("if (event.key === 'ArrowLeft')")
        ->toContain("if (event.key === 'ArrowRight')")
        ->toContain("if (event.key === 'Home')")
        ->toContain("if (event.key === 'End')")
        ->toContain("panel.setAttribute('aria-hidden', isActive ? 'false' : 'true')")
        ->toContain("if ('inert' in panel) panel.inert = !isActive");
});

it('publishes the featured Omu Creek record and honest project-stage empty states', function () {
    $content = $this->get(route('projects.index'))->getContent();
    preg_match_all('/<article[^>]*data-project-card[^>]*>.*?<\/article>/s', $content, $cards);

    $projects = collect(config('selotemna.projects'))->flatMap(fn (array $group): array => $group['items']);

    expect($cards[0])->toHaveCount(1)
        ->and($cards[0][0])->toContain('Omu Creek')
        ->toContain('Upcoming Project')
        ->toContain('Real Estate Development')
        ->toContain(config('selotemna.projects.upcoming.items.0.summary'))
        ->toContain('data-project-featured')
        ->toContain('View Full Project Details')
        ->toContain('Request an Inspection')
        ->toContain('href="'.route('omu-creek').'"')
        ->toContain('href="'.route('inspections.create', ['interest' => 'Omu Creek']).'"')
        ->and($projects)->toHaveCount(1)
        ->and($projects->first()['name'])->toBe('Omu Creek')
        ->and(config('selotemna.temporary_projects'))->toBeNull()
        ->and($content)->toContain('Project portfolio')
        ->toContain('Explore projects by stage.')
        ->toContain('Omu Creek is Selotemna’s latest project and the current project with detailed public information available. Previous project profiles will appear as their information is approved for publication.')
        ->and($content)->toContain('Selotemna has undertaken previous projects.')
        ->toContain('Upcoming Project describes the project stage; property availability remains subject to confirmation.')
        ->toContain('No ongoing project profiles are currently published.')
        ->toContain('New project information will appear here when it has been approved for public release.')
        ->toContain('Completed project profiles are not yet published.')
        ->toContain('Their names, locations, images and details will appear here as the information is approved for publication.')
        ->not->toContain('sole published project')
        ->not->toContain('only published project')
        ->not->toContain('Selotemna’s only project')
        ->and($content)->not->toContain('Layout Sample')
        ->not->toContain('Development-only')
        ->and(Route::has('projects.show'))->toBeTrue()
        ->and($content)->toContain('<title>Projects | Selotemna</title>')
        ->toContain('<meta name="description" content="Explore Selotemna projects by stage, including Omu Creek, the company’s latest project and current opportunity with detailed public information.">')
        ->toContain('Interested in Omu Creek?')
        ->toContain('Review the complete project information or request an inspection with your preferred date and contact details.')
        ->toContain('Submitting an inspection request does not automatically confirm an appointment.');
});

it('keeps the same verified Omu Creek project in production', function () {
    $this->app->detectEnvironment(fn (): string => 'production');
    $content = $this->get(route('projects.index'))->getContent();

    expect(substr_count($content, 'data-project-card'))->toBe(1)
        ->and($content)->toContain('Omu Creek')
        ->toContain('Upcoming Project')
        ->not->toContain('Layout Sample')
        ->toContain('>Upcoming</button>')
        ->toContain('>Ongoing</button>')
        ->toContain('>Completed</button>')
        ->toContain('No ongoing project profiles are currently published.')
        ->toContain('Completed project profiles are not yet published.');
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
    config()->set('selotemna.phones', []);
    config()->set('selotemna.emails', []);

    foreach (array_keys(publicRoutes()) as $routeName) {
        $content = $this->get(route($routeName))->getContent();
        expect($content)->not->toContain('href="#"')
            ->not->toContain('tel:')
            ->not->toContain('wa.me')
            ->not->toContain('mailto:');
    }
});

it('renders configured contact channels with safe urls', function () {
    config()->set('selotemna.phone', null);
    config()->set('selotemna.phones', ['+234 800 000 0000', '+234 811 111 1111']);
    config()->set('selotemna.whatsapp', null);
    config()->set('selotemna.email', null);
    config()->set('selotemna.emails', ['contact@selotemna.test', 'projects@selotemna.test']);
    config()->set('selotemna.address', 'Verified office address');
    config()->set('selotemna.business_hours', 'Verified business hours');

    $this->get(route('contact'))
        ->assertSee('tel:+2348000000000', false)
        ->assertSee('tel:+2348111111111', false)
        ->assertSee('mailto:contact@selotemna.test', false)
        ->assertSee('mailto:projects@selotemna.test', false)
        ->assertSee('Verified office address')
        ->assertSee('Verified business hours')
        ->assertDontSee('wa.me', false);
});

it('publishes the verified Selotemna contacts and preserves singular primary aliases', function () {
    $contact = app(SelotemnaContent::class)->contactDetails();
    $content = $this->get(route('contact'))->assertOk()->getContent();

    expect($contact['phones'])->toHaveCount(2)
        ->and($contact['emails'])->toHaveCount(2)
        ->and($contact['phone'])->toBe('0905 151 2521')
        ->and($contact['phone_url'])->toBe('tel:+2349051512521')
        ->and($contact['email'])->toBe('info@selotemna.com')
        ->and($contact['email_url'])->toBe('mailto:info@selotemna.com')
        ->and($contact['whatsapp_url'])->toBeNull()
        ->and($content)->toContain('0905 151 2521')
        ->toContain('0802 406 6013')
        ->toContain('tel:+2349051512521')
        ->toContain('tel:+2348024066013')
        ->toContain('info@selotemna.com')
        ->toContain('selotemna@gmail.com')
        ->toContain('mailto:info@selotemna.com')
        ->toContain('mailto:selotemna@gmail.com')
        ->not->toContain('wa.me');
});

it('accepts legacy singular contact configuration', function () {
    config()->set('selotemna.phones', []);
    config()->set('selotemna.emails', []);
    config()->set('selotemna.phone', '+234 800 000 0000');
    config()->set('selotemna.email', 'legacy@selotemna.test');

    $contact = app(SelotemnaContent::class)->contactDetails();

    expect($contact['phones'])->toHaveCount(1)
        ->and($contact['emails'])->toHaveCount(1)
        ->and($contact['phone'])->toBe('0800 000 0000')
        ->and($contact['phone_url'])->toBe('tel:+2348000000000')
        ->and($contact['email'])->toBe('legacy@selotemna.test')
        ->and($contact['email_url'])->toBe('mailto:legacy@selotemna.test');
});

it('renders the redesigned Contact page and progressively enhanced enquiry form', function () {
    $content = $this->get(route('contact'))->assertOk()->getContent();
    $script = file_get_contents(resource_path('js/app.js'));
    preg_match('/<section[^>]*data-page-hero[^>]*>.*?<\/section>/s', $content, $hero);

    expect($hero)->not->toBeEmpty()
        ->and($hero[0])->toContain('data-page-hero-variant="overlay"')
        ->toContain('data-page-hero-alignment="center"')
        ->toContain('data-page-hero-size="compact"')
        ->toContain('Contact Selotemna')
        ->toContain('Speak with our team about Omu Creek, real estate development, or an engineering and construction requirement.')
        ->toContain('assets/images/contact.jpg')
        ->not->toContain('data-page-hero-breadcrumb')
        ->and($content)->not->toContain('Editorial image.')
        ->not->toContain('Photo by Ninthgrid on Pexels')
        ->not->toContain('pexels.com/photo/business-meeting-in-lagos')
        ->toContain('Speak with our team')
        ->toContain('Tell us what you would like to discuss.')
        ->toContain('Share enough information for our team to understand your enquiry and determine the appropriate next step.')
        ->toContain('action="'.route('contact.store').'"')
        ->toContain('data-contact-enquiry-form')
        ->toContain('data-async-form="contact"')
        ->toContain('data-step-form')
        ->toContain('name="interest_type"')
        ->toContain('Land opportunities')
        ->toContain('A building project')
        ->toContain('data-enquiry-type')
        ->toContain('data-engineering-fields')
        ->toContain('Project type')
        ->toContain('Proposed location')
        ->toContain('Current project stage')
        ->toContain('Scope or requirement summary')
        ->toContain('Omu Creek information')
        ->toContain('Real Estate Development')
        ->toContain('Engineering &amp; Construction')
        ->toContain('General enquiry')
        ->toContain('data-pending-label="Sending enquiry…"')
        ->toContain('Planning an Omu Creek visit?')
        ->toContain('data-async-success="contact"')
        ->not->toContain('Before you send an enquiry')
        ->not->toContain('Start with the right conversation.')
        ->and($script)->toContain("enquiryType?.addEventListener('change', updateEngineeringFields)")
        ->toContain("enquiryType?.value === 'Engineering & Construction'")
        ->toContain('engineeringFields.hidden = !isEngineeringEnquiry')
        ->toContain('input.disabled = !isEngineeringEnquiry')
        ->toContain('fetch(form.action')
        ->toContain('response.status === 422');
});

it('renders an accessible inspection modal across the site while retaining the direct fallback page', function () {
    $homepage = $this->get(route('home'))->assertOk()->getContent();
    $inspectionPage = $this->get(route('inspections.create'))->assertOk()->getContent();
    $script = file_get_contents(resource_path('js/app.js'));

    expect($homepage)->toContain('data-inspection-dialog')
        ->toContain('aria-labelledby="inspection-dialog-title"')
        ->toContain('data-inspection-dialog-close')
        ->toContain('data-async-form="inspection"')
        ->toContain('data-form-step="1"')
        ->toContain('data-form-step="2"')
        ->toContain('data-form-step="3"')
        ->and($inspectionPage)->not->toContain('data-inspection-dialog')
        ->toContain('data-inspection-form')
        ->and($script)->toContain("typeof inspectionDialog.showModal === 'function'")
        ->toContain('destination.pathname !== inspectionPath')
        ->toContain('focusTarget?.focus()');
});

it('validates contact enquiries and preserves the submission token and old input', function () {
    Mail::fake();
    $data = validContactEnquiryData();
    $data['phone'] = '';
    $data['enquiry_type'] = '';
    $data['message'] = '';
    unset($data['consent']);

    $this->from(route('contact'))
        ->post(route('contact.store'), $data)
        ->assertRedirect(route('contact'))
        ->assertSessionHasErrors(['phone', 'enquiry_type', 'message', 'consent'])
        ->assertSessionHasInput('submission_token', $data['submission_token'])
        ->assertSessionHasInput('full_name', $data['full_name']);

    Mail::assertNothingSent();
    expect(ContactEnquiry::query()->count())->toBe(0);
});

it('returns field errors and an in-place receipt for enhanced contact submissions', function () {
    Mail::fake();
    config()->set('selotemna.email', 'primary@selotemna.test');
    config()->set('mail.default', 'smtp');

    $this->postJson(route('contact.store'), array_replace(validContactEnquiryData(), ['email' => 'not-an-email', 'contact_method' => 'Email']))
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['email']);

    $this->postJson(route('contact.store'), validContactEnquiryData())
        ->assertCreated()
        ->assertJsonPath('message', 'Thanks! We got your message. We will reply within 24 hours.')
        ->assertJsonPath('receipt.interest_type', 'Both')
        ->assertJsonStructure(['receipt' => ['reference', 'enquiry_type', 'contact_method']]);
});

it('requires the selected contact detail and rejects the contact honeypot', function () {
    Mail::fake();

    foreach ([['Email', 'email'], ['WhatsApp', 'whatsapp']] as [$method, $field]) {
        $data = validContactEnquiryData();
        $data['submission_token'] = (string) Str::uuid();
        $data['contact_method'] = $method;
        $data[$field] = '';

        $this->post(route('contact.store'), $data)->assertSessionHasErrors([$field]);
    }

    $honeypot = validContactEnquiryData();
    $honeypot['website'] = 'bot-value';
    $this->post(route('contact.store'), $honeypot)->assertSessionHasErrors(['website']);

    Mail::assertNothingSent();
    expect(ContactEnquiry::query()->count())->toBe(0);
});

it('saves and emails an engineering enquiry to the primary address', function () {
    Mail::fake();
    config()->set('selotemna.email', 'primary@selotemna.test');
    config()->set('mail.default', 'smtp');
    $data = validContactEnquiryData();
    $data['enquiry_type'] = 'Engineering & Construction';
    $data['project_type'] = 'Commercial building';
    $data['proposed_location'] = 'Lagos';
    $data['project_stage'] = 'Initial planning';
    $data['scope_summary'] = 'Review the available project scope.';

    $this->post(route('contact.store'), $data)
        ->assertRedirect(route('contact'))
        ->assertSessionHas('contact_enquiry_receipt');

    Mail::assertSent(ContactEnquiryMail::class, fn (ContactEnquiryMail $mail): bool => $mail->hasTo('primary@selotemna.test') && $mail->enquiry->enquiry_type === 'Engineering & Construction');

    $enquiry = ContactEnquiry::query()->sole();
    expect($enquiry->status)->toBe(ContactEnquiry::STATUS_NEW)
        ->and($enquiry->interest_type)->toBe('Both')
        ->and($enquiry->project_type)->toBe('Commercial building')
        ->and($enquiry->proposed_location)->toBe('Lagos')
        ->and($enquiry->project_stage)->toBe('Initial planning')
        ->and($enquiry->scope_summary)->toBe('Review the available project scope.')
        ->and($enquiry->staff_notified_at)->not->toBeNull()
        ->and(InspectionRequest::query()->count())->toBe(0);
});

it('does not duplicate a repeated contact enquiry token', function () {
    Mail::fake();
    config()->set('selotemna.email', 'primary@selotemna.test');
    config()->set('mail.default', 'smtp');
    $data = validContactEnquiryData();

    $this->post(route('contact.store'), $data)->assertRedirect(route('contact'));
    $this->post(route('contact.store'), $data)->assertRedirect(route('contact'));

    expect(ContactEnquiry::query()->count())->toBe(1);
    Mail::assertSentCount(1);
});

it('keeps a saved contact enquiry when staff email delivery fails', function () {
    config()->set('selotemna.email', 'primary@selotemna.test');
    config()->set('mail.default', 'smtp');
    Mail::shouldReceive('to')->once()->andThrow(new RuntimeException('Mail transport unavailable'));

    $this->post(route('contact.store'), validContactEnquiryData())
        ->assertRedirect(route('contact'))
        ->assertSessionHas('contact_enquiry_receipt');

    $enquiry = ContactEnquiry::query()->sole();
    expect($enquiry->notification_failed_at)->not->toBeNull()
        ->and($enquiry->staff_notified_at)->toBeNull();
});

it('rate limits contact enquiry submissions', function () {
    Mail::fake();
    config()->set('selotemna.email', 'primary@selotemna.test');
    config()->set('mail.default', 'smtp');

    foreach (range(1, 5) as $attempt) {
        $this->post(route('contact.store'), validContactEnquiryData())->assertRedirect(route('contact'));
    }

    $this->post(route('contact.store'), validContactEnquiryData())->assertStatus(429);
    expect(ContactEnquiry::query()->count())->toBe(5);
    Mail::assertSentCount(5);
});

it('renders the saved contact enquiry receipt instead of the form', function () {
    $content = $this->withSession([
        'contact_enquiry_receipt' => [
            'reference' => 'ENQ-TEST-1234',
            'interest_type' => 'Land',
            'enquiry_type' => 'General enquiry',
            'contact_method' => 'Telephone',
        ],
    ])->get(route('contact'))->assertOk()->getContent();

    expect($content)->toContain('data-contact-enquiry-receipt')
        ->toContain('Enquiry received')
        ->toContain('Thanks! We got your message.')
        ->toContain('We will reply within 24 hours')
        ->toContain('Land')
        ->toContain('Keep this reference for follow-up.')
        ->toContain('ENQ-TEST-1234')
        ->toContain('Return to Home')
        ->toContain('Send Another Enquiry')
        ->not->toContain('data-contact-enquiry-form');
});

it('keeps the database-backed request form available when email delivery is unavailable', function () {
    config()->set('selotemna.email', 'inspections@selotemna.test');
    config()->set('mail.default', 'log');
    $response = $this->get(route('inspections.create'));

    $response->assertOk()
        ->assertSee('Submitting the form does not confirm an inspection appointment.')
        ->assertSee('data-inspection-form', false)
        ->assertDontSee('data-inspection-contact-fallback', false)
        ->assertDontSee('Inspection confirmed');
});

it('renders the responsive request-first inspection experience', function () {
    $content = $this->get(route('inspections.create'))->assertOk()->getContent();
    $script = file_get_contents(resource_path('js/app.js'));
    preg_match('/<section[^>]*data-page-hero[^>]*>.*?<\/section>/s', $content, $hero);

    expect($hero)->not->toBeEmpty()
        ->and($hero[0])->toContain('data-page-hero-variant="overlay"')
        ->toContain('data-page-hero-alignment="center"')
        ->toContain('data-page-hero-size="compact"')
        ->toContain('Request an Inspection')
        ->toContain('Choose a preferred date for Omu Creek and our team will follow up to confirm availability.')
        ->toContain('assets/images/selotemna-inspection-consultation.jpg')
        ->not->toContain('data-page-hero-breadcrumb')
        ->and($content)->toContain('Editorial image.')
        ->toContain('Photo by Gustavo Fring on Pexels')
        ->toContain('order-1 min-w-0 max-w-3xl lg:order-2')
        ->toContain('order-2 min-w-0 lg:order-1 lg:sticky lg:top-28')
        ->toContain('Inspection for Omu Creek')
        ->toContain('Review project details')
        ->toContain('What happens next')
        ->toContain('Share your details')
        ->toContain('Receive your reference')
        ->toContain('Wait for confirmation')
        ->toContain('Important notes')
        ->toContain('Contact details')
        ->toContain('Inspection preference')
        ->toContain('Additional information')
        ->toContain('Consent and submission')
        ->toContain('data-contact-method')
        ->toContain('data-async-form="inspection"')
        ->toContain('data-step-form')
        ->toContain('I’m flexible')
        ->toContain('data-async-success="inspection"')
        ->toContain('data-conditional-contact="WhatsApp"')
        ->toContain('data-conditional-contact="Email"')
        ->toContain('data-pending-label="Saving request…"')
        ->not->toContain('optional email notification')
        ->not->toContain('mail-delivery issue')
        ->and(strpos($content, 'data-inspection-form'))->toBeLessThan(strpos($content, 'What happens next'))
        ->and($script)->toContain("contactMethod?.addEventListener('change', updateConditionalContactFields)")
        ->toContain("form.setAttribute('aria-busy', isSubmitting ? 'true' : 'false')")
        ->toContain("submitButton.setAttribute('aria-busy', isSubmitting ? 'true' : 'false')");
});

it('renders the saved inspection receipt instead of the form', function () {
    $content = $this->withSession([
        'inspection_receipt' => [
            'reference' => 'INS-TEST-1234',
            'project' => 'Omu Creek',
            'preferred_date' => '26 August 2026',
            'preferred_time' => 'Morning',
        ],
    ])->get(route('inspections.create'))->assertOk()->getContent();

    expect($content)->toContain('data-inspection-receipt')
        ->toContain('Request received')
        ->toContain('Thanks! We got your inspection request.')
        ->toContain('We will reply within 24 hours')
        ->toContain('This receipt does not confirm an inspection appointment.')
        ->toContain('Keep this reference for follow-up.')
        ->toContain('INS-TEST-1234')
        ->toContain('Review Omu Creek')
        ->toContain('Submit Another Request')
        ->not->toContain('data-inspection-form');
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

it('rejects a past inspection date while preserving the submission token and old input', function () {
    Mail::fake();
    $data = validInspectionData();
    $data['preferred_date'] = now()->subDay()->toDateString();

    $this->from(route('inspections.create'))
        ->post(route('inspections.store'), $data)
        ->assertRedirect(route('inspections.create'))
        ->assertSessionHasErrors(['preferred_date'])
        ->assertSessionHasInput('submission_token', $data['submission_token'])
        ->assertSessionHasInput('full_name', $data['full_name']);

    Mail::assertNothingSent();
    expect(InspectionRequest::query()->count())->toBe(0);
});

it('requires the additional detail for WhatsApp and Email contact methods', function () {
    Mail::fake();

    foreach ([['WhatsApp', 'whatsapp'], ['Email', 'email']] as [$method, $field]) {
        $data = validInspectionData();
        $data['submission_token'] = (string) Str::uuid();
        $data['contact_method'] = $method;
        $data[$field] = '';

        $this->post(route('inspections.store'), $data)
            ->assertSessionHasErrors([$field]);
    }

    Mail::assertNothingSent();
    expect(InspectionRequest::query()->count())->toBe(0);
});

it('emails a valid inspection request and confirms receipt without confirming an appointment', function () {
    Mail::fake();
    config()->set('selotemna.email', 'inspections@selotemna.test');
    config()->set('mail.default', 'smtp');

    $this->post(route('inspections.store'), validInspectionData())
        ->assertRedirect(route('inspections.create'))
        ->assertSessionHas('status', fn (string $status): bool => str_contains($status, 'Thanks! We got your inspection request.') && str_contains($status, 'does not confirm an appointment'))
        ->assertSessionHas('inspection_receipt');

    Mail::assertSent(InspectionRequestMail::class, fn (InspectionRequestMail $mail): bool => $mail->hasTo('inspections@selotemna.test') && $mail->inspection->project_name === 'Omu Creek');
    expect(InspectionRequest::query()->first())
        ->project_slug->toBe('omu-creek')
        ->status->toBe(InspectionRequest::STATUS_NEW)
        ->staff_notified_at->not->toBeNull();
});

it('returns field errors and an in-place receipt for enhanced inspection submissions', function () {
    Mail::fake();
    config()->set('selotemna.email', 'inspections@selotemna.test');
    config()->set('mail.default', 'smtp');

    $this->postJson(route('inspections.store'), array_replace(validInspectionData(), ['email' => 'not-an-email', 'contact_method' => 'Email']))
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['email']);

    $this->postJson(route('inspections.store'), validInspectionData())
        ->assertCreated()
        ->assertJsonPath('message', 'Thanks! We got your inspection request. We will reply within 24 hours to confirm the next step.')
        ->assertJsonPath('receipt.project', 'Omu Creek')
        ->assertJsonStructure(['receipt' => ['reference', 'preferred_date', 'preferred_time']]);
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
    config()->set('selotemna.emails', []);
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

it('renders attributed editorial media and supplied contact media appropriately', function () {
    $this->get(route('inspections.create'))
        ->assertOk()
        ->assertSee('assets/images/selotemna-inspection-consultation.jpg', false)
        ->assertSee('pexels.com/photo/a-man-talking-to-his-clients', false)
        ->assertSee('Photo by Gustavo Fring on Pexels')
        ->assertSee('Editorial image.');

    $this->get(route('contact'))
        ->assertOk()
        ->assertSee('assets/images/contact.jpg', false)
        ->assertDontSee('pexels.com/photo/business-meeting-in-lagos', false)
        ->assertDontSee('Photo by Ninthgrid on Pexels')
        ->assertDontSee('Editorial image.');
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
        ->and($inspection)->toContain('data-inspection-form data-submit-once data-async-form="inspection" data-step-form data-reveal')
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
