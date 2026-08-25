# Selotemna Real-Estate and Project UX Patterns

## Division discovery

The public experience begins with two clear choices:

1. Real Estate Development
2. Engineering & Construction

Land and property opportunities belong under Real Estate Development. Engineering and construction work belongs under Engineering & Construction. Do not model the homepage as five equal service choices.

## Real Estate Development opportunities

Design around verified inventory:

- keep verified name, type, price, dimensions, documentation, and inspection action visible;
- do not infer location or specifications;
- avoid claims about returns or appreciation;
- use a clear empty state when no verified opportunity exists;
- route detailed opportunity browsing to the Real Estate Development page.

## Featured land

For a verified land opportunity, prioritise:

1. Name
2. Land type
3. Title document
4. Price per sqm
5. Allocation sizes and total prices
6. Tax and availability disclaimer
7. Inspection request

Use tabular figures and separated rows. On mobile, place media first and keep prices free from horizontal overflow.

Omu Creek is Selotemna’s latest project and is classified as an Upcoming Project. The status does not replace the requirement to confirm current availability.

## Projects discovery

Projects are a central public content type. The Projects page supports Ongoing Projects, Completed Projects, and Upcoming Projects. Only verified records may be published in production.

Selotemna has undertaken previous projects, while Omu Creek is its latest project and the current project with detailed public information in the repository. Do not turn the absence of approved historical records into an “only project” claim, and do not invent names, locations, images, statistics or details for previous work.

Do not infer status from photography, marketing language, dates, or perceived completion. A project status must come from approved content.

A project preview should contain only verified fields such as:

- name;
- division;
- confirmed status;
- approved summary;
- approved media;
- verified location when supplied;
- detail URL;
- relevant enquiry or inspection action.

A future generic Project Detail page should provide the approved overview, scope or opportunity information, media, status, documentation where relevant, and next action without unsupported claims. Omu Creek currently uses its own verified detail route and is classified as an Upcoming Project.

## Demo project data

The public project catalogue currently contains no demo records. Demo data is allowed only for isolated local development, component testing, and layout validation.

Requirements:

- keep temporary records disabled by default in production;
- mark them clearly as demo data in development;
- keep them out of SEO, structured data, sitemaps, analytics, and public proof;
- do not use real-looking claims that could be confused with verified work;
- ensure empty and production-safe states still work without demo records.

## Inspection requests

Treat submission as a request until a representative confirms it. The implemented page collects the visitor’s details, opportunity of interest, preferred date, and consent only when a valid email destination is configured.

Validate on the server, associate errors with fields, preserve entered data, prevent repeated submission, and confirm receipt without promising an appointment.

## Direct contact

- Render telephone, WhatsApp, and email links only from verified values.
- Never render dummy numbers, empty links, or bare hash targets.
- Keep WhatsApp styling within the purple brand system.
- Hide unavailable channels.

## Engineering & Construction enquiries

Collect only the information needed to route an enquiry, such as project type, proposed location, current stage, approximate scope, preferred contact method, and message. Do not publish or promise a detailed delivery process until confirmed.

## Property Management

Treat Property Management as a possible supporting service, not a primary division. Do not design a homepage pathway or dedicated page until its scope and placement are confirmed. Do not imply tenant management, rent collection, maintenance, inspections, or reporting unless approved.

## Media and mobile behavior

- Use approved opportunity and project media only.
- Reserve dimensions with aspect ratio.
- Use a decorative brand surface when approved media is unavailable.
- Native video must have controls, playsinline, and no autoplay.
- Provide captions or a transcript for spoken media before production publishing.
- Prevent horizontal overflow from 320px.
- Keep inspection and verified contact actions accessible without covering content.
- Use safe-area padding and do not rely on hover for essential information.
- Keep tap targets at least 44px.
