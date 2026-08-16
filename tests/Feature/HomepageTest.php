<?php

use Illuminate\Support\Facades\Route;

it('renders the approved corporate homepage journey', function () {
    $this->get(route('home'))
        ->assertOk()
        ->assertSee('Property solutions built around your next move.')
        ->assertSee('From land and homes to property development, construction and management, Selotemna helps individuals, families and businesses take the next step with confidence.')
        ->assertSee('One company. More ways to move forward with property.')
        ->assertSee('See the property before making your next decision.')
        ->assertSee('What would you like to know?');
});

it('renders only the five approved service pathways', function () {
    $content = $this->get(route('home'))->getContent();

    foreach ([
        'Property Development',
        'Land Sales',
        'House Sales',
        'Property Management',
        'Construction',
    ] as $service) {
        expect($content)->toContain($service);
    }

    expect(substr_count($content, 'data-service-path'))->toBe(5);
});

it('renders the verified Omu Creek property facts from structured data', function () {
    $this->get(route('home'))
        ->assertOk()
        ->assertSee('Featured land opportunity')
        ->assertSee('Omu Creek')
        ->assertSee('Land allocation')
        ->assertSee('Certificate of Occupancy (C of O)')
        ->assertSee('₦50,000 per sqm')
        ->assertSee('300 sqm')
        ->assertSee('₦15,000,000')
        ->assertSee('500 sqm')
        ->assertSee('₦25,000,000')
        ->assertSee('1,000 sqm')
        ->assertSee('₦50,000,000')
        ->assertSee('Prices exclude applicable taxes. Availability and property information are subject to confirmation.');
});

it('keeps the approved homepage section order', function () {
    $content = $this->get(route('home'))->getContent();

    $markers = [
        'id="services"',
        'id="omu-creek"',
        'id="about"',
        'id="diaspora"',
        'id="inspection-process"',
        'id="property-support"',
        'id="faq"',
        'id="contact"',
    ];

    $positions = array_map(fn (string $marker): int|false => strpos($content, $marker), $markers);

    expect($positions)->not->toContain(false)
        ->and($positions)->toBe(collect($positions)->sort()->values()->all());
});

it('does not render retired or production-note language', function () {
    $response = $this->get(route('home'));

    foreach ([
        'pending approval',
        'will appear here',
        'coming soon',
        'video pending',
        'approved project photography',
        'placeholder',
        '[Verified',
        'testimonial',
    ] as $phrase) {
        expect(strtolower($response->getContent()))->not->toContain(strtolower($phrase));
    }

    $response
        ->assertDontSee('href="#"', false)
        ->assertDontSee('hello@example.com', false);
});

it('renders a silent branded panel when the Omu Creek video is not configured', function () {
    config()->set('selotemna.featured_property.video_url', null);
    config()->set('selotemna.featured_property.video_poster', null);

    $this->get(route('home'))
        ->assertOk()
        ->assertDontSee('<video', false)
        ->assertDontSee('video pending', false)
        ->assertDontSee('coming soon', false);
});

it('renders an accessible Omu Creek video when media is configured', function () {
    config()->set('selotemna.featured_property.video_url', 'https://media.example.test/omu-creek.mp4');
    config()->set('selotemna.featured_property.video_poster', 'https://media.example.test/omu-creek-poster.jpg');

    $content = $this->get(route('home'))
        ->assertOk()
        ->assertSee('<video', false)
        ->assertSee('controls', false)
        ->assertSee('playsinline', false)
        ->assertSee('preload="metadata"', false)
        ->assertSee('poster="https://media.example.test/omu-creek-poster.jpg"', false)
        ->assertSee('src="https://media.example.test/omu-creek.mp4"', false)
        ->assertSee('data-event="omu_creek_video_play"', false)
        ->getContent();

    expect($content)->not->toContain('autoplay')
        ->not->toContain(' loop');
});

it('does not render a poster attribute when only a video URL is configured', function () {
    config()->set('selotemna.featured_property.video_url', 'https://media.example.test/omu-creek.mp4');
    config()->set('selotemna.featured_property.video_poster', null);

    $this->get(route('home'))
        ->assertOk()
        ->assertSee('<video', false)
        ->assertDontSee('poster=', false);
});

it('exposes stable Omu Creek inspection hooks without claiming confirmation', function () {
    $this->get(route('home'))
        ->assertOk()
        ->assertSee('data-event="omu_creek_inspection_click"', false)
        ->assertSee('data-property-interest="Omu Creek"', false)
        ->assertSee('Request an Omu Creek Inspection')
        ->assertSee('Submitting a request does not automatically confirm the appointment.')
        ->assertDontSee('Inspection confirmed.');
});

it('uses the approved navigation targets', function () {
    $response = $this->get(route('home'));

    foreach (['Home', 'About Us', 'Properties', 'Services', 'Contact'] as $label) {
        $response->assertSee($label);
    }

    $response
        ->assertSee('href="'.route('home').'#omu-creek"', false)
        ->assertDontSee('href="#"', false);
});

it('hides unavailable contact channels', function () {
    foreach (['phone', 'whatsapp', 'email', 'address', 'business_hours'] as $key) {
        config()->set("selotemna.{$key}", null);
    }

    $this->get(route('home'))
        ->assertOk()
        ->assertDontSee('tel:', false)
        ->assertDontSee('wa.me', false)
        ->assertDontSee('mailto:', false);
});

it('renders verified contact channels with safe links', function () {
    config()->set('selotemna.phone', '+234 800 000 0000');
    config()->set('selotemna.whatsapp', '+2348000000000');
    config()->set('selotemna.email', 'contact@selotemna.test');
    config()->set('selotemna.address', 'Verified office address');
    config()->set('selotemna.business_hours', 'Verified business hours');

    $this->get(route('home'))
        ->assertOk()
        ->assertSee('tel:+2348000000000', false)
        ->assertSee('https://wa.me/2348000000000', false)
        ->assertSee('mailto:contact@selotemna.test', false)
        ->assertSee('Verified office address')
        ->assertSee('Verified business hours');
});

it('keeps one h1 and does not introduce inspection persistence routes', function () {
    $content = $this->get(route('home'))->getContent();

    expect(substr_count($content, '<h1'))->toBe(1)
        ->and(Route::has('home'))->toBeTrue()
        ->and(Route::has('inspections.create'))->toBeFalse()
        ->and(Route::has('inspections.store'))->toBeFalse();
});
