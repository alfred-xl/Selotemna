# Selotemna

Selotemna’s public corporate real-estate homepage is built with Laravel Blade, Tailwind CSS, Vite, and small framework-free JavaScript modules.

## Requirements

- PHP 8.3 or newer
- Composer
- Node.js and npm
- A database matching the local Laravel configuration when database-backed sessions are used

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

## Homepage configuration

Contact actions are hidden until verified values are provided:

    SELOTEMNA_PHONE=
    SELOTEMNA_WHATSAPP=
    SELOTEMNA_EMAIL=
    SELOTEMNA_ADDRESS=
    SELOTEMNA_BUSINESS_HOURS=

Omu Creek media is optional:

    SELOTEMNA_OMU_CREEK_VIDEO_URL=
    SELOTEMNA_OMU_CREEK_VIDEO_POSTER=

Without a video URL, the homepage renders a decorative branded 16:9 panel. With a URL, it renders a native controlled video without autoplay or looping.

If the published video contains speech, supply captions or a transcript before production release.

## Verified homepage opportunity

Omu Creek is configured in config/selotemna.php with:

- Land allocation
- Certificate of Occupancy (C of O)
- ₦50,000 per sqm
- 300 sqm at ₦15,000,000
- 500 sqm at ₦25,000,000
- 1,000 sqm at ₦50,000,000
- Prices excluding applicable taxes
- Availability and property information subject to confirmation

Do not add a location or other property claims until verified.

## Commands

    composer test
    npm run build
    vendor/bin/pint --test

On Windows PowerShell, npm.cmd run build may be required when script execution blocks npm.ps1.

## Architecture

- app/Http/Controllers/HomeController.php assembles page data and safe contact links.
- config/selotemna.php is the single source for the featured property and contact environment values.
- resources/views/home.blade.php composes the homepage.
- resources/views/layouts and resources/views/components contain shared Blade UI.
- resources/css/app.css contains Tailwind v4 brand tokens and shared styles.
- resources/js/app.js handles the mobile drawer, FAQ, and mobile contact bar.
- tests/Feature/HomepageTest.php covers public content and conditional states.

The inspection database flow is intentionally not implemented.

## Brand asset

The active logo is public/assets/logo.png. It is a supplied 189 × 153 transparent raster image. A higher-resolution transparent PNG or SVG remains preferable for production; do not redraw or recolour the current wordmark.
