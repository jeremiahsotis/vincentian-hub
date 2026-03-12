Vincentian Hub Developer Kickoff Checklist and Acceptance Criteria

Practical delivery companion for a developer or development team. Includes environment checklist, assumptions, test gates, and launch acceptance criteria.

1. Inputs the Developer Still Needs

Staging WordPress access with plugin installation rights

Google Sign-In credentials and approved redirect URIs

Approved domain list: svdpfortwayne.org and svdpsfw.org

Initial trusted directory CSV

List of existing conference pages and page IDs if not easily queryable from WordPress

Sample document files for testing preview/download behavior

Decision on whether Drive import is in MVP day-one or phase-two within MVP

2. Assumptions Already Locked

Users are 1:1 with Conference unless explicit district user.

District is never self-selectable in onboarding.

WordPress is the source of truth for targeted content and events.

Google calendar API sync is not required; calendar export and subscription are required.

WYSIWYG is required in MVP.

The portal adopts the basics of the current theme and should not require major bespoke design work.

3. Environment Checklist

4. Acceptance Criteria

Authentication and Access

Approved-domain users can self-select a conference but cannot self-select district.

Trusted-directory users auto-approve with assigned scope and conference.

Unknown users become pending and cannot access gated content.

Targeting

Resolver correctly enforces scope, audience, conference targeting mode, group flags, active state, and publish windows.

District-only content never leaks to conference users.

Selected-conference content never leaks to non-targeted conferences.

Dashboard Experience

Existing conference URLs keep working.

Dashboard uses current theme basics and renders cleanly on mobile and desktop.

Common sections are predictable, legible, and easy to tap.

Documents

Document detail, preview, and download are all permission-protected.

Users never need to browse Google Drive folder trees.

Plain-language titles and summaries are visible where configured.

Events

Event detail pages show date/time, location or virtual access, description, meeting packet, and add-to-calendar controls.

Private ICS feed returns only visible export-enabled events for the approved user tied to the token.

Single-event Add to Google Calendar and ICS download work.

Admin Operations

District announcements editor can publish announcements only.

District editor can manage dashboard items, announcements, documents, and events.

District admin can manage users, conferences, settings, and imports.

5. Recommended Additional Deliverables for the Developer

README with setup, local development, and deployment notes

Automated test coverage for resolver logic and route security

Seed or fixture data for at least one conference user, one district editor, one document, one announcement, and one event

Admin quick-start notes for content staff

Rollback notes for route changes and activation/deactivation behavior

6. Launch Gate

Contract tests passing

Permission tests passing

Manual mobile pass complete on at least one iPhone-size viewport and one desktop viewport

Sample content QA complete with district staff

No broken existing conference links

No unauthorized document or event exposure