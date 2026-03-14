# Tasks: Vincentian Hub Implementation

**Input**: Design documents from `/specs/001-vincentian-hub-plan/`
**Prerequisites**: [plan.md](/Users/jeremiahotis/projects/vincentian-hub/specs/001-vincentian-hub-plan/plan.md), [spec.md](/Users/jeremiahotis/projects/vincentian-hub/specs/001-vincentian-hub-plan/spec.md), [research.md](/Users/jeremiahotis/projects/vincentian-hub/specs/001-vincentian-hub-plan/research.md), [data-model.md](/Users/jeremiahotis/projects/vincentian-hub/specs/001-vincentian-hub-plan/data-model.md), [contracts/front-end-routes.md](/Users/jeremiahotis/projects/vincentian-hub/specs/001-vincentian-hub-plan/contracts/front-end-routes.md), [contracts/authorization-boundaries.md](/Users/jeremiahotis/projects/vincentian-hub/specs/001-vincentian-hub-plan/contracts/authorization-boundaries.md)

**Tests**: Include PHPUnit-backed tasks because the approved plan, architecture docs, and `tests/README.md` require resolver, capability, route-security, and export coverage.

**Organization**: Tasks are grouped by implementation slice / PR boundary and mapped to implementation user stories from `spec.md`. Each user story is an independently testable increment of plugin behavior.

## Format: `[ID] [P?] [Story] Description`

- **[P]**: Can run in parallel when dependencies are already complete and file ownership does not overlap
- **[Story]**: Execution slice label for independently reviewable implementation increments
- Every task includes exact file paths and the PR slice it belongs to

## Phase 1: Setup (Shared Infrastructure)

**Purpose**: Establish execution scaffolding so each PR slice can add tests and validation without inventing structure mid-stream.

- [X] T001 Create WordPress/PHPUnit test scaffolding in `/tests/README.md`, `/tests/bootstrap.php`, `/tests/unit/.gitkeep`, `/tests/integration/.gitkeep`, and `/tests/contract/.gitkeep`
  Description: Establish the test directory layout referenced by the approved plan so later slices can add resolver, capability, route-security, and export coverage in predictable locations.
  Files/Modules Affected: `/tests/README.md`, `/tests/bootstrap.php`, `/tests/unit/.gitkeep`, `/tests/integration/.gitkeep`, `/tests/contract/.gitkeep`
  Dependencies: None
  Acceptance Criteria: Test directories exist; `tests/README.md` reflects the minimum required coverage areas from the architecture; later slice tasks can add PHPUnit tests without changing structure.
  PR Slice Grouping: Setup
  Contract/Risk Checks: Validate against `/specs/contracts-spec.md`, `/specs/architecture/targeting-engine-rules.md`, and `/specs/architecture/wordpress-data-model-map.md`; avoid creating a parallel test taxonomy unrelated to resolver, route, or capability coverage.

**Checkpoint**: Setup is complete when PHPUnit/bootstrap scaffolding exists and all later slices can add tests without redefining the test structure.

---

## Phase 2: Foundational (Blocking Prerequisites)

**Purpose**: Complete the contract-locked foundation slice before any protected route or front-end feature work begins.

**⚠️ CRITICAL**: No user-story slice may begin until this phase is complete.

- [X] T002 Implement canonical plugin bootstrap and activation/deactivation ownership in `/vincentian-hub.php` and `/includes/bootstrap.php`
  Description: Wire the main plugin entrypoint and module loader so bootstrap sequencing, activation hooks, and deactivation hooks remain centralized in the canonical entrypoint/bootstrap layer.
  Files/Modules Affected: `/vincentian-hub.php`, `/includes/bootstrap.php`
  Dependencies: T001
  Acceptance Criteria: `vincentian-hub.php` remains the plugin entrypoint; plugin name, slug, code prefix, schema/meta prefix, namespace, main file, and text domain remain aligned to the canonical plugin identity contract; bootstrap includes the canonical modules in plan order; activation/deactivation ownership is not scattered into feature files.
  PR Slice Grouping: Foundation
  Contract/Risk Checks: Contract check: canonical plugin identity and entrypoint only. Security check: no protected feature behavior is introduced in bootstrap.

