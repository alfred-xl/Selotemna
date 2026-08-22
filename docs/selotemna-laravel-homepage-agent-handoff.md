# Selotemna Laravel Website — Project Direction Handoff

Status: Multipage public frontend implemented and aligned to the two-division business direction.

Governing skill: design-selotemna-web

## Implemented phase

This handoff records the implemented public architecture. The Laravel homepage is a concise overview of the approved two-division hierarchy.

Blade, CSS, JavaScript, routes, controllers, configuration, mail handling, and feature tests are aligned in this phase.

## Repository baseline

- Laravel Framework 13
- Blade rendering
- Tailwind CSS 4
- Vite
- Sora and Manrope
- Minimal framework-free JavaScript
- No complete project catalogue
- No persisted inspection-request workflow

## Approved business positioning

Selotemna has two primary public divisions:

1. Real Estate Development
2. Engineering & Construction

Land and property opportunities are presented under Real Estate Development.

Property Management is not a primary homepage division. It may remain a possible supporting service until its scope and future placement are confirmed.

Do not return to the earlier model of several equal homepage service divisions.

## Implemented public architecture

The site now provides:

- `/about` — About
- `/real-estate-development` — Real Estate Development
- `/real-estate-development/omu-creek` — verified Omu Creek detail
- `/engineering-construction` — Engineering & Construction
- `/projects` — Projects
- `/book-inspection` — request-oriented Book Inspection
- `/faq` — complete approved FAQ
- `/contact` — configured contact channels and enquiry pathways

The homepage is a concise overview that introduces the company and its two divisions, previews Omu Creek and project categories, and routes visitors to dedicated pages.

A generic Project Detail route is not implemented because verified project records and slugs have not been supplied.

## Projects architecture

Projects are now an important website content type.

The Projects page supports:

- Ongoing Projects
- Completed Projects
- Upcoming Projects

These labels are factual statuses. Do not assign a status until it is confirmed.

A future generic Project Detail page should present only approved project information, media, scope, location, status, and actions appropriate to that project.

## Temporary project data

Temporary project records are allowed only for development and layout testing.

They must:

- be removed automatically in production using the application environment;
- be visibly identified as layout samples in development;
- remain separate from verified Selotemna records;
- be excluded from SEO, structured data, sitemaps, analytics, statistics, case studies, and public proof;
- never be presented as completed or active Selotemna work.

Production-safe empty states work without temporary records.

## Omu Creek

Omu Creek remains the verified featured land opportunity under Real Estate Development.

Preserve these facts exactly:

- Land allocation
- Lagos State Government Allocation
- ₦50,000 per sqm
- 300 sqm: ₦15,000,000
- 500 sqm: ₦25,000,000
- 1,000 sqm: ₦50,000,000
- Prices exclude applicable taxes.
- Availability and property information are subject to confirmation.

Do not assign Omu Creek an ongoing, completed, or upcoming status until confirmed.

The approved FAQ source now supplies location, title claims, payment terms, charges, documentation stages, planned infrastructure, allocation timing, construction guidance, default terms, resale terms, and refund terms. The canonical wording is in the embedded skill’s references/faq-content.md file.

## Inspection behavior

Inspection submissions are requests. They do not automatically confirm appointments.

When a valid `SELOTEMNA_EMAIL` and a deliverable Laravel mailer are configured, the Book Inspection page collects and validates the visitor’s details, preferred date, opportunity of interest, and consent, then sends a rate-limited Laravel Mail message. The `log` and `array` mailers do not activate the public form. Without a safe delivery path, it hides the form and renders verified direct-contact options. It does not persist requests to a database.

## FAQ boundary

The homepage uses the approved four-question preview. The dedicated FAQ page contains all 15 approved Omu Creek questions with the Registered Survey fee corrected to ₦1,500,000.

## Implemented homepage direction

The homepage uses:

1. Concise corporate hero
2. Two division pathways
3. Concise Projects overview
4. Verified Omu Creek feature under Real Estate Development
5. Short About summary
6. Four-question FAQ preview
7. Final inspection/contact conversion
8. Footer

Detailed division, opportunity, project, inspection, FAQ, and contact content lives on dedicated pages.

## Implemented work

The approved phase completed the following work.

### Stage 1 — Content and navigation alignment

- replaced the legacy equal-service language with the two divisions;
- added route-aware navigation and unique metadata;
- kept Omu Creek under Real Estate Development;
- preserved conditional verified contact actions.

### Stage 2 — Projects foundation

- retained environment-filtered temporary project arrays for layout work;
- added a reusable accessible tab component;
- created the Projects route and view without a fictional detail route;
- added safe empty states and tests.

### Stage 3 — Dedicated division pages

- built Real Estate Development;
- built Engineering & Construction;
- left Property Management unpublished pending scope and placement.

### Stage 4 — Inspection, FAQ, About, and Contact

- implemented the conditional request-based inspection workflow;
- built the dedicated FAQ page from the approved 15-item source;
- added About and Contact pages using only approved or configured content.

### Stage 5 — Homepage consolidation

- reduced homepage detail;
- kept concise summaries and selected previews;
- routed deeper exploration to dedicated pages.

## Existing shared frontend

The current repository contains:

- a shared Blade layout;
- reusable header, footer, button, division, project tabs/cards, FAQ, contact, and CTA components;
- Tailwind brand tokens;
- Sora and Manrope;
- accessible desktop dropdown, viewport-level mobile drawer, project tabs, and FAQ JavaScript;
- conditional contact actions;
- a complete Omu Creek detail page and concise homepage summary.

The mobile drawer contains Book an Inspection as a normal navigation link. There is no persistent mobile contact bar; verified phone and WhatsApp links appear in the drawer only when configured.

Keep these foundations and the content hierarchy aligned in future changes.

## Assets

The active logo is public/assets/logo.png, a 189 × 153 transparent raster asset. Request a production SVG or higher-resolution transparent PNG before launch. Do not redraw or alter the wordmark.

Approved project photography and Omu Creek video/poster assets remain outstanding.

## Decisions still required

1. Project status definitions and approval owner
2. Verified records for each project category
3. Omu Creek project status
4. Generic Project Detail field requirements
5. Real Estate Development inventory model beyond Omu Creek
6. Detailed Engineering & Construction scope and enquiry fields
7. Property Management scope and placement
8. Verified contact details and operating locations
9. Approved project photography and Omu Creek video/poster assets
