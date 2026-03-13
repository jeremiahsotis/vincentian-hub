# Implementation Plan: Vincentian Hub Implementation Roadmap

**Branch**: `001-vincentian-hub-plan` | **Date**: 2026-03-13 | **Spec**: [spec.md](/Users/jeremiahotis/projects/vincentian-hub/specs/001-vincentian-hub-plan/spec.md)
**Input**: Feature specification from `/specs/001-vincentian-hub-plan/spec.md`

## Summary

Implement Vincentian Hub in contract-safe vertical slices that establish the plugin foundation first, centralize normalized user context and front-end visibility in the targeting resolver second, and only then add route-dependent dashboard, document, event, and calendar behavior. Each slice must stop at a clean PR boundary and validate against the binding contract, architecture, targeting, data-model, and workflow docs before merge.

## Technical Context

**Language/Version**: PHP for a WordPress plugin; planning baseline assumes compatibility with the current scaffold and local PHPUnit 10 toolchain  
**Primary Dependencies**: WordPress core APIs, PHPUnit 10 for automated tests, Google OAuth integration, Google Drive import integration  
**Storage**: WordPress posts, post meta, taxonomy terms, user meta, options, and the runtime custom table `{$wpdb->prefix}svdp_directory`  
**Testing**: PHPUnit-backed unit/integration coverage plus WordPress manual validation for auth, resolver, route security, and mobile-first rendering  
**Target Platform**: Self-hosted WordPress site using the current theme baseline and mobile-first portal templates  
**Project Type**: WordPress plugin / gated web application module  
**Performance Goals**: Maintain normal WordPress request performance while enforcing server-side permission checks on all protected queries, detail routes, downloads, feeds, and exports  
**Constraints**: No contract drift, no alternate schema names, no duplicated resolver logic, no route pattern changes, no client-side-only access control, WordPress remains the system of record  
**Scale/Scope**: 5 CPTs, 1 taxonomy, 1 custom table, 5 WordPress roles, 9 front-end visibility profiles, 6 protected route patterns, 22 canonical `includes/` modules, 7 portal templates

## Constitution Check

*GATE: Must pass before Phase 0 research. Re-check after Phase 1 design.*

`.specify/memory/constitution.md` now defines the Vincentian Hub governance baseline and is enforced together with the Vincentian Hub binding documents and PR workflow artifacts.

Pre-research gate result:

- PASS: The constitution defines contract authority, WordPress-as-system-of-record, authorization boundaries, resolver authority, canonical schema/route stability, server-side security, and module ownership rules.
- PASS: Plan preserves the contract-locked plugin identity, CPTs, taxonomy, custom table naming, meta keys, route patterns, and enums.
- PASS: Plan keeps WordPress roles/capabilities for backend authorization separate from `svdp_role_profiles` for front-end visibility.
- PASS: Plan keeps the targeting resolver as the single front-end visibility authority and forbids alternate resolver implementations.
- PASS: Plan follows the PR-first slice model required by `specs/issues/SPEC.md` and `.github/pull_request_template.md`.

Post-design gate result:

- PASS: `spec.md`, `research.md`, `data-model.md`, `quickstart.md`, and `contracts/` align with the active Vincentian Hub constitution and binding architecture set.
- PASS: `research.md`, `data-model.md`, `quickstart.md`, and `contracts/` all reuse canonical Vincentian Hub naming and ownership boundaries.
- PASS: Route contracts remain centralized under `includes/routes.php` with server-side security enforcement before protected content delivery.
- PASS: The phased roadmap places foundation and resolver slices before any user-facing rendering or protected delivery slices.

## Project Structure

### Documentation (this feature)

```text
specs/001-vincentian-hub-plan/
├── plan.md
├── research.md
├── data-model.md
├── quickstart.md
├── contracts/
│   ├── authorization-boundaries.md
│   └── front-end-routes.md
└── spec.md
```

### Source Code (repository root)

```text
vincentian-hub.php
includes/
├── bootstrap.php
├── roles.php
├── capabilities.php
├── post-types.php
├── taxonomies.php
├── meta-registration.php
├── user-meta.php
├── directory-table.php
├── auth-google.php
├── onboarding.php
├── conferences.php
├── targeting-resolver.php
├── dashboard-query.php
├── dashboard-renderer.php
├── announcements.php
├── documents.php
├── events.php
├── calendar-ics.php
├── routes.php
├── permissions.php
├── shortcode-context.php
├── settings.php
├── admin-menu.php
└── drive-imports.php
templates/
assets/
tests/
specs/
```

**Structure Decision**: Use the existing WordPress-plugin layout already present in the repository. Keep `includes/` as the application logic layer, `templates/` as presentation only, `assets/` as minimal theme-aligned styling/scripts, and `tests/` as the home for resolver, capability, route-security, and export coverage.

## Phased Roadmap

### Phase 1: Foundation Registration and Bootstrap

Scope:

- plugin bootstrap and activation/deactivation ownership
- roles and capabilities
- CPT and taxonomy registration
- canonical object-meta registration
- user-meta registration and normalization helpers
- trusted directory table creation/access helpers
- settings and admin-menu scaffolding needed for the foundation

Why first:

- all later slices depend on stable registration, naming, and authorization boundaries
- the architecture requires activation/bootstrap ownership to stay centralized

Primary modules:

- `vincentian-hub.php`
- `includes/bootstrap.php`
- `includes/roles.php`
- `includes/capabilities.php`
- `includes/post-types.php`
- `includes/taxonomies.php`
- `includes/meta-registration.php`
- `includes/user-meta.php`
- `includes/directory-table.php`
- `includes/settings.php`
- `includes/admin-menu.php`

Risks:

- meta registration drifting into feature modules
- role/capability semantics drifting from the authoritative matrix
- hardcoding `wp_svdp_directory`
- activation/bootstrap responsibilities scattering across modules

Validation points:

- canonical names and enums match `contracts-spec.md`
- capability-to-role map matches the MVP matrix
- user meta keys and table naming match `wordpress-data-model-map.md`
- foundation files only; no user-facing route logic introduced

Checkpoint:

- foundation registration loads cleanly and is ready for a standalone PR

### Phase 2: Targeting Resolver and Permission Core

Scope:

- normalized user context creation
- conference-flag derivation inputs
- canonical resolver order and allow/deny logic
- shared permission helpers
- baseline resolver and capability-boundary tests

Why second:

- all protected front-end behavior depends on a single resolver implementation

Primary modules:

- `includes/targeting-resolver.php`
- `includes/permissions.php`
- `includes/conferences.php` for flag derivation support
- `tests/`

Risks:

- duplicated resolver logic in later feature modules
- direct conference-meta queries during visibility evaluation
- confusing admin capabilities with front-end targeting profiles

Validation points:

- resolver order matches the canonical seven-step sequence
- normalized user context uses the locked schema
- empty `svdp_audience_profiles` behavior is preserved correctly
- tests cover scope, audience, conference mode, and group flags

Checkpoint:

- resolver and permission core merged before any protected routes are added

### Phase 3: Authentication and Onboarding

Scope:

- Google OAuth flow
- approval status handling
- account scope handling
- onboarding completion and conference-selection behavior
- pending-access/login/onboarding template flow

Why now:

- route-protected UI needs a reliable authenticated user state and onboarding path

Primary modules:

- `includes/auth-google.php`
- `includes/onboarding.php`
- `includes/user-meta.php`
- `templates/login.php`
- `templates/onboarding.php`
- `templates/pending-access.php`

Risks:

- writing inconsistent user-meta state
- leaking portal access to `pending` or `disabled` users
- pulling runtime authority from the trusted directory after account creation

Validation points:

- approval and account-scope enums match the contract
- conference-scope users require exactly one assigned conference
- district users do not inherit conference targeting from directory-table data

Checkpoint:

- authenticated users reach the correct gated state before dashboard or detail pages exist

