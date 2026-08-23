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
- Omu Creek is the sole published project
- Persisted, request-oriented Omu Creek inspection workflow

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

The homepage is a concise overview that introduces the company and its two divisions, features Omu Creek as the sole published Upcoming Project, and routes visitors to dedicated pages.

A generic Project Detail route is not implemented because verified project records and slugs have not been supplied.

## Projects architecture

Projects are now an important website content type.

The Projects page supports:

- Ongoing Projects
- Completed Projects
- Upcoming Projects

These labels are factual statuses. Omu Creek is currently the only published record and is classified as an Upcoming Project. Ongoing and Completed categories remain hidden until verified records exist.

A future generic Project Detail page should present only approved project information, media, scope, location, status, and actions appropriate to that project.

## Verified project data

The repository no longer contains development-only project records. The public catalogue contains Omu Creek only. Do not add demo records to the public project configuration; add another project only after its name, division, status, summary, media and public route have been approved.

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

Omu Creek is classified as an Upcoming Project. This status describes the project-development stage and does not replace the current availability disclaimer.

The approved FAQ source now supplies location, title claims, payment terms, charges, documentation stages, planned infrastructure, allocation timing, construction guidance, default terms, resale terms, and refund terms. The canonical wording is in the embedded skill’s references/faq-content.md file.

## Inspection behavior

Inspection submissions are requests. They do not automatically confirm appointments.

The Book Inspection page is always available. It validates the visitor’s contact details, fixed Omu Creek interest, preferred date, contact method, optional message and request acknowledgement on the server. A valid submission is saved in `inspection_requests` with a unique submission token, human-readable reference, `new` status and consent timestamp before any notification is attempted.

When a valid `SELOTEMNA_EMAIL` and deliverable Laravel mailer are configured, the saved request is also emailed to Selotemna. Email failure is recorded on the request without discarding it or presenting the visitor with a failed submission. Reusing the same submission token returns the existing request instead of creating a duplicate. The receipt repeats that the request and preferred date do not confirm an appointment.

## FAQ boundary

The homepage uses the approved four-question preview. The dedicated FAQ page contains all 15 approved Omu Creek questions with the Registered Survey fee corrected to ₦1,500,000.

## Implemented homepage direction

The homepage uses:

1. Concise corporate hero
2. Two division pathways
3. Verified Omu Creek Upcoming Project feature under Real Estate Development
4. Short About summary
5. Verified testimonial preview when approved records exist
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

- removed all environment-filtered temporary project arrays;
- established Omu Creek as the sole Upcoming Project;
- retained the Projects route without a fictional generic detail route;
- hid categories that do not contain verified records.

### Stage 3 — Dedicated division pages

- built Real Estate Development;
- built Engineering & Construction;
- left Property Management unpublished pending scope and placement.

### Stage 4 — Inspection, FAQ, About, and Contact

- implemented the persisted, rate-limited request-based inspection workflow;
- added an accessible saved-request receipt and optional staff notification;
- rebuilt Contact around verified channels and focused public enquiry pathways;
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

Two approved Omu Creek videos are integrated from public Cloudflare R2 development URLs: a short preview in the Omu Creek feature and a detailed video on the dedicated Omu Creek page. Three approved editorial image placements use `selotemna-development-aerial.jpg`, `selotemna-earthworks-truck.jpg`, and `selotemna-building-construction.jpg` from `public/assets/images` when those files are present. Video poster images, accessible captions/transcripts and a production R2 custom domain remain outstanding.

Book Inspection and Contact use the local `selotemna-inspection-consultation.jpg` and `selotemna-contact-meeting.jpg` files downloaded from credited Pexels source pages retained in `config/selotemna.php`. Public captions identify them as editorial imagery. They must not be described as Selotemna staff, offices, projects or customers.

Testimonials are supported but remain hidden until a quote, public name, relevant division or project, editorial approval and publication permission are recorded.

## Decisions still required

1. Status definitions and approval criteria for future Ongoing and Completed projects
2. Generic Project Detail field requirements beyond Omu Creek
3. Real Estate Development inventory model beyond Omu Creek
4. Detailed Engineering & Construction scope and enquiry fields
5. Property Management scope and placement
6. Verified contact details and operating locations
7. Commercial-use confirmation and source files for the three supplied editorial images
8. Approved testimonial quotations, identities, service relevance and publication permission
9. Omu Creek video posters and accessible captions/transcripts
10. Production custom domain for the approved Omu Creek videos currently served through `r2.dev`
11. Inspection-request privacy notice, retention period and deletion process
12. Future staff handling method for saved requests beyond database records and optional email notification