- [X] T003 [P] Implement the canonical WordPress role and capability matrix in `/includes/roles.php`, `/includes/capabilities.php`, and `/tests/unit/CapabilityMatrixTest.php`
  Description: Register the five WordPress roles, attach the MVP capability matrix, and add tests that lock the capability-to-role boundaries before any admin CRUD UI is built.
  Files/Modules Affected: `/includes/roles.php`, `/includes/capabilities.php`, `/tests/unit/test-capability-matrix.php`
  Dependencies: T002
  Acceptance Criteria: Roles and capabilities match the authoritative matrix; `svdp_district_staff` has no unintended publishing rights; tests assert the role boundaries expected by the contracts spec.
  PR Slice Grouping: Foundation
  Contract/Risk Checks: Contract check: roles/capabilities match `/specs/contracts-spec.md`. Security check: WordPress roles are not treated as `svdp_role_profiles`.

- [X] T004 [P] Register canonical CPTs and taxonomy in `/includes/post-types.php`, `/includes/taxonomies.php`, and `/tests/unit/ContentRegistrationTest.php`
  Description: Register `svdp_conf`, `svdp_dash_item`, `svdp_announcement`, `svdp_doc`, `svdp_event`, and `svdp_doc_cat`, then lock the registration names with tests.
  Files/Modules Affected: `/includes/post-types.php`, `/includes/taxonomies.php`, `/tests/unit/ContentRegistrationTest.php`
  Dependencies: T002
  Acceptance Criteria: All canonical CPT and taxonomy names are registered exactly once; tests fail if alternate names or missing registrations appear.
  PR Slice Grouping: Foundation
  Contract/Risk Checks: Contract check: canonical CPT/taxonomy names only. Data-model check: no extra MVP content types or taxonomies.

- [X] T005 Implement centralized object-meta and user-meta registration in `/includes/meta-registration.php`, `/includes/user-meta.php`, and `/tests/unit/MetaRegistrationTest.php`
  Description: Register the shared targeting block, contract-locked object meta, and required user meta from one canonical place before feature modules start reading or writing them.
  Files/Modules Affected: `/includes/meta-registration.php`, `/includes/user-meta.php`, `/tests/unit/MetaRegistrationTest.php`
  Dependencies: T003, T004
  Acceptance Criteria: Shared targeting keys and required user meta keys are centrally registered; prohibited alias keys are absent; tests cover key presence and storage-shape expectations.
  PR Slice Grouping: Foundation
  Contract/Risk Checks: Contract check: canonical keys only. Data-model check: no meta drift into feature modules.

- [X] T006 Implement the trusted directory table and foundation bootstrap validation in `/includes/directory-table.php` and `/tests/integration/FoundationBootstrapTest.php`
  Description: Create the runtime table helpers using `{$wpdb->prefix}svdp_directory` and verify the plugin boots with the canonical foundation registration modules wired from the entrypoint/bootstrap layer.
  Files/Modules Affected: `/includes/directory-table.php`, `/tests/integration/FoundationBootstrapTest.php`
  Dependencies: T003, T005
  Acceptance Criteria: Directory table helpers use runtime prefix semantics; integration test confirms foundation bootstrap loads cleanly and includes the canonical foundation registration modules.
  PR Slice Grouping: Foundation
  Contract/Risk Checks: Contract check: runtime table naming uses `{$wpdb->prefix}svdp_directory`. Security check: no route or resolver behavior is introduced here.

**Checkpoint**: Foundation slice is complete enough for PR when bootstrap, registrations, capabilities, user-meta, table helpers, and baseline tests all pass without adding protected front-end behavior.

---

## Phase 3: User Story 1 - Protected Dashboard MVP (Priority: P1) 🎯 MVP

**Goal**: Deliver the first independently testable protected portal experience by implementing the targeting resolver, auth/onboarding flow, canonical conference context, route ownership, and dashboard rendering.

**Independent Test**: An authenticated approved user reaches the correct conference or district dashboard route, sees only resolver-eligible content, and denied users are held at login/onboarding/pending-access states.

### PR Slice: Targeting Resolver