### Phase 4: Conferences and Canonical Context

Scope:

- conference model helpers
- `svdp_conf_page_slug` route resolution support
- linked-page mapping
- conference flag derivation helpers
- shortcode conference-context injection

Why now:

- conference routing and many dashboard behaviors depend on canonical conference context

Primary modules:

- `includes/conferences.php`
- `includes/shortcode-context.php`
- supporting parts of `includes/permissions.php`

Risks:

- deriving conference routes from post slug instead of `svdp_conf_page_slug`
- deriving shortcode context from request parameters instead of canonical conference context
- letting inactive conferences contribute targeting flags

Validation points:

- route token source is `svdp_conf_page_slug`
- conference flags normalize to `urban`, `rural`, `new_haven`, `allen_county`
- shortcode context does not become an alternate routing or authorization layer

Checkpoint:

- conference lookup/context behavior is stable before route-heavy dashboard work

### Phase 5: Dashboard Shell and Routing

Scope:

- front-end route registration
- conference and district dashboard route handling
- dashboard dataset queries
- dashboard rendering orchestration
- conference and district dashboard templates

Why now:

- resolver, auth, and conference context are now available for protected dashboard delivery

Primary modules:

- `includes/routes.php`
- `includes/dashboard-query.php`
- `includes/dashboard-renderer.php`
- `templates/dashboard-conference.php`
- `templates/dashboard-district.php`
- `assets/css/hub.css`
- `assets/js/hub.js`

Risks:

- templates becoming query or resolver layers
- dashboard query and rendering responsibilities collapsing together
- route handlers skipping server-side resolver checks

Validation points:

- route ownership remains in `includes/routes.php`
- query/render separation matches the architecture
- templates only render provided data
- mobile-first current-theme baseline is preserved

Checkpoint:

- protected dashboard routes work with centralized route/security entry points

### Phase 6: Announcements

Scope:

- announcement object behavior
- announcement queries/render helpers
- use of the shared targeting block in dashboard and announcement contexts

Why separate:

- announcements are a distinct content system and a clean review unit after the dashboard shell exists

Primary modules:

- `includes/announcements.php`
- related dashboard-query/render touch points

Risks:

- introducing announcement-specific visibility logic outside the resolver
- blending announcement authoring permissions with front-end targeting profiles

Validation points:

- announcement enums and meta match the contract
- admin CRUD still uses capabilities
- front-end visibility still uses resolver only

Checkpoint:

- announcement behavior is reviewable without document/file-delivery complexity

### Phase 7: Documents

Scope:

- document detail route behavior
- preview and download delivery
- document-specific rendering helpers
- taxonomy integration
- Drive import intake boundaries needed for document records

Why separate:

- documents are a high-risk protected-delivery boundary with distinct security requirements

Primary modules:

- `includes/documents.php`
- `includes/routes.php`
- `includes/drive-imports.php`
- `templates/document-detail.php`

Risks:

- detail, preview, or download endpoints bypassing route-level security
- document module implementing alternate access logic
- Drive import data overriding WordPress system-of-record behavior

Validation points:

- preview/download/detail each enforce server-side resolver checks
- `svdp_doc_cat` remains the only document taxonomy
- Drive imports remain upstream-source behavior only

Checkpoint:

- protected document delivery is complete and independently reviewable

### Phase 8: Events and Calendar Export

Scope:

- event detail routes
- event queries and related-object behavior
- calendar feed endpoint
- single-event ICS download endpoint
- calendar token usage and export controls

Why separate:

- events/calendar add more protected routes plus export behavior and deserve their own security review boundary

Primary modules:

- `includes/events.php`
- `includes/calendar-ics.php`
- `includes/routes.php`
- `templates/event-detail.php`

Risks:

- feed or ICS endpoints exposing unauthorized event data
- event/export permissions diverging from the resolver
- token handling drifting away from the normalized user context contract

Validation points:

- `/portal-calendar/feed/<token>/` and `/portal-calendar/event/<event_id>/download/` remain canonical
- feed/export checks occur server-side before data is returned
- event enums and relationships match the data-model map

