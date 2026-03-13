# Research: Vincentian Hub Implementation Plan

## Decision: Use the binding Vincentian Hub docs as the real governance source

**Rationale**: The repo's `.specify/memory/constitution.md` is still the stock placeholder and does not define enforceable project rules. The binding Vincentian Hub docs and PR template are the actual non-negotiable governance surface for planning.

**Alternatives considered**:
- Treat the placeholder constitution as authoritative. Rejected because it contains no project-specific principles.
- Delay planning until a custom constitution exists. Rejected because the Vincentian Hub contract set already provides the necessary constraints.

## Decision: Treat the project as a WordPress plugin with WordPress core plus external Google integrations

**Rationale**: The repo shape, plugin entrypoint, `includes/` ownership map, and contract docs all describe a WordPress plugin. Google OAuth and Google Drive are supporting integrations, not the system of record.

**Alternatives considered**:
- Treat the project as a generic PHP web app. Rejected because it would obscure WordPress registration, routing, and capability boundaries.
- Treat Google Drive as a peer data system. Rejected because the contracts explicitly define WordPress as the system of record.

## Decision: Use foundation-first sequencing before any route or UI work

**Rationale**: CPT registration, taxonomy registration, meta registration, user-meta handling, roles/capabilities, and directory-table setup define the data and authorization surface that all later work depends on. The contracts and implementation roadmap both put this before resolver and user-facing features.

**Alternatives considered**:
- Start with dashboard templates and routes. Rejected because protected content and conference routing would drift without the resolver and canonical data registration.
- Start with Google auth first. Rejected because auth writes into user-meta and approval/account-scope contracts that need stable registration boundaries.

## Decision: Centralize all front-end visibility through the targeting resolver

**Rationale**: The binding targeting rules state that the resolver is the single authority for dashboards, detail pages, downloads, feeds, export endpoints, shortcode-rendered content, and AJAX responses. This must be established as an early implementation slice.

**Alternatives considered**:
- Let each content module perform its own visibility checks. Rejected because it duplicates logic and creates route-level security drift.
- Let templates hide unauthorized content. Rejected because client-side or presentation-layer filtering is explicitly insufficient.

## Decision: Keep route ownership in `includes/routes.php` while delivery logic remains in feature modules

**Rationale**: The architecture docs assign route declaration and entry-point security to `includes/routes.php`, while documents, events, and calendar modules own object-specific behavior. This split supports centralized security enforcement without collapsing all feature logic into one file.

**Alternatives considered**:
- Put each route registration directly inside its feature module. Rejected because it weakens centralized route ownership and server-side access consistency.
- Put all route and feature behavior into one routing file. Rejected because it would violate the architecture's module ownership boundaries.

## Decision: Use focused PR slices aligned to architectural owners

**Rationale**: The process docs require PR-first implementation, and the PR template only recognizes slices that map to defined architecture owners. The cleanest review units are foundation, resolver, auth/onboarding, conferences, dashboard, announcements, documents, events/calendar, and settings/admin polish.

**Alternatives considered**:
- Large horizontal slices spanning many unrelated modules. Rejected because they are hard to review and invite drift.
- Extremely granular one-file slices. Rejected because many behaviors depend on coordinated ownership boundaries across related modules.

## Decision: Treat current testing as a gap that must be closed slice-by-slice

**Rationale**: The repo currently has only `tests/README.md` and a PHPUnit dev dependency. The architecture docs still define mandatory coverage areas, so the plan must add testing with each slice rather than defer all tests to the end.

**Alternatives considered**:
- Defer tests until all implementation is complete. Rejected because it would leave route/security and resolver drift undetected for too long.
- Rely only on manual verification. Rejected because the contracts explicitly call out minimum required test ownership.

## Decision: Use the current theme baseline with minimal plugin-owned UI scaffolding

**Rationale**: The product spec and architecture docs constrain UI work to mobile-first layouts using the current theme baseline. The existing `assets/css/hub.css` already reflects this direction.

**Alternatives considered**:
- Introduce a standalone design system or redesign. Rejected because it conflicts with the current-theme and anti-drift constraints.
- Leave all UI decisions to templates without shared styling. Rejected because basic mobile-first consistency is still required.
