# Selotemna Web Design System

## Design thesis

Build a modern corporate real-estate interface that is clear, credible, and inspection-led. Use white space, strong black typography, and Selotemna purple with restraint. Omu Creek is the homepage’s dominant property feature.

## Colour tokens

- brand-50: #F5F3FA
- brand-100: #E9E5F5
- brand-500: #5A4694
- brand-700: #28166B
- brand-800: #1C0F4D
- brand-950: #120933
- ink-50: #FAFAFB
- ink-200: #E5E3E8
- ink-500: #6B6972
- ink-800: #242329
- ink-950: #0A0A0B
- white: #FFFFFF

Use purple for primary actions and selected states. Use black for structural contrast and the footer. Do not introduce gradients, gold, green accents, multicolour decoration, glass effects, or heavy glow.

## Typography

- Headings and prominent values: Sora, weights 600 and 700
- Body, controls, and metadata: Manrope, weights 400 through 700
- Use responsive type with balanced headings and readable line lengths.
- Use tabular figures for property prices.

## Layout

- Standard container: 1280px
- Wide header/hero container: 1440px
- Mobile gutters: 20px
- Tablet gutters: 32px
- Desktop gutters: 48–64px
- Mobile section spacing: 64–80px
- Desktop section spacing: 96–120px

Build mobile first. Prevent horizontal overflow from 320px.

## Shape and structure

- Controls: 10–12px radius
- Content/media frames: 16–24px radius
- Prefer borders, spacing, and typography over shadows.
- Do not wrap every content block in a card.
- Use full pill shapes only for compact labels.

## Buttons and links

Primary actions use brand purple with white text. Secondary actions use a white or transparent surface with a clear border. Reversed actions use white on purple or black surfaces. All controls must have visible hover, active, focus-visible, and disabled states.

Minimum target size is 44px. Link labels should describe the action.

## Navigation

Desktop navigation includes Home, About Us, Properties, Services, and Contact. Properties targets Omu Creek until a catalogue route exists. Mobile navigation uses a right-side drawer with focus containment, Escape and backdrop dismissal, scroll locking, and focus return.

Never use an empty hash target.

## Featured land presentation

Use an asymmetric two-column desktop composition:

- 16:9 media on the left
- information and price options on the right
- visible type, title, per-sqm rate, allocation options, disclaimer, and inspection CTA

On mobile, put media first, follow with information, and use separated price rows and a full-width inspection action.

Without a configured video URL, use a silent decorative Selotemna surface. With a URL, use native controls, playsinline, preload metadata, and no autoplay or loop. Reserve the frame to prevent layout shift.

## Decorative media

When approved photography is unavailable:

- use abstract brand geometry or the supplied logo;
- keep the surface aria-hidden;
- do not present it as a real property;
- do not show production notes, approval status, or replacement instructions to visitors.

## Forms and disclosure controls

Keep visible labels, helpful errors, and clear focus states. FAQ triggers must be real buttons with aria-expanded and controlled panels. Do not animate essential layout continuously.

## Fixed mobile actions

The mobile contact bar may contain inspection and verified contact channels. It must use safe-area padding, stay below overlays, and hide when the footer is visible.

## Accessibility

- One H1
- Logical heading hierarchy and semantic landmarks
- Normal text contrast of at least 4.5:1
- Interface contrast of at least 3:1
- Keyboard access and visible focus
- 44px targets
- Meaningful alternative text for informative media
- Decorative graphics hidden from assistive technology
- Reduced-motion support
- No sound or video autoplay

## Review checklist

- Purple, white, black, and neutral system is consistent.
- Sora and Manrope load through Vite.
- The hero identifies Selotemna and prioritises inspection.
- Omu Creek prices are readable and consistent.
- No invented property information or proof appears.
- Missing contact actions are absent.
- Drawer, FAQ, footer observer, and mobile bar work by keyboard.
- No overflow occurs at 320px, 390px, tablet, or desktop.
- Video states reserve the same 16:9 frame.
- Production build and automated checks pass.
