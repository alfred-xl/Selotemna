# Selotemna Homepage Section Specification

## Status

This document describes the implemented concise multipage homepage direction.

## Goals

A first-time visitor should understand that Selotemna operates through Real Estate Development and Engineering & Construction, see a concise selection of verified opportunities or projects, and know the appropriate next step.

The homepage should act as a clear overview and route visitors to dedicated public pages. It should not contain every detail about the company, divisions, projects, inspections, FAQs, or contact process.

## Implemented concise structure

The homepage uses this sequence:

1. Header and navigation
2. Concise corporate hero
3. Two division pathways
4. Projects preview
5. Verified Omu Creek summary
6. Short About summary
7. Four-question FAQ preview
8. Final inspection/contact conversion
9. Footer

Audience-specific content, including diaspora guidance, should be concise on the homepage and move to an appropriate dedicated page when its placement is confirmed.

## Header

Navigation includes Home, About, an accessible Divisions menu, Projects, FAQ, Contact, and Book an Inspection. The Divisions menu contains Real Estate Development and Engineering & Construction. Smaller screens use the accessible drawer.

## Hero

The hero identifies Selotemna and introduces the relationship between its two divisions without five-equal-service messaging.

Use the H1 `Developing places. Building with purpose.`, a short supporting statement, and the actions Request an Inspection and Explore Omu Creek.

## Division pathways

Show two primary pathways only. Use the approved asymmetric editorial layout: an approximately 38% left column for the eyebrow, heading, and introduction, and an approximately 62% right column for two stacked pathways. Use restrained `01` and `02` markers, thin dividers instead of card containers, subtle `brand-50` hover and focus feedback, restrained arrow movement, and a clean mobile stack.

Eyebrow: `What we do`

Heading: `Explore our developments. Discuss your next project.`

Introduction: `Selotemna operates through Real Estate Development and Engineering & Construction, giving visitors a clear way to explore our development work, review current opportunities or begin a project conversation.`

### Real Estate Development

Use: `Explore Selotemna’s real-estate developments and property opportunities. Omu Creek, our latest project, is the current featured opportunity for buyers and investors to review before making an enquiry or requesting an inspection.`

CTA: `Explore Real Estate Development`

### Engineering & Construction

Use: `Bring an engineering or construction requirement to Selotemna. Share the site, scope and current stage so the team can understand the project and identify the appropriate next step.`

CTA: `Explore Engineering & Construction`

Property Management must not appear as an equal division. If retained, treat it as a supporting service and do not finalise its homepage placement until confirmed.

## Omu Creek

Present Omu Creek as Selotemna’s latest project and verified featured land opportunity under Real Estate Development. Selotemna has undertaken previous projects; never describe Omu Creek as its first or only project, and do not invent historical project records.

Preserve exactly:

- Land allocation
- Lagos State Government Allocation
- ₦50,000 per sqm
- 300 sqm: ₦15,000,000
- 500 sqm: ₦25,000,000
- 1,000 sqm: ₦50,000,000
- Prices exclude applicable taxes.
- Availability and property information are subject to confirmation.

Omu Creek is classified as an Upcoming Project. Continue to keep current availability subject to confirmation.

Continue to reserve media space safely, format prices consistently, identify the inspection interest, and treat the CTA as a request rather than confirmation.

## Projects overview

Projects are a core website destination. The homepage should show only a concise preview and route visitors to the Projects page. Omu Creek may be the current project with detailed public information in the repository, but that publishing boundary must remain distinct from Selotemna’s wider project history.

The Projects page supports three categories:

- Ongoing Projects
- Completed Projects
- Upcoming Projects

Category labels are factual statuses, not decorative filters. Show a status only when verified.

The public catalogue contains no temporary project records. Demo records are permitted only in isolated development and layout testing, must be disabled by default in production, excluded from SEO and structured data, and never presented as verified Selotemna work.

## About summary

Use a brief corporate introduction and link to About. Do not publish unverified company history, statistics, locations, awards, or differentiators.

## Inspection summary

Explain that the visitor selects an opportunity or project, shares details, and receives follow-up. Submission does not automatically confirm an appointment.

The Book Inspection page contains the request explanation, conditional form, validation, consent, and request-received state.

## FAQ preview

Use the approved four-question homepage preview in references/faq-content.md. Keep the complete 15-question set on the dedicated FAQ page.

Link to the implemented FAQ route and avoid reproducing the complete FAQ library on the homepage.

## Final CTA and footer

Use a concise final CTA with Book Inspection primary. Render direct contact channels only when verified. Footer navigation reflects implemented public pages without inventing legal or social links.

## Accessibility and responsive requirements

- One H1 and logical headings
- Semantic landmarks
- 44px minimum targets
- Visible focus and sufficient contrast
- Keyboard-accessible navigation and disclosures
- Decorative media hidden from assistive technology
- Reduced-motion support
- No autoplay
- No horizontal overflow from 320px

## Implementation status

The homepage, navigation, shared components, tests, metadata, and project data handling are implemented together. Keep this specification aligned with the public interface when it changes.