- [X] T007 [P] [US1] Add failing resolver tests in `/tests/unit/TargetingResolverTest.php` and `/tests/integration/NormalizedUserContextTest.php`
  Description: Lock the seven-step resolver order, normalized user context shape, empty-audience behavior, scope handling, and group-flag targeting before implementing resolver code.
  Files/Modules Affected: `/tests/unit/TargetingResolverTest.php`, `/tests/integration/NormalizedUserContextTest.php`
  Dependencies: T005, T006
  Acceptance Criteria: Tests cover user existence, approval status, active state, publish window, scope match, audience intersection, and conference/district targeting match; tests initially fail because resolver implementation does not exist yet.
  PR Slice Grouping: Targeting resolver
  Contract/Risk Checks: Contract check: resolver order matches `/specs/architecture/targeting-engine-rules.md`. Security check: no partial or alternate resolver implementation is introduced.

- [X] T008 [US1] Implement normalized user context and resolver authority in `/includes/targeting-resolver.php`
  Description: Build the canonical context schema and allow/deny resolver helpers as the only front-end visibility authority.
  Files/Modules Affected: `/includes/targeting-resolver.php`
  Dependencies: T007
  Acceptance Criteria: Resolver exposes reusable helpers based on the locked context schema; front-end targeting decisions use normalized user `role_profiles` derived from `svdp_role_profiles` rather than WordPress role names; context contains `conference_flags` rather than querying conference meta during evaluation; resolver behavior satisfies the failing tests from T007.
  PR Slice Grouping: Targeting resolver
  Contract/Risk Checks: Contract check: canonical normalized context schema only. Security check: no alternate front-end visibility checks are added elsewhere.

- [X] T009 [US1] Implement shared permission helpers in `/includes/permissions.php` and conference-flag support in `/includes/conferences.php`
  Description: Add the non-resolver permission helpers and conference-flag derivation support needed by routes and protected delivery entry points.
  Files/Modules Affected: `/includes/permissions.php`, `/includes/conferences.php`
  Dependencies: T008
  Acceptance Criteria: Permission helpers consume normalized user context rather than raw request data; conference-flag derivation respects active conferences only; resolver tests and context tests pass.
  PR Slice Grouping: Targeting resolver
  Contract/Risk Checks: Data-model check: conference flags derive from active `svdp_conf` data only. Security check: permissions helpers do not become a second resolver.

**Checkpoint**: Targeting resolver slice ends when normalized context and resolver behavior are test-covered and ready for a standalone PR before any protected routes are exposed.

### PR Slice: Auth / Onboarding

- [X] T010 [P] [US1] Add failing auth/onboarding flow tests in `/tests/integration/AuthGoogleFlowTest.php` and `/tests/integration/OnboardingAccessStatesTest.php`
  Description: Define the expected behavior for approved, pending, disabled, conference-scope, and district-scope users before implementing Google auth and onboarding transitions.
  Files/Modules Affected: `/tests/integration/AuthGoogleFlowTest.php`, `/tests/integration/OnboardingAccessStatesTest.php`
  Dependencies: T009
  Acceptance Criteria: Tests cover login, onboarding, pending-access, and denial flows using canonical approval/account-scope enums; tests fail until auth/onboarding is implemented.
  PR Slice Grouping: Auth / onboarding
  Contract/Risk Checks: Contract check: approval/account-scope enums match `/specs/contracts-spec.md`. Security check: pending and disabled users are denied protected portal access.

- [X] T011 [US1] Implement Google auth and onboarding state handling in `/includes/auth-google.php`, `/includes/onboarding.php`, `/templates/login.php`, `/templates/onboarding.php`, and `/templates/pending-access.php`
  Description: Add the Google OAuth entry flow, post-auth onboarding state transitions, and the three gated templates needed before dashboard access exists.
  Files/Modules Affected: `/includes/auth-google.php`, `/includes/onboarding.php`, `/templates/login.php`, `/templates/onboarding.php`, `/templates/pending-access.php`, `/includes/user-meta.php`
  Dependencies: T010
  Acceptance Criteria: Approved users can complete auth and reach the next valid state; pending users are blocked from protected routes and shown pending access; disabled users are blocked from protected routes; conference-scope users end onboarding with exactly one `svdp_conference_id`; district-scope users do not derive targeting behavior from trusted-directory `conference_id`.
  PR Slice Grouping: Auth / onboarding
  Contract/Risk Checks: Contract check: approval and scope behavior remain canonical. Data-model check: trusted directory stays bootstrap-only after account creation.

**Checkpoint**: Auth/onboarding slice ends when users can authenticate into the correct gated state and tests prove that approval and scope rules are enforced.

### PR Slice: Conferences / Canonical Context

