# Selotemna Motion System

## Principles

Motion communicates calm confidence, precision, and structural progression. Use it to clarify hierarchy, confirm interaction, and connect related content without delaying information or competing with property and project facts.

All motion is progressive enhancement. Content and controls remain visible, ordered, and usable when JavaScript is unavailable.

## Tokens

Define the canonical values in `resources/css/app.css`:

- Fast feedback: 160ms
- Standard interaction: 240ms
- Content reveal: 520ms
- Hero media settle: 760ms
- Stagger interval: 80ms
- Reveal distance: 16px desktop and 12px mobile
- Standard easing: `cubic-bezier(0.2, 0.8, 0.2, 1)`
- Emphasised easing: `cubic-bezier(0.16, 1, 0.3, 1)`

Consume these values through CSS custom properties. Do not scatter unrelated duration values through Blade templates.

## Entrances

Use opacity with a small upward translation for content and opacity with a subtle scale for approved media. Hero order is eyebrow, H1, supporting copy, CTA group, then media. Keep movement at or below 20px, make the primary CTA available quickly, and run the sequence once per page load.

Do not animate individual words or every paragraph.

## Scroll reveals and stagger

Use stable `data-reveal` hooks and one shared IntersectionObserver where practical. Mark only off-screen elements as pending after JavaScript is ready, reveal each once, and stop observing it. Content must remain visible if JavaScript fails.

Appropriate targets include section headings, division pathways, Omu Creek summaries and price rows, project cards, short numbered processes, media frames, FAQ groups, and conversion sections.

Use `data-reveal-group` for related children. Stagger no more than six children with the shared interval. Remove group delay and use the shorter distance on small screens.

## Hover and press feedback

- Buttons transition colour with the fast token and use a `scale(0.98)` press response.
- Text links use a restrained underline or move the directional icon slightly.
- Project cards move no more than 3px upward and scale approved imagery no more than 1.02.
- Essential information and actions never depend on hover.

## Interactive components

- Desktop dropdown: short opacity and vertical-position transition; preserve Escape, outside-click, and keyboard behavior.
- Mobile drawer: standard horizontal transition; preserve scroll locking, focus containment, Escape and backdrop dismissal, focus return, and inert state.
- Project tabs: apply a short opacity and 4px translation settle to the newly selected panel without delaying availability.
- FAQ: animate panel opacity and a 4px translation while preserving `aria-expanded`, controlled-panel state, keyboard operation, and immediate semantic availability. Rotate the icon with the standard token.
- Header: add a subtle border or shadow state after the page moves beyond the top sentinel. Do not run continuous scroll calculations.

## Media

Reserve explicit dimensions or aspect ratios. Video controls remain immediately usable. Never autoplay, loop, parallax, or transform the video itself on hover. Apply card-hover scaling only to suitable images inside clipped frames.

## Page transitions

Normal browser navigation is the baseline. Add a same-origin View Transition only when it is a progressive, 180–250ms opacity treatment that does not intercept forms, validation, history navigation, or anchor links. Disable it for reduced motion. If those guarantees are uncertain, do not implement it.

## Responsive behavior

Verify from 320px upward. Use the smaller reveal distance on mobile, remove large stagger sequences, avoid expensive effects, and prevent transforms from creating horizontal overflow. Do not add a fixed contact bar as part of motion work.

## Reduced motion

Under `prefers-reduced-motion: reduce`:

- remove reveal translation and stagger delay;
- keep all reveal content visible;
- disable image scaling and smooth scrolling;
- make state transitions effectively immediate;
- skip Web Animations API entrances and settles;
- preserve focus, contrast, and every interaction state.

Respond to preference changes during a session by making pending content visible.

## Performance

Prefer transform and opacity. Avoid continuous scroll calculations, repeated width or height animation, permanent `will-change`, multiple observers for equivalent reveal work, layout shifts, and replaying reveals after small scroll changes. Disconnect observers when their work is complete.

## Prohibited patterns

Do not add parallax, scroll hijacking, bouncing buttons, floating decoration, animated counters, autoplay carousels, looping logo animation, gradients, glassmorphism, cursor-following effects, word-by-word animation, large clip-path reveals, flashing, or motion that obscures content.

## Motion QA

- Check hero order and immediate CTA access.
- Scroll every public-page section and confirm each reveal runs once.
- Test dropdown, drawer, tabs, FAQ, videos, form validation, and focus return by keyboard.
- Verify 320px, 390px, 768px, and 1440px without overflow.
- Test `prefers-reduced-motion: reduce`, JavaScript disabled, and browsers without View Transitions support.
- Confirm video controls, forms, history, anchors, and semantic order remain unaffected.
- Run PHP tests, Pint, the production Vite build, and route inspection.
