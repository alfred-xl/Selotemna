---
name: design-selotemna-web
description: Design, build, review, or refine Selotemna’s public-facing Laravel and Blade website, including its corporate homepage, property opportunities, service pages, inspection and contact flows, responsive styling, brand application, accessibility, and UI polish. Use for Selotemna public pages, its purple-white-black design system, property discovery, inspection conversion, or brand work. Do not use for admin dashboards, internal tools, backend-only work, or unrelated brands.
---

# Design Selotemna Web

Use this skill as the governing design and content source for Selotemna’s public website.

## Required reading

Before changing a homepage:

1. Read references/project-context.md.
2. Read references/design-system.md.
3. Read references/homepage-content.md.
4. Read references/homepage-section-specification.md.
5. Read references/real-estate-ux.md.
6. Read references/content-rules.md.
7. Read docs/selotemna-laravel-homepage-agent-handoff.md when it exists.

Read every selected reference completely. Inspect the repository and current implementation before proposing changes.

## Current public direction

- Selotemna is a corporate real-estate company.
- Public services are Property Development, Land Sales, House Sales, Property Management, and Construction.
- Omu Creek is the verified featured land opportunity on the homepage.
- Book or request an inspection is the primary conversion.
- Inspection submission remains a request until a representative confirms it.
- Never invent listings, locations, images, contacts, statistics, testimonials, amenities, fees, payment plans, or investment claims.

## Design read

Before implementation, state briefly:

- page type;
- primary audience;
- primary user goal;
- dominant visual idea;
- largest responsive or interaction risk.

## Implementation rules

- Inspect routes, controllers, Blade views, CSS, JavaScript, tests, assets, and user changes first.
- Preserve Laravel Blade, Tailwind CSS, and minimal framework-free JavaScript unless the repository materially changes.
- Reuse existing components and tokens.
- Use the supplied logo without redrawing, recolouring, stretching, or reconstructing it.
- Use Sora for headings and Manrope for body/interface text.
- Keep the visual system purple, white, black, and neutral.
- Do not use gradients, green accents, autoplay, fake proof, search bars, carousels, or card treatment on every section.
- Render verified contact actions only; never use dummy values or empty links.
- Do not build inspection persistence unless explicitly in scope.
- Keep one H1, logical landmarks, visible focus, 44px targets, keyboard interactions, reduced-motion support, and no overflow from 320px.

## Media policy

- Use approved local media only.
- Omu Creek video and poster URLs come from configuration.
- Without a video URL, render a quiet decorative branded panel with no visitor-facing production note.
- With a video URL, use semantic controls, playsinline, and preload metadata; never autoplay or loop.
- Obtain captions or a transcript before publishing spoken video.

## Verification

For implementation work:

1. Run composer test.
2. Run npm run build.
3. Run vendor/bin/pint --test; if it reports fixable formatting, run Pint and rerun.
4. Inspect mobile, tablet, and desktop in a real browser.
5. Check drawer focus/escape/backdrop, FAQ interaction, fixed mobile actions, video states, conditional contacts, keyboard access, and overflow.
6. Scan public code for dummy links, unapproved claims, production notes, and retired positioning.

Report files changed, checks completed, and unresolved production content.