- [X] T012 [P] [US1] Add failing conference context tests in `/tests/unit/ConferenceContextTest.php` and `/tests/integration/ShortcodeContextTest.php`
  Description: Lock conference lookup, page-slug resolution, active-flag derivation, and shortcode context behavior before implementing conference helpers.
  Files/Modules Affected: `/tests/unit/ConferenceContextTest.php`, `/tests/integration/ShortcodeContextTest.php`
  Dependencies: T011
  Acceptance Criteria: Tests assert `svdp_conf_page_slug` route resolution, normalized group-flag derivation, linked-page mapping, and shortcode-context injection from canonical conference context; tests fail until implementation exists.
  PR Slice Grouping: Conferences / canonical context
  Contract/Risk Checks: Contract check: conference route token is `svdp_conf_page_slug`. Security check: no request-param-based conference targeting.

- [X] T013 [US1] Implement conference helpers and shortcode context in `/includes/conferences.php` and `/includes/shortcode-context.php`
  Description: Build the conference model, linked-page mapping, route-token lookup, and shortcode context injection needed by the dashboard and future content routes.
  Files/Modules Affected: `/includes/conferences.php`, `/includes/shortcode-context.php`
  Dependencies: T012
  Acceptance Criteria: Conference lookup resolves by `svdp_conf_page_slug`; active conferences alone contribute `conference_flags`; shortcode context derives from canonical conference context, not raw request parameters.
  PR Slice Grouping: Conferences / canonical context
  Contract/Risk Checks: Data-model check: conference flags normalize from active `svdp_conf` meta only. Security check: shortcode helpers do not become an authorization layer.

**Checkpoint**: Conferences slice ends when conference routing/context helpers are stable enough for route registration without inventing alternate lookup logic.

### PR Slice: Dashboard Shell and Routes

- [X] T014 [P] [US1] Add failing route-security and dashboard query/render tests in `/tests/integration/DashboardRoutesTest.php`, `/tests/integration/DashboardSecurityTest.php`, and `/tests/unit/DashboardQueryTest.php`
  Description: Define the expected behavior for district and conference dashboard routes, route-level resolver checks, and query/render separation before implementing the dashboard shell.
  Files/Modules Affected: `/tests/integration/DashboardRoutesTest.php`, `/tests/integration/DashboardSecurityTest.php`, `/tests/unit/DashboardQueryTest.php`
  Dependencies: T013
  Acceptance Criteria: Tests assert canonical route patterns, route-level permission enforcement, visible dataset assembly through the resolver, and template-only rendering behavior; tests fail until the slice is implemented.
  PR Slice Grouping: Dashboard shell and routes
  Contract/Risk Checks: Contract check: canonical dashboard routes only. Security check: templates do not become auth/query layers.

- [X] T015 [US1] Implement route registration and protected dashboard entry points in `/includes/routes.php`
  Description: Register the conference and district dashboard routes from the canonical routing layer and enforce server-side permission checks before handing off to dashboard query/render code.
  Files/Modules Affected: `/includes/routes.php`
  Dependencies: T014
  Acceptance Criteria: `/district-resources/<conference-name>/` resolves only by `svdp_conf_page_slug`; `/district-resources/district/` is denied to non-eligible users server-side; route handlers build normalized user context before content lookup; route handlers invoke the resolver before any protected dashboard data is returned.
  PR Slice Grouping: Dashboard shell and routes
  Contract/Risk Checks: Contract check: route ownership remains in `/includes/routes.php`. Security check: protected front-end routes are not registered from feature modules.

- [X] T016 [US1] Implement dashboard dataset assembly and rendering in `/includes/dashboard-query.php`, `/includes/dashboard-renderer.php`, `/templates/dashboard-conference.php`, `/templates/dashboard-district.php`, `/assets/css/hub.css`, and `/assets/js/hub.js`
  Description: Build the visible dashboard dataset layer, renderer orchestration, and minimal theme-aligned templates/assets for conference and district dashboards.
  Files/Modules Affected: `/includes/dashboard-query.php`, `/includes/dashboard-renderer.php`, `/templates/dashboard-conference.php`, `/templates/dashboard-district.php`, `/assets/css/hub.css`, `/assets/js/hub.js`
  Dependencies: T015
  Acceptance Criteria: `dashboard-query.php` assembles visible datasets and does not render markup; `dashboard-renderer.php` renders supplied datasets and does not rebuild targeting logic; templates only render already-authorized data; conference and district dashboard tests pass for approved allowed users and denied users.
  PR Slice Grouping: Dashboard shell and routes
  Contract/Risk Checks: Contract check: current theme baseline and mobile-first structure remain intact. Security check: no resolver logic is rebuilt in query, renderer, or templates.

