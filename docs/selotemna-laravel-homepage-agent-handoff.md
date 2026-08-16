# Selotemna Laravel Homepage — Current Handoff

Status: Homepage frontend implemented; inspection persistence intentionally deferred.

Governing skill: design-selotemna-web

## Repository audit

- Laravel Framework 13.25.0
- PHP 8.4.22
- Blade rendering
- Tailwind CSS 4.3.3
- Vite 8.2.1
- laravel-vite-plugin 3.2.0
- Sora and Manrope loaded through the Laravel Vite font helper
- Minimal framework-free JavaScript
- No client-side application framework or component library
- No property catalogue or inspection-request model
- Pest 5 test suite
- Every repository file currently appears untracked, so Git has no baseline for separating earlier changes

## Design read

This is a corporate, inspection-led real-estate homepage for buyers, families, investors, diaspora customers, and businesses.

The visual direction is a white-led editorial composition with disciplined black typography, restrained Selotemna purple, and Omu Creek as the single verified property opportunity.

The primary goal is to understand the five services and request an inspection. The largest responsive risk is keeping the Omu Creek media/pricing block, mobile drawer, and fixed mobile actions readable without overflow or obstruction.

## Public services

Use exactly:

1. Property Development
2. Land Sales
3. House Sales
4. Property Management
5. Construction

## Homepage order

1. Header
2. Corporate hero
3. Five services
4. Omu Creek
5. About
6. Diaspora
7. Inspection process
8. Construction and property management
9. Why Selotemna
10. FAQ
11. Final CTA
12. Footer

The mobile contact bar is rendered as a shared responsive component.

## Omu Creek data contract

The controller receives one structured config array containing:

- name;
- type;
- title;
- price per sqm;
- allocation options;
- disclaimer;
- video URL;
- video poster.

Verified values:

- Omu Creek
- Land allocation
- Certificate of Occupancy (C of O)
- ₦50,000 per sqm
- 300 sqm at ₦15,000,000
- 500 sqm at ₦25,000,000
- 1,000 sqm at ₦50,000,000
- Prices exclude applicable taxes.
- Availability and property information are subject to confirmation.

Do not add a location, unverified fees, payment plans, plot count, amenities, infrastructure, landmarks, returns, appreciation, or completion dates.

## Environment configuration

Keep values empty until verified:

    SELOTEMNA_PHONE=
    SELOTEMNA_WHATSAPP=
    SELOTEMNA_EMAIL=
    SELOTEMNA_ADDRESS=
    SELOTEMNA_BUSINESS_HOURS=
    SELOTEMNA_OMU_CREEK_VIDEO_URL=
    SELOTEMNA_OMU_CREEK_VIDEO_POSTER=

Telephone, WhatsApp, and email actions render only from safe verified values.

## Video behaviour

When SELOTEMNA_OMU_CREEK_VIDEO_URL is empty, the page renders a quiet decorative 16:9 Selotemna panel.

When set, it renders a semantic video with controls, playsinline, preload metadata, an optional configured poster, useful fallback text, and the omu_creek_video_play event hook. It must not autoplay or loop.

If the supplied video contains speech, captions or a transcript remain required for production.

## Inspection boundary

The homepage explains the three-step process and exposes a stable property-interest attribute on the Omu Creek CTA. It does not create a database record, route, form, model, migration, or automatic appointment.

A future implementation must treat submission as a request and must not claim appointment confirmation.

## Shared frontend

- resources/views/layouts/site.blade.php owns the page shell and metadata.
- Header implements desktop navigation and an accessible mobile drawer.
- Footer and mobile bar render contact actions conditionally.
- resources/js/app.js implements drawer focus/escape/backdrop behaviour, FAQ disclosure, and footer-aware mobile bar hiding.
- resources/css/app.css contains the Tailwind v4 tokens and shared layout utilities.

## Logo

The active asset is public/assets/logo.png, 189 × 153 with transparency. It is suitable for the current frontend but remains low resolution. Request a production SVG or higher-resolution transparent PNG before launch. Do not redraw or alter the wordmark.

## Required verification

Run:

    composer test
    npm run build
    vendor/bin/pint --test

Also inspect 320px, 390px, tablet, and desktop widths in a real browser. Check the drawer, FAQ, Omu Creek prices, video states, conditional contacts, fixed mobile bar, keyboard focus, and horizontal overflow.

## Outstanding production content

1. Full Omu Creek location
2. Actual Omu Creek video and poster
3. Captions or transcript if the video contains speech
4. Production-quality logo
5. Verified telephone, WhatsApp, email, address, and business hours
6. Any applicable charges beyond the stated tax qualifier
7. Complete inspection request workflow
8. Approved project and corporate photography
9. Operating locations and legal-page content
