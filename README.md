# Selotemna

Selotemna’s public website is a Laravel Blade application for a company with two primary divisions:

1. Real Estate Development
2. Engineering & Construction

Land and property opportunities belong under Real Estate Development. Projects are a core part of the implemented public architecture.

Property Management is not a primary homepage division. It remains a possible supporting service while its scope and placement are confirmed.

## Current project state

The repository contains an implemented multipage public website and shared frontend foundation aligned to the two-division direction.

The homepage is a concise overview, with detailed content on dedicated company, division, opportunity, project, FAQ, inspection, and contact pages.

## Implemented public pages

- `/about` — About
- `/real-estate-development` — Real Estate Development
- `/real-estate-development/omu-creek` — Omu Creek detail
- `/engineering-construction` — Engineering & Construction
- `/projects` — Projects
- `/book-inspection` — Book Inspection request
- `/faq` — FAQ
- `/contact` — Contact

The homepage routes detailed exploration to these pages. A generic Project Detail route is intentionally deferred until verified project records and slugs are supplied.

## Projects direction

The Projects experience supports:

- Ongoing Projects
- Completed Projects
- Upcoming Projects

Use a project status only when verified. Omu Creek is currently the sole published project and is classified as an Upcoming Project. Ongoing and Completed categories remain hidden until verified records exist.

The project catalogue contains no demo or layout-sample records.

## Verified Omu Creek opportunity

Omu Creek remains a verified featured land opportunity under Real Estate Development.

Preserve these facts exactly:

- Land allocation
- Lagos State Government Allocation
- ₦50,000 per sqm
- 300 sqm: ₦15,000,000
- 500 sqm: ₦25,000,000
- 1,000 sqm: ₦50,000,000
- Prices exclude applicable taxes.
- Availability and property information are subject to confirmation.

Omu Creek is classified as an Upcoming Project. The label describes its project-development stage; current land availability remains subject to confirmation.

## Inspection behavior

Inspection submissions are requests. They do not automatically confirm an appointment. The Book Inspection page validates and stores each request in the `inspection_requests` table before attempting any optional staff email notification. Every saved request receives a human-readable reference and starts with the internal `new` status. A repeated submission token returns the existing request instead of creating a duplicate.

When a valid `SELOTEMNA_EMAIL` and deliverable Laravel mailer are configured, Selotemna also receives an email containing the saved request reference. Email delivery failure is recorded without discarding the database record. The form therefore remains available when email is unconfigured or uses the `log` or `array` mailer.

## Technology

- PHP 8.3 or newer
- Laravel 13
- Blade
- Tailwind CSS 4
- Vite
- Sora and Manrope
- Minimal framework-free JavaScript

## Setup

    composer install
    npm install
    copy .env.example .env
    php artisan key:generate
    php artisan migrate
    npm run build

For local development:

    composer run dev

If the local database does not have the sessions table, use a file session driver for local preview or run the project migrations before serving.

## Current configuration

Verified contact actions are hidden until values are provided:

    SELOTEMNA_PHONE=
    SELOTEMNA_WHATSAPP=
    SELOTEMNA_EMAIL=
    SELOTEMNA_ADDRESS=
    SELOTEMNA_BUSINESS_HOURS=

Omu Creek media is configured through:

    SELOTEMNA_OMU_CREEK_VIDEO_URL="https://pub-0625ccae8b454afab44be786c0943de3.r2.dev/OMU%20CREEK%202%20VIDEO%201.mp4"
    SELOTEMNA_OMU_CREEK_VIDEO_POSTER=
    SELOTEMNA_OMU_CREEK_SHORT_VIDEO_URL="https://pub-0625ccae8b454afab44be786c0943de3.r2.dev/SHORT%20FORM%201.mp4"
    SELOTEMNA_OMU_CREEK_SHORT_VIDEO_POSTER=

Without a video URL, Omu Creek uses a decorative branded panel. If a published video contains speech, provide captions or a transcript.

Approved editorial images should be saved in `public/assets/images` using these filenames:

- `selotemna-development-aerial.jpg`
- `selotemna-earthworks-truck.jpg`
- `selotemna-building-construction.jpg`

Missing image files fall back to branded media surfaces. Testimonials render only from approved records with confirmed publication permission.

The Book Inspection and Contact page heroes use `selotemna-inspection-consultation.jpg` and `selotemna-contact-meeting.jpg`, downloaded from the credited Pexels source pages recorded in `config/selotemna.php`. They are stock images, not representations of Selotemna staff or completed work. Public captions link to their source pages.

## Architecture

- app/Http/Controllers contains dedicated public-page and inspection controllers.
- app/Models/InspectionRequest.php represents persisted inspection requests and generates public references.
- database/migrations contains the inspection-request table alongside the Laravel foundation tables.
- app/Support/SelotemnaContent.php normalises shared opportunity, project, FAQ, and safe contact data.
- config/selotemna.php contains Omu Creek, verified projects, approved testimonial, media, FAQ, and contact configuration.
- resources/views contains the shared Blade layout, components, public pages, and inspection email.
- resources/css/app.css contains Tailwind brand tokens and shared styles.
- resources/js/app.js handles the desktop dropdown, mobile drawer, project tabs, FAQ, form-error focus, and submit-once behavior.
- tests/Feature/PublicWebsiteTest.php covers the multipage public frontend and persisted inspection-request behavior.
- .agents/skills/design-selotemna-web contains the governing project guidance.
- docs/selotemna-laravel-homepage-agent-handoff.md records the implemented architecture and outstanding inputs.

## Commands

    composer test
    npm run build
    vendor/bin/pint --test

On Windows PowerShell, npm.cmd run build may be required when script execution blocks npm.ps1.

## FAQ content

The homepage uses questions 1, 4, 5, and 6 from the approved Omu Creek FAQ source. The dedicated FAQ page contains all 15 questions. The Registered Survey fee is ₦1,500,000.

## Brand asset

The active logo is public/assets/logo.png. It is a supplied 189 × 153 transparent raster image. A higher-resolution transparent PNG or SVG remains preferable for production; do not redraw or recolour the current wordmark.