**Checkpoint**: User Story 1 is complete when approved users can reach the correct protected dashboard, denied users cannot, and the first route/security tests pass. This is the recommended MVP merge point.

---

## Phase 4: User Story 2 - Protected Announcements and Documents (Priority: P2)

**Goal**: Add independently reviewable protected content slices for announcements and documents without breaking the dashboard MVP.

**Independent Test**: Announcements appear only for eligible users, and document detail/preview/download endpoints enforce server-side access checks through the central resolver.

### PR Slice: Announcements

- [ ] T017 [P] [US2] Add failing announcement tests in `/tests/unit/test-announcements.php` and `/tests/integration/test-announcement-visibility.php`
  Description: Lock the shared targeting behavior, placement enums, and announcement visibility expectations before implementing announcement helpers.
  Files/Modules Affected: `/tests/unit/test-announcements.php`, `/tests/integration/test-announcement-visibility.php`
  Dependencies: T016
  Acceptance Criteria: Tests cover contract-locked announcement enums, resolver-based announcement visibility, and announcements-only capability boundaries; tests fail before implementation.
  PR Slice Grouping: Announcements
  Contract/Risk Checks: Contract check: announcement enums and targeting keys remain canonical. Security check: no announcement-specific visibility model outside the resolver.

- [ ] T018 [US2] Implement announcement behavior in `/includes/announcements.php`, `/includes/dashboard-query.php`, and `/includes/dashboard-renderer.php`
  Description: Add announcement queries/render helpers that reuse the shared targeting block and integrate them into the existing dashboard pipeline without collapsing query/render separation.
  Files/Modules Affected: `/includes/announcements.php`, `/includes/dashboard-query.php`, `/includes/dashboard-renderer.php`
  Dependencies: T017
  Acceptance Criteria: Announcements are shown only when the resolver allows them; empty `svdp_audience_profiles` skips only audience filtering, not scope/active/publish checks; announcement capability boundaries remain backend-only; announcement tests cover allow and deny cases.
  PR Slice Grouping: Announcements
  Contract/Risk Checks: Contract check: announcement behavior matches `/specs/contracts-spec.md`. Security check: admin authorization remains capability-based, not profile-based.

**Checkpoint**: Announcements slice ends when targeted announcements are visible through the dashboard without introducing a second visibility engine.

### PR Slice: Documents

- [ ] T019 [P] [US2] Add failing document contract and security tests in `/tests/contract/test-document-routes.php` and `/tests/integration/test-document-access.php`
  Description: Lock the canonical document route, detail/preview/download security behavior, and taxonomy usage before implementing protected document delivery.
  Files/Modules Affected: `/tests/contract/test-document-routes.php`, `/tests/integration/test-document-access.php`
  Dependencies: T016
  Acceptance Criteria: Tests cover `/resource-library/<doc-slug>/`, protected preview/download enforcement, and `svdp_doc_cat` usage; tests fail until delivery logic exists.
  PR Slice Grouping: Documents
  Contract/Risk Checks: Contract check: canonical document route and taxonomy only. Security check: document access must not leak through detail, preview, or download.

- [ ] T020 [US2] Implement protected document delivery in `/includes/documents.php`, `/includes/routes.php`, and `/templates/document-detail.php`
  Description: Add document detail, preview, and download behavior under centralized route ownership, using server-side resolver checks before returning protected content.
  Files/Modules Affected: `/includes/documents.php`, `/includes/routes.php`, `/templates/document-detail.php`
  Dependencies: T019
  Acceptance Criteria: Approved eligible users can access allowed document detail routes; approved ineligible users are denied detail, preview, and download routes; pending and disabled users are denied detail, preview, and download routes; document templates render only already-authorized data; document route tests cover detail, preview, and download allow/deny cases.
  PR Slice Grouping: Documents
  Contract/Risk Checks: Contract check: route ownership stays centralized. Security check: `/includes/documents.php` does not become an alternate routing or authorization layer.

- [ ] T021 [US2] Implement Drive import intake boundaries for document records in `/includes/drive-imports.php` and `/tests/integration/test-drive-imports.php`
  Description: Add the upstream import behavior for document records while preserving WordPress as the system of record for runtime document state and access control.
  Files/Modules Affected: `/includes/drive-imports.php`, `/tests/integration/test-drive-imports.php`
  Dependencies: T020
  Acceptance Criteria: Imported document records do not change canonical route patterns or resolver inputs; Google Drive remains an upstream source only; runtime document access continues to use WordPress data and resolver checks; Drive import tests confirm imports do not bypass protected delivery rules.
  PR Slice Grouping: Documents
  Contract/Risk Checks: Contract check: WordPress remains the runtime system of record. Security check: import logic does not override authorization behavior.

**Checkpoint**: User Story 2 is complete when announcements and documents are protected by the resolver and documents cannot be accessed through unguarded detail/preview/download paths.

---

## Phase 5: User Story 3 - Protected Events, Calendar Exports, and Admin Branding (Priority: P3)

**Goal**: Complete the remaining protected event/calendar routes and finish the settings/admin polish slice without introducing design or authorization drift.

**Independent Test**: Eligible users can access event detail and calendar export endpoints, ineligible users cannot, and branding/admin screens remain capability-gated.

### PR Slice: Events / Calendar Export

- [ ] T022 [P] [US3] Add failing event and calendar-export tests in `/tests/contract/test-event-routes.php`, `/tests/integration/test-event-access.php`, and `/tests/integration/test-calendar-feed-access.php`
  Description: Lock the canonical event routes, feed token handling, ICS download behavior, and resolver enforcement before implementing event detail and calendar export features.
  Files/Modules Affected: `/tests/contract/test-event-routes.php`, `/tests/integration/test-event-access.php`, `/tests/integration/test-calendar-feed-access.php`
  Dependencies: T021
  Acceptance Criteria: Tests cover `/events/<event-slug>/`, `/portal-calendar/feed/<token>/`, and `/portal-calendar/event/<event_id>/download/`; tests assert server-side visibility checks before data is returned; tests fail until implementation exists.
  PR Slice Grouping: Events / calendar export
  Contract/Risk Checks: Contract check: canonical event/feed/export routes only. Security check: no feed/export leakage.

- [ ] T023 [US3] Implement protected event detail behavior in `/includes/events.php`, `/includes/routes.php`, and `/templates/event-detail.php`
  Description: Add event detail queries, related-object behavior, and route-handled event rendering while preserving centralized route ownership and resolver enforcement.
  Files/Modules Affected: `/includes/events.php`, `/includes/routes.php`, `/templates/event-detail.php`
  Dependencies: T022
  Acceptance Criteria: Approved eligible users can access event detail; approved ineligible users are denied event detail; pending and disabled users are denied event detail; event detail routes invoke resolver checks before event data is returned.
  PR Slice Grouping: Events / calendar export
  Contract/Risk Checks: Contract check: canonical event relationships and meta keys only. Security check: event modules do not bypass routing/security entry points.

- [ ] T024 [US3] Implement calendar feed and single-event ICS export in `/includes/calendar-ics.php`, `/includes/routes.php`, and `/tests/integration/test-calendar-ics.php`
  Description: Add the protected calendar feed and single-event export behavior tied to canonical user token and event export controls.
  Files/Modules Affected: `/includes/calendar-ics.php`, `/includes/routes.php`, `/tests/integration/test-calendar-ics.php`
  Dependencies: T023
  Acceptance Criteria: Valid eligible tokens return only resolver-visible events; invalid or rotated tokens are denied; single-event ICS downloads enforce the same visibility rules as event detail access; feed/export tests cover allow and deny cases.
  PR Slice Grouping: Events / calendar export
  Contract/Risk Checks: Contract check: calendar routes and token semantics remain canonical. Security check: export paths do not bypass the resolver.

**Checkpoint**: Events/calendar slice ends when event detail and export endpoints are protected and test-covered as independent route/security boundaries.

### PR Slice: Settings / Admin Polish

- [ ] T025 [P] [US3] Add failing settings/admin access tests in `/tests/integration/test-settings-access.php` and `/tests/integration/test-admin-menu-access.php`
  Description: Lock the expected admin capability boundaries and branding setting access before polishing settings and admin menu behavior.
  Files/Modules Affected: `/tests/integration/test-settings-access.php`, `/tests/integration/test-admin-menu-access.php`
  Dependencies: T006
  Acceptance Criteria: Tests verify district-admin-only branding/settings access; tests verify only district admins can manage the `vincentian_hub_logo_attachment_id` branding setting and related upload flow; tests confirm non-admin roles cannot reach protected admin screens; tests fail before implementation is completed.
  PR Slice Grouping: Settings / admin polish
  Contract/Risk Checks: Contract check: branding/admin access matches the capability matrix. Security check: no capability leakage into non-admin roles.

- [ ] T026 [US3] Finalize branding, settings, and admin menu behavior in `/includes/settings.php`, `/includes/admin-menu.php`, and `/assets/css/hub.css`
  Description: Complete the logo-setting contract, admin-menu capability wiring, and last-mile UI polish while staying within the current theme baseline.
  Files/Modules Affected: `/includes/settings.php`, `/includes/admin-menu.php`, `/assets/css/hub.css`
  Dependencies: T025
  Acceptance Criteria: `vincentian_hub_logo_attachment_id` is the exact setting key used; district admins can access branding/settings screens and manage the branding/logo setting; non-admin roles are denied settings/admin access and branding/logo management; UI polish does not introduce a redesign beyond the current theme baseline.
  PR Slice Grouping: Settings / admin polish
  Contract/Risk Checks: Contract check: branding setting key and capability boundaries remain canonical. Security check: settings/admin code is not used as a substitute for front-end targeting.

**Checkpoint**: User Story 3 is complete when event/calendar exports and admin branding/settings are protected, test-covered, and ready for the final PR merge.

---

## Final Phase: Polish & Cross-Cutting Concerns

**Purpose**: Close the implementation with repo-wide validation, documentation, and final anti-drift checks after all slices are complete.

- [ ] T027 [P] Run cross-slice contract and security validation in `/.github/pull_request_template.md`, `/specs/contracts-spec.md`, `/specs/architecture/targeting-engine-rules.md`, and `/specs/architecture/wordpress-data-model-map.md`
  Description: Perform the final repo-wide verification that no slice introduced canonical-key drift, route changes, duplicate visibility logic, or client-side-only access control.
  Files/Modules Affected: `/.github/pull_request_template.md`, `/specs/contracts-spec.md`, `/specs/architecture/targeting-engine-rules.md`, `/specs/architecture/wordpress-data-model-map.md`
  Dependencies: T024, T026
  Acceptance Criteria: Every slice can be checked against the PR template; plugin identity, canonical keys, routes, and enums remain unchanged; all templates are verified to remain presentation-only with no query, resolver, or authorization logic; `svdp_role_profiles` remains the front-end targeting source instead of WordPress roles; no known contract/routing/security drift remains open; final validation output is ready for review.
  PR Slice Grouping: Polish
  Contract/Risk Checks: Anti-drift check: duplicated resolver logic, route-level permission gaps, meta drift, template auth logic, and role/profile confusion.

- [ ] T028 Update end-state implementation and validation notes in `/README.md`, `/tests/README.md`, and `/specs/001-vincentian-hub-plan/quickstart.md`
  Description: Document how the implemented plugin is validated slice-by-slice so future work starts from the completed architecture rather than re-deriving process.
  Files/Modules Affected: `/README.md`, `/tests/README.md`, `/specs/001-vincentian-hub-plan/quickstart.md`
  Dependencies: T027
  Acceptance Criteria: Project docs describe the final validation flow, required tests, slice boundaries, template-boundary checks, and branding/admin permission validation without contradicting the binding documents.
  PR Slice Grouping: Polish
  Contract/Risk Checks: Documentation check: docs remain aligned with the binding Vincentian Hub architecture set.

**Checkpoint**: The implementation is complete when all slices have merged, the anti-drift validation passes, and the final docs reflect the implemented architecture.

---

## Dependencies & Execution Order

### Phase Dependencies

- **Setup (Phase 1)**: No dependencies; establishes testing and validation scaffolding.
- **Foundational (Phase 2)**: Depends on Setup; blocks all implementation slices.
- **User Story 1 / Protected Dashboard MVP (Phase 3)**: Depends on Foundation; establishes resolver, auth/onboarding, conference context, and dashboard routes.
- **User Story 2 / Protected Announcements and Documents (Phase 4)**: Depends on User Story 1; reuses the established route/resolver/dashboard framework.
- **User Story 3 / Protected Events, Calendar Exports, and Admin Branding (Phase 5)**: Event/calendar depends on User Story 2; settings/admin polish depends on Foundation.
- **Polish (Final Phase)**: Depends on all implementation slices.

### User Story Dependencies

- **US1**: Starts after Foundation and delivers the recommended MVP.
- **US2**: Starts after US1 because announcements and documents depend on existing route ownership, resolver enforcement, and dashboard shell patterns.
- **US3**: Events/calendar starts after US2 because it reuses the same protected delivery model; settings/admin polish can proceed after Foundation but may be reviewed later for cleaner PR sequencing.

### PR Slice Dependencies

- **Foundation**: T002 -> T003/T004 -> T005 -> T006
- **Targeting resolver**: T007 -> T008 -> T009
- **Auth / onboarding**: T010 -> T011
- **Conferences / canonical context**: T012 -> T013
- **Dashboard shell and routes**: T014 -> T015 -> T016
- **Announcements**: T017 -> T018
- **Documents**: T019 -> T020 -> T021
- **Events / calendar export**: T022 -> T023 -> T024
- **Settings / admin polish**: T025 -> T026
- **Polish**: T027 -> T028

### What Gets Built First

1. Test scaffolding
2. Plugin/bootstrap foundation
3. Roles, capabilities, CPTs, taxonomy, meta, user meta, directory table
4. Resolver and normalized user context
5. Auth/onboarding
6. Conference context
7. Dashboard routes and rendering
8. Announcements and documents
9. Events/calendar exports
10. Settings/admin polish

### What Gets Tested First

1. Capability matrix and registration tests in the Foundation slice
2. Resolver order and normalized context tests in the Targeting Resolver slice
3. Auth/onboarding access-state tests before user-facing dashboards
4. Route-security tests before each protected content route goes live

### When a Slice Is Ready to Commit, Push, and Open a PR

- The slice's tasks are complete and its checkpoint is satisfied.
- The slice passes its explicit tests and manual validation steps.
- The slice changes only the modules listed in that PR boundary, plus closely related tests/docs.
- The slice passes the drift-prevention checks in `.github/pull_request_template.md`.

## Parallel Opportunities

- T003 and T004 can run in parallel after T002.
- Within each user story, the failing-test task can be prepared while the prior slice is under review, but implementation waits for merge.
- T025 can proceed independently from T022-T024 after Foundation if the team wants a separate admin/settings track.

## Parallel Example: Foundation Slice

```bash
Task: "Implement the canonical WordPress role and capability matrix in /includes/roles.php, /includes/capabilities.php, and /tests/unit/test-capability-matrix.php"
Task: "Register canonical CPTs and taxonomy in /includes/post-types.php, /includes/taxonomies.php, and /tests/unit/test-content-registration.php"
```

## Parallel Example: Protected Dashboard MVP

```bash
Task: "Add failing auth/onboarding flow tests in /tests/integration/test-auth-google-flow.php and /tests/integration/test-onboarding-access-states.php"
Task: "Add failing conference context tests in /tests/unit/test-conferences.php and /tests/integration/test-shortcode-context.php"
```

## Parallel Example: Final Feature Slices

```bash
Task: "Add failing document contract and security tests in /tests/contract/test-document-routes.php and /tests/integration/test-document-access.php"
Task: "Add failing event and calendar-export tests in /tests/contract/test-event-routes.php, /tests/integration/test-event-access.php, and /tests/integration/test-calendar-feed-access.php"
```

## Implementation Strategy

### MVP First

1. Complete Setup and Foundation.
2. Complete User Story 1 only.
3. Validate dashboard route security, auth/onboarding states, and resolver coverage.
4. Commit, push, open the PR for the dashboard MVP, and merge before starting User Story 2.

### Incremental Delivery

1. Foundation PR
2. Targeting resolver PR
3. Auth / onboarding PR
4. Conferences / canonical context PR
5. Dashboard shell and routes PR
6. Announcements PR
7. Documents PR
8. Events / calendar export PR
9. Settings / admin polish PR
10. Final polish PR if needed

### Notes

- Every checklist item above is specific enough to execute without reopening planning.
- Keep changes limited to the slice-specific files listed in each task unless a dependent test/doc file must change.
- Do not start the next implementation slice on the same branch after reaching a clean PR boundary.
