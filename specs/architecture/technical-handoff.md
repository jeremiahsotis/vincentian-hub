Vincentian Hub Technical Handoff

Developer handoff for Codex, Spec Kit, and GitHub Issues. This document translates the contracts into implementation guidance and deliverable structure.

1. Product Summary

Build a gated WordPress resource portal that preserves existing conference page URLs, authenticates users with Google Sign-In, personalizes access by approved conference or district role, surfaces curated tools and documents, and supports targeted announcements and events with meeting packet attachments and calendar export.

2. Delivery Targets

Preserve all existing /district-resources/<conference-name> URLs and render a dynamic dashboard in place.

Implement Google-based sign-in with trusted-directory and approved-domain approval logic.

Create first-class content objects for dashboard items, announcements, documents, and events.

Implement a single shared targeting resolver used everywhere.

Deliver WordPress-controlled document preview/download and event detail pages.

Deliver personal calendar outputs through private ICS feed URLs and single-event add/download.

3. Build Principles

WordPress is the source of truth for portal content and events.

Google Drive is an upstream document source, not the user interface.

Backend authorization uses capabilities. Front-end visibility uses user context plus targeting metadata.

The current WordPress theme supplies the visual baseline. Portal work should focus on layout, hierarchy, spacing, readability, and mobile-first behavior.

4. Required Modules

5. Admin Workflows

User Approval

Pending user appears in Users & Approvals.

District admin reviews identity, assigns scope, conference if applicable, and role profiles.

District is never self-selectable.

Announcement Publishing

District-authorized editor creates a post, targets audience and conference set, sets schedule, and publishes.

Placement options determine banner, card, or What’s New output.

Document Curation

Document record is created or imported, categorized, targeted, and published.

Preview type and plain-language summary are set before user-facing publication.

Event Publishing

District editor creates event, sets date/time/location, targets audience and conferences, attaches meeting packet docs, enables calendar export, and publishes.

6. GitHub Issue Breakdown

7. Acceptance Standard

No schema drift from contract-locked keys.

No unauthorized content exposure through pages, downloads, or feeds.

Current theme remains visually recognizable.

Mobile-first rendering is clear, low-friction, and usable with large tap targets and strong typography.

Event and document detail pages behave predictably and do not dump users into external file-system views.