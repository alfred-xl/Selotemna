---
name: design-selotemna-web
description: "Design, build, review, or refine Selotemna’s multipage public Laravel and Blade website across Real Estate Development and Engineering & Construction, including the concise homepage, projects, Omu Creek, inspection and contact flows, accessibility, responsive styling, and brand application. Do not use for admin dashboards, internal tools, backend-only work, or unrelated brands."
---

# Design Selotemna Web

Use this skill as the governing workflow for Selotemna’s public website.

## Read first

For homepage, public-page, or architecture work, read:

1. references/project-context.md
2. references/design-system.md
3. references/homepage-content.md
4. references/homepage-section-specification.md
5. references/real-estate-ux.md
6. references/content-rules.md
7. docs/selotemna-laravel-homepage-agent-handoff.md when present

Read each selected reference completely. Keep detailed business facts and page specifications in the references rather than duplicating them here.

For FAQ work, also read references/faq-content.md completely.

## Workflow

1. Inspect routes, controllers, Blade views, CSS, JavaScript, tests, assets, and existing user changes.
2. State the page type, audience, primary goal, dominant visual idea, and main responsive risk.
3. Confirm the requested scope and preserve Laravel Blade, Tailwind CSS, and minimal framework-free JavaScript unless the repository has materially changed.
4. Reuse existing components, tokens, and supplied assets.
5. Implement only verified content and conditionally render missing contact or media channels.
6. Verify accessibility, responsive behavior, content truth, formatting, tests, and the production build in proportion to the change.

## Guardrails

- Treat inspection submissions as requests, never automatic appointment confirmations.
- Keep demo project content development-only, disabled by default in production, and clearly separate from verified Selotemna work.
- Do not infer project status, property facts, locations, contacts, proof, statistics, testimonials, payment terms, or investment outcomes.
- Keep the visual system purple, white, black, and neutral; use Sora and Manrope.
- Maintain one H1, semantic landmarks, visible focus, 44px targets, reduced-motion support, and no horizontal overflow from 320px.
- Do not introduce a client-side application framework without a material repository or product requirement.
- Keep the implemented named-route architecture and shared Blade components aligned across all public pages.

Report files changed, checks completed, unresolved contradictions, and content decisions still awaiting confirmation.