Checkpoint:

- event detail and calendar export behavior land in one focused PR

### Phase 9: Settings, Branding, and Admin Polish

Scope:

- branding/logo setting
- remaining admin-menu behavior
- settings UI hardening
- mobile-first polish that stays within the current theme baseline

Why last:

- it depends on the foundation settings layer but does not block core protected-content behavior

Primary modules:

- `includes/settings.php`
- `includes/admin-menu.php`
- `assets/css/hub.css`

Risks:

- introducing design drift beyond the current theme baseline
- admin screens bypassing capability boundaries

Validation points:

- logo setting key remains `vincentian_hub_logo_attachment_id`
- branding upload remains district-admin only
- UI polish does not become a redesign slice

Checkpoint:

- settings/admin polish is merged as a finishing slice after core behavior exists

## Dependency-Aware Module Order

1. `vincentian-hub.php` and `includes/bootstrap.php`
2. `includes/roles.php` and `includes/capabilities.php`
3. `includes/post-types.php` and `includes/taxonomies.php`
4. `includes/meta-registration.php`, `includes/user-meta.php`, and `includes/directory-table.php`
5. `includes/settings.php` and `includes/admin-menu.php`
6. `includes/conferences.php`, `includes/targeting-resolver.php`, and `includes/permissions.php`
7. `includes/auth-google.php` and `includes/onboarding.php`
8. `includes/shortcode-context.php`
9. `includes/routes.php`
10. `includes/dashboard-query.php` and `includes/dashboard-renderer.php`
11. `includes/announcements.php`
12. `includes/documents.php` and `includes/drive-imports.php`
13. `includes/events.php` and `includes/calendar-ics.php`
14. `templates/` and `assets/` refinements within the owning slice
15. `tests/` additions throughout, not deferred to the end

Must come before route work:

- conference lookup helpers
- normalized user context
- resolver and permission helpers
- auth/onboarding state handling

Must come before dashboard rendering:

- route ownership
- dashboard query layer
- resolver-backed visible dataset assembly

Must come before document delivery:

- routes
- resolver
- permissions
- document CPT/meta registration

Must come before event/calendar work:

- event CPT/meta registration
- routes
- resolver
- permissions
- calendar token user-meta handling

## Recommended PR Slices

1. Foundation
2. Targeting resolver
3. Auth / onboarding
4. Conferences / canonical context
5. Dashboard shell and routes
6. Announcements
7. Documents
8. Events / calendar export
9. Settings / admin polish

Each slice is coherent because it maps to a documented architecture owner or tightly-coupled ownership boundary and can be reviewed against the PR template without mixing unrelated systems.

## Branch Naming Convention

- Planning branch for this work: `001-vincentian-hub-plan`
- Implementation branches should continue numeric prefixes and concise slice names, for example:
  - `002-foundation`
  - `003-targeting-resolver`
  - `004-auth-onboarding`
  - `005-conferences-context`
  - `006-dashboard-routes`
  - `007-announcements`
  - `008-documents`
  - `009-events-calendar`
  - `010-settings-admin-polish`

Rules:

- one slice per branch
- no long-running branch that spans multiple slices
- merge before starting the next numbered branch

## Commit Checkpoints

Each slice should have at least these checkpoints before PR:

1. Registration or ownership boundary established without contract drift
2. Core behavior for the slice implemented behind the correct module owner
3. Tests or manual validation for the slice completed
4. PR template review completed
5. Slice scoped cleanly with no unrelated modules pulled in

Recommended commit rhythm inside a slice:

1. bootstrap/registration groundwork
2. core behavior
3. tests and validation adjustments
4. PR cleanup commit if needed

The branch should stop once the slice is PR-ready; the next slice starts only after merge.

## Complexity Tracking

No constitution violations require justification in this planning slice. The primary governance gap is that `.specify/memory/constitution.md` is still a placeholder, but the Vincentian Hub contract and workflow documents already define the active constraints used here.
