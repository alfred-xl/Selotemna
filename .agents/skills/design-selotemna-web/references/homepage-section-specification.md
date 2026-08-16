# Selotemna Homepage Section Specification

## Goals

A first-time visitor should understand that Selotemna is a real-estate company, see its five public services, review the verified Omu Creek opportunity, and know that requesting an inspection is the primary next step.

The page must feel corporate and credible rather than like a crowded marketplace or generic luxury template.

## Required order

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

The mobile contact bar is a responsive shared control.

## Header

Desktop shows the logo, Home, About Us, Properties, Services, Contact, verified telephone action when available, and Book an Inspection.

Mobile shows the logo and accessible menu trigger. The drawer contains the same navigation and only verified contact channels. Close on selection, Escape, and backdrop click; return focus and restore page scrolling.

## Hero

Use one H1: Property solutions built around your next move.

Use the approved supporting sentence from the content reference. Book an Inspection is primary and Explore Properties targets Omu Creek. Do not add search, statistics, badges, a carousel, or invented imagery.

## Services

Show exactly five pathways: Property Development, Land Sales, House Sales, Property Management, and Construction.

Desktop uses an editorial heading column and divided pathway grid. Mobile uses full-width rows with visible descriptions. Avoid identical floating cards.

## Omu Creek

Section ID: omu-creek.

Desktop uses a 16:9 video surface on the left and property information on the right. Mobile places video first, then information, separated prices, disclaimer, and a full-width inspection action.

Render the configured name, type, title, per-sqm price, three allocation options, disclaimer, video URL, and poster. Prices must use consistent naira formatting and tabular figures.

CTA label: Request an Omu Creek Inspection.

CTA event: omu_creek_inspection_click.

Video event: omu_creek_video_play.

Preserve the property identity in a stable data attribute for a future form.

When the video URL is absent, render a quiet aria-hidden brand surface. Do not expose a production note. When present, use a semantic video with controls, playsinline, preload metadata, optional poster, useful fallback text, no autoplay, and no loop.

Do not infer location, amenities, infrastructure, landmarks, plot count, fees, payment plans, returns, appreciation, or completion dates.

## About

Use a split editorial composition. Keep corporate claims general and omit unverified history or statistics. Missing photography uses a silent decorative brand surface.

## Diaspora

Use a deep-purple split section. Invite a conversation without promising unverified remote services. Contact actions are conditional.

## Inspection process

Show three readable steps explaining that the visitor chooses an opportunity, shares details, and receives the next step. Do not imply automatic confirmation. The database and form workflow are outside the current homepage scope.

## Construction and property management

Use two related editorial panels with silent decorative media where approved imagery is unavailable. Do not claim detailed service scope before confirmation.

## Why Selotemna

Use four structural benefits with dividers. Do not add statistics, awards, customer counts, or unsupported proof.

## FAQ

Use a two-column layout on desktop and stacked layout on mobile. Triggers are buttons with aria-expanded and controlled panels. Include the approved Omu Creek inspection answer and safe due-diligence, payment, diaspora, construction, and management guidance.

## Final CTA and footer

Use a purple final CTA with inspection primary. Render WhatsApp and telephone only when verified.

Footer uses black, the supplied logo on a white plate, useful navigation, RC 7361086, and only verified contact fields. Never render empty labels, dummy links, or missing social icons.

## Mobile contact bar

Show Book Inspection plus verified WhatsApp/call icons. Use safe-area padding. Hide when the footer is substantially visible and while overlays are active.

## Accessibility

- One H1 and logical headings
- Semantic landmarks
- 44px minimum targets
- Visible focus
- Sufficient contrast
- Keyboard drawer and FAQ
- Decorative media aria-hidden
- Reduced-motion support
- No autoplay
- No horizontal overflow from 320px

## Analytics-ready hooks

- book_inspection_click
- omu_creek_inspection_click
- omu_creek_video_play
- whatsapp_click
- call_agent_click
- service_path_click

Do not install analytics until the platform and privacy requirements are confirmed.

## Definition of done

- The purple, white, black, and neutral system is consistent.
- Sora and Manrope are used.
- Omu Creek facts match the structured config.
- No invented content or visitor-facing production language appears.
- Missing contacts and video render safely.
- Drawer, FAQ, fixed actions, and footer observer work.
- Mobile, tablet, and desktop are inspected.
- Tests, formatting, and the production build pass.
