Vincentian Hub Build Backlog and Sprint Plan

Prioritized implementation plan for development. Organized to support Spec Kit, GitHub Issues, and practical sprint execution.

Sprint 0. Project Setup and Contract Freeze

Import the contract spec into the repo as an authoritative document.

Create issue templates for epic, story, bug, and contract-change request.

Set code conventions, local environment instructions, and deployment assumptions.

Confirm Google auth credentials, approved domains, and access to a staging WordPress environment.

Sprint 1. Foundation and Identity

Objectives

Create plugin bootstrap, CPT registration, taxonomy registration, and capability scaffolding.

Create trusted directory table and import utility.

Implement Google Sign-In flow, approved-domain logic, pending-user creation, and onboarding skeleton.

Definition of Done

Plugin installs cleanly.

Users can sign in and land in approved, pending, or onboarding states correctly.

Contract tests validate canonical keys and role map foundation.

Sprint 2. Conferences and Targeting Engine

Objectives

Implement conference CPT, linked-page mapping, and conference flag model.

Implement user-context resolver and shared targeting resolver.

Add automated tests for scope, audience, conference selection, group flags, district_only, and publish windows.

Definition of Done

Existing conference pages resolve to conference context.

Resolver is reusable across object types.

Core authorization logic is covered by tests.

Sprint 3. Dashboard Shell and Dashboard Items

Objectives

Implement conference page interception and district dashboard route.

Create fixed dashboard section rendering.

Implement dashboard item admin UI and conference shortcode auto-injection.

Definition of Done

Conference users see role-aware dashboard sections.

District users see district dashboard basics.

No schema drift from dashboard item keys.

Sprint 4. Announcements

Objectives

Implement announcement admin UI, scheduling, placement, and targeting.

Render banner, card, and What’s New placements in dashboards.

Add permission tests for announcement editors versus district editors/admins.

Definition of Done

District staff can publish targeted announcements.

Announcements follow schedule and targeting rules.

Announcement editor permissions are correctly bounded.

Sprint 5. Documents

Objectives

Implement document admin UI, categories, protected detail pages, preview, and download.

Implement Drive curation foundation and source-aware document delivery.

Support meeting-packet eligibility flags and related-content relationships.

Definition of Done

Documents open inside WordPress-controlled pages.

Permission checks cover page, preview, and download.

Document records can be attached to events later.

Sprint 6. Events and Calendar Export

Objectives

Implement event admin UI, detail pages, meeting packet attachments, and event targeting.

Generate Add to Google Calendar links, single-event ICS files, and private user ICS feeds.

Test export security and visible-event filtering.

Definition of Done

Targeted events render correctly.

Users can subscribe to their events through private feed URLs.

Event detail pages surface packet materials cleanly.

Sprint 7. Search, Mobile Polish, and Accessibility

Objectives

Implement visible-content search across dashboard items, announcements, documents, and events.

Perform mobile-first layout pass using the current theme as the visual base.

Run usability pass oriented to older volunteers and district staff.

Definition of Done

Search returns only visible content.

Screens are comfortable on mobile and desktop.

Tap targets, typography, and spacing are appropriate for aging users.

Sprint 8. Stabilization and Launch Readiness

Objectives

Complete bug triage, caching pass, and logging checks.

Run content population rehearsal with real sample documents, announcements, and events.

Prepare launch checklist, rollback notes, and admin training materials.

Definition of Done

Staging passes acceptance criteria.

Content team can operate the portal.

Launch packet is ready.

Cross-Cutting Backlog Items