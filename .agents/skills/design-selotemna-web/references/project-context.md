# Selotemna Project Context

## Business positioning

Selotemna is a Nigerian company with two primary public divisions:

1. Real Estate Development
2. Engineering & Construction

Use these division names consistently. Do not present Property Development, Land Sales, House Sales, Property Management, and Construction as five equal company or homepage divisions.

Real Estate Development contains land and property opportunities, including verified featured opportunities. Engineering & Construction contains the company’s engineering, building, and construction work.

Property Management is not a primary homepage division. It may remain documented as a possible supporting service while its scope and future placement are confirmed.

The supplied logo displays RC 7361086.

## Website purpose

The public website should:

- establish Selotemna’s corporate identity;
- explain the two divisions clearly;
- present verified land and property opportunities under Real Estate Development;
- present verified engineering and construction projects;
- make projects a central part of the site architecture;
- support inspection requests and verified contact paths;
- build confidence without unsupported claims.

The homepage should become a concise overview. Detailed company, division, project, inspection, FAQ, and contact information belongs on dedicated pages.

## Audiences

Design for property buyers, investors, families, diaspora customers, businesses, land buyers, engineering clients, and construction clients. Do not list every audience in the hero.

## Implemented public pages

- `/` — concise corporate homepage
- `/about` — About
- `/real-estate-development` — Real Estate Development
- `/real-estate-development/omu-creek` — verified Omu Creek detail
- `/engineering-construction` — Engineering & Construction
- `/projects` — Projects
- `/book-inspection` — request-oriented Book Inspection
- `/faq` — complete approved Omu Creek FAQ
- `/contact` — configured contact channels and enquiry pathways

The homepage remains the overview and navigation entry point.

A generic Project Detail route is intentionally not implemented. Add one only when verified project records, slugs, and detail content are supplied.

## Projects architecture

Projects are an important public content type. The implemented Projects experience supports:

- Ongoing Projects
- Completed Projects
- Upcoming Projects

A project must not receive one of these statuses until the status is verified.

Temporary project arrays remain in PHP configuration for development and layout testing. They are visibly identified as layout samples outside production and automatically removed in production. Production renders one useful empty state per category. Temporary data must not be used for SEO, structured data, analytics reporting, or public proof.

## Verified Omu Creek facts

Omu Creek is the verified featured land opportunity under Real Estate Development.

Preserve these facts exactly:

- Land allocation
- Lagos State Government Allocation
- ₦50,000 per sqm
- 300 sqm: ₦15,000,000
- 500 sqm: ₦25,000,000
- 1,000 sqm: ₦50,000,000
- Prices exclude applicable taxes.
- Availability and property information are subject to confirmation.

Omu Creek’s project status is unconfirmed. Do not classify it as ongoing, completed, or upcoming.

The approved FAQ source now verifies location, title claims, payment terms, statutory charges, documentation stages, planned infrastructure, allocation timing, construction guidance, default terms, resale terms, and refund terms. Use references/faq-content.md for the complete approved wording. Do not infer facts beyond that source.

## Inspection implementation

An inspection submission is a request. It does not automatically confirm an appointment. When a valid `SELOTEMNA_EMAIL` destination and a deliverable Laravel mailer are configured, the page renders a server-validated, rate-limited Laravel Mail form. The `log` and `array` mailers do not activate the public form. Without a safe delivery path, the form is hidden and only verified direct-contact options are rendered. There is no database persistence.

## Technical baseline

- Laravel 13
- PHP 8.3 or newer
- Blade
- Tailwind CSS v4
- Vite
- Sora headings and Manrope body/interface type
- Minimal framework-free JavaScript

Do not introduce a client-side application framework unless the repository has materially changed and the requirement justifies it.

## Assets and content truth

The active supplied logo is public/assets/logo.png. It is a low-resolution raster asset; request a transparent SVG or higher-resolution transparent PNG before launch-quality delivery.

There is no verified full project catalogue, testimonial set, statistics, operating-location list, or complete contact information. Hide missing content or use silent decorative brand surfaces. Never expose planning notes or temporary records as public facts.

## Decisions awaiting confirmation

- Property Management scope and future page placement
- Definitions and editorial approval criteria for the three project statuses
- Which verified Selotemna work belongs in each project category
- Omu Creek’s project status
- Verified project-detail records, slugs, media, and fields
- Detailed Engineering & Construction capabilities
- Verified company contact details and operating locations beyond the approved Omu Creek FAQ
