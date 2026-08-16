# Selotemna Real-Estate UX Patterns

## Property discovery

- Design around real inventory.
- Do not add search or filters before a catalogue supports them.
- Keep verified name, type, price, dimensions, documentation, and inspection action visible.
- Do not infer location or specifications.
- Avoid claims about returns or appreciation.
- Use a clear empty state when no verified opportunity exists.

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

## Future property details

Organise future detail pages as:

1. Name, category, verified location, and verified price
2. Approved gallery or video
3. Primary specifications
4. Clear description
5. Verified features and amenities
6. Verified documentation
7. Location context when approved
8. Inspection and direct-contact actions

## Inspection requests

Treat submission as a request until a representative confirms it. A future workflow should collect full name, telephone, property of interest, preferred date, and consent, with optional WhatsApp, email, time, message, and contact preference.

Validate on the server, associate errors with fields, preserve entered data, prevent repeated submission, and confirm receipt without promising an appointment.

## Direct contact

- Render telephone, WhatsApp, and email links only from verified values.
- Never render dummy numbers, empty links, or bare hash targets.
- Keep WhatsApp styling within the purple brand system.
- Hide unavailable channels.

## Diaspora customers

Invite a conversation without inventing remote services. Do not promise virtual inspections, remote payment, legal handling, or remote completion without verification.

## Service enquiries

For construction, ask for project type, proposed location, stage, approximate scope, and preferred contact method. Do not publish a detailed process until confirmed.

For property management, ask for property type, location, occupancy status, requested support, and contact method. Do not imply tenant management, rent collection, maintenance, inspections, or reporting unless included.

## Media states

- Use approved property media only.
- Reserve dimensions with aspect ratio.
- Without approved media, use a decorative brand surface that cannot be mistaken for a real property.
- Native video must have controls, playsinline, and no autoplay.
- Provide captions or a transcript for spoken media before production publishing.

## Mobile behaviour

- Prevent horizontal overflow from 320px.
- Keep inspection, WhatsApp, and call actions accessible without covering content.
- Use safe-area padding.
- Hide the mobile action bar while the footer or an overlay is active.
- Do not rely on hover for essential information.
- Keep tap targets at least 44px.
