# Tasks: Vincentian Hub

**Input**: Design documents from `/Users/jeremiahotis/projects/vincentian-hub/specs/001-vincentian-hub-plan/`
**Prerequisites**: [plan.md](/Users/jeremiahotis/projects/vincentian-hub/specs/001-vincentian-hub-plan/plan.md), [spec.md](/Users/jeremiahotis/projects/vincentian-hub/specs/001-vincentian-hub-plan/spec.md), [research.md](/Users/jeremiahotis/projects/vincentian-hub/specs/001-vincentian-hub-plan/research.md), [data-model.md](/Users/jeremiahotis/projects/vincentian-hub/specs/001-vincentian-hub-plan/data-model.md), [contracts/route-and-interface-contracts.md](/Users/jeremiahotis/projects/vincentian-hub/specs/001-vincentian-hub-plan/contracts/route-and-interface-contracts.md), [quickstart.md](/Users/jeremiahotis/projects/vincentian-hub/specs/001-vincentian-hub-plan/quickstart.md)

**Tests**: Included. The approved plan and repo test guidance require early coverage for contract identity, resolver behavior, capability boundaries, route security, document/event access, and ICS visibility.

**Organization**: Tasks are grouped by implementation slice / PR boundary. Story labels map to the planning stories in `spec.md`:

- `[US1]`: Foundation conformance and registration work
- `[US2]`: Centralized visibility, route security, and protected feature delivery
- `[US3]`: Focused PR-slice completion, admin polish, and workflow hardening

## Format: `[ID] [P?] [Story?] Description with file path`

## Phase 1: Setup (Shared Infrastructure)

**Purpose**: Establish the minimal execution scaffolding so slice work and validation can happen predictably.

- [X] T001 Define WordPress testing and slice-validation conventions in `tests/README.md`
  Description: Expand the test README so each later slice has an agreed place for contract, integration, and security coverage and a clear definition of what gets tested first.
  Files/Modules Affected: `tests/README.md`
  Dependencies: None
  Acceptance Criteria:
  - `tests/README.md` names the minimum coverage areas from the architecture docs.
  - The document distinguishes contract checks, resolver/security checks, and feature-slice checks.
  - The document states that protected-route tests are required before protected UI slices are considered complete.
  PR Slice Grouping: Setup
  Contract/Risk Checks:
  - Validate against `specs/contracts-spec.md` change-control and security expectations.
  - Validate against `specs/architecture/targeting-engine-rules.md` resolver-enforcement scope.
  - Validate against `specs/architecture/wordpress-data-model-map.md` resolver join inputs.

- [X] T002 Create initial WordPress/PHP test bootstrap files in `tests/bootstrap.php` and `tests/phpunit.xml.dist`
  Description: Add the baseline test bootstrap and runner configuration needed for contract and security tests in later slices.
  Files/Modules Affected: `tests/bootstrap.php`, `tests/phpunit.xml.dist`
  Dependencies: T001
  Acceptance Criteria:
  - A test bootstrap file exists for plugin-loading and WordPress test setup.
  - A PHPUnit configuration file exists and points at the test bootstrap.
  - Later slice tests can be added without redefining the test runtime.
  PR Slice Grouping: Setup
  Contract/Risk Checks:
  - Keep the test scaffold WordPress-native and plugin-oriented.
  - Do not encode non-canonical schema names in the bootstrap.

- [X] T003 [P] Add WordPress plugin lifecycle test scaffolding in `tests/integration/test-plugin-lifecycle.php`
  Description: Add lifecycle-focused test scaffolding for activation/deactivation concerns tied to canonical plugin loading, registration readiness, and directory-table setup timing.
  Files/Modules Affected: `tests/integration/test-plugin-lifecycle.php`
  Dependencies: T002
  Acceptance Criteria:
  - Lifecycle test coverage has a dedicated home in the suite.
  - The scaffold can exercise activation-time setup expectations without redefining the test runtime.
  - Later Foundation tasks can validate entrypoint and table-setup timing through this file.
  PR Slice Grouping: Setup
  Contract/Risk Checks:
  - Keep lifecycle validation tied to canonical plugin identity and table semantics.
  - Do not introduce non-canonical plugin filenames into the tests.

**Checkpoint**: Setup is complete when the repository has a clear testing entry point and documented slice-validation rules.

---

## Phase 2: PR Slice 1 - Foundation (Priority: P1) 🎯 MVP

**Goal**: Bring the plugin into identity and registration conformance before any protected feature work begins.

**Independent Test**: The slice is complete when the plugin loads through the canonical entrypoint, registers the canonical roles/capabilities/CPTs/taxonomy/meta/user-meta/table semantics, and foundational contract tests pass.

- [X] T004 [US1] Add canonical plugin entrypoint and bootstrap handoff in `vincentian-hub.php`
  Description: Create the canonical main plugin file required by the contract and move the active plugin bootstrap load path to it.
  Files/Modules Affected: `vincentian-hub.php`, `includes/bootstrap.php`
  Dependencies: T003
  Acceptance Criteria:
  - `vincentian-hub.php` exists as the canonical plugin entrypoint.
  - The plugin constants and bootstrap handoff are loaded from the canonical file.
  - The repo no longer depends on `vincentian-hub-portal.php` as the active main file.
  PR Slice Grouping: Foundation
  Contract/Risk Checks:
  - Validate against `specs/contracts-spec.md` plugin identity lock and main plugin file enforcement.
  - Do not change the plugin slug, text domain, code prefix, or schema prefix.

- [X] T005 [P] [US1] Register canonical capabilities and WordPress roles in `includes/capabilities.php` and `includes/roles.php`
  Description: Implement the canonical capability registry and role boundaries, including the intentionally capability-light `svdp_district_staff` role.
  Files/Modules Affected: `includes/capabilities.php`, `includes/roles.php`
  Dependencies: T004
  Acceptance Criteria:
  - All canonical capabilities from the contracts spec are registered.
  - All canonical WordPress roles are registered with the correct capability boundaries.
  - The code keeps backend roles/capabilities separate from `svdp_role_profiles`.
  PR Slice Grouping: Foundation
  Contract/Risk Checks:
  - Validate against `specs/contracts-spec.md` capability registry and role matrix.
  - Prevent role/profile confusion, especially `svdp_district_staff` vs `district_staff`.

- [X] T006 [P] [US1] Register canonical CPTs and taxonomy in `includes/post-types.php` and `includes/taxonomies.php`
  Description: Implement the canonical content-object registry for conferences, dashboard items, announcements, documents, events, and document taxonomy.
  Files/Modules Affected: `includes/post-types.php`, `includes/taxonomies.php`
  Dependencies: T004
  Acceptance Criteria:
  - `svdp_conf`, `svdp_dash_item`, `svdp_announcement`, `svdp_doc`, and `svdp_event` are registered.
  - `svdp_doc_cat` is registered.
  - No alternate CPT or taxonomy names are introduced.
  PR Slice Grouping: Foundation
  Contract/Risk Checks:
  - Validate against `specs/contracts-spec.md` canonical object registry.
  - Validate against `specs/architecture/wordpress-data-model-map.md` core object types.

- [X] T007 [US1] Register canonical conference key meta in `includes/meta-registration.php`
  Description: Register the `svdp_conf` key meta map centrally so conference routing, flags, linked-page behavior, and conference context all depend on canonical keys.
  Files/Modules Affected: `includes/meta-registration.php`
  Dependencies: T006
  Acceptance Criteria:
  - Conference key meta from the data model map is registered centrally.
  - Conference route and flag fields use canonical key names.
  - Conference meta registration does not drift into `conferences.php` or route callbacks.
  PR Slice Grouping: Foundation
  Contract/Risk Checks:
  - Validate against `specs/architecture/wordpress-data-model-map.md` conference data model and canonical meta registration ownership.
  - Prevent conference-meta drift.

- [X] T008 [US1] Register the shared targeting block in `includes/meta-registration.php`
  Description: Register the shared targeting keys and enforce canonical naming so all targeted content types use the same resolver interface.
  Files/Modules Affected: `includes/meta-registration.php`
  Dependencies: T007
  Acceptance Criteria:
  - Shared targeting keys are registered centrally.
  - Prohibited alias keys are not introduced.
  - Shared targeting registration is not duplicated in feature modules.
  PR Slice Grouping: Foundation
  Contract/Risk Checks:
  - Validate against `specs/contracts-spec.md` shared targeting block.
  - Validate against `specs/architecture/wordpress-data-model-map.md` shared targeting block.

- [X] T009 [P] [US1] Register dashboard-item, announcement, document, and event unique meta in `includes/meta-registration.php`
  Description: Register the unique per-object meta maps for dashboard items, announcements, documents, and events in the canonical meta owner.
  Files/Modules Affected: `includes/meta-registration.php`
  Dependencies: T008
  Acceptance Criteria:
  - Unique meta keys for dashboard items, announcements, documents, and events are registered centrally.
  - Per-object meta maps match the WordPress data model map.
  - Feature modules do not invent local registration for these keys.
  PR Slice Grouping: Foundation
  Contract/Risk Checks:
  - Validate against `specs/architecture/wordpress-data-model-map.md` sections 7.1 through 7.4.
  - Prevent object-meta drift into feature modules.

- [X] T010 [P] [US1] Register required user meta and normalization helpers in `includes/user-meta.php`
  Description: Implement the required user meta keys, allowed enum handling, and storage expectations for user state.
  Files/Modules Affected: `includes/user-meta.php`
  Dependencies: T005
  Acceptance Criteria:
  - All required `svdp_` user meta keys are registered or normalized in one owner module.
  - `svdp_role_profiles` is handled as an array-of-strings shape.
  - Approval and account-scope enums are constrained to canonical values.
  PR Slice Grouping: Foundation
  Contract/Risk Checks:
  - Validate against `specs/contracts-spec.md` required user meta and enums.
  - Validate against `specs/architecture/wordpress-data-model-map.md` user data model.

- [X] T011 [US1] Implement the trusted directory table contract in `includes/directory-table.php`
  Description: Create the runtime table logic using `$wpdb->prefix` semantics and preserve the table as bootstrap/import source rather than runtime authority.
  Files/Modules Affected: `includes/directory-table.php`
  Dependencies: T010
  Acceptance Criteria:
  - The runtime physical table name resolves to `{$wpdb->prefix}svdp_directory`.
  - Canonical directory columns are defined.
  - The implementation notes or helpers make clear that directory data does not override user meta after account creation.
  PR Slice Grouping: Foundation
  Contract/Risk Checks:
  - Validate against `specs/contracts-spec.md` custom table naming.
  - Validate against `specs/architecture/wordpress-data-model-map.md` trusted directory model and post-user-creation rules.

- [X] T012 [US1] Implement plugin lifecycle wiring in `vincentian-hub.php` and `includes/bootstrap.php`
  Description: Add activation/deactivation lifecycle wiring for canonical plugin loading, registration readiness, and directory-table setup timing.
  Files/Modules Affected: `vincentian-hub.php`, `includes/bootstrap.php`, `includes/directory-table.php`
  Dependencies: T004, T011
  Acceptance Criteria:
  - Lifecycle wiring exists in the canonical entrypoint/bootstrap path.
  - Directory-table setup timing is tied to plugin lifecycle rather than ad hoc runtime behavior.
  - Lifecycle behavior does not depend on the non-canonical plugin filename.
  PR Slice Grouping: Foundation
  Contract/Risk Checks:
  - Validate against `specs/contracts-spec.md` main plugin file enforcement and runtime table semantics.
  - Prevent lifecycle drift away from the canonical entrypoint.

- [X] T013 [P] [US1] Add foundational contract and lifecycle coverage in `tests/contract/test-foundation-contracts.php` and `tests/integration/test-plugin-lifecycle.php`
  Description: Add tests that fail until the canonical plugin file, object registry, conference/object meta maps, role/capability map, user meta, runtime table semantics, and lifecycle wiring are implemented.
  Files/Modules Affected: `tests/contract/test-foundation-contracts.php`, `tests/integration/test-plugin-lifecycle.php`
  Dependencies: T004, T005, T006, T007, T008, T009, T010, T011, T012
  Acceptance Criteria:
  - Tests assert the canonical plugin identity and entrypoint expectations.
  - Tests assert the CPT, taxonomy, conference meta, object meta, user meta, and capability registry contracts.
  - Tests assert the directory table uses `$wpdb->prefix` semantics.
  - Tests assert lifecycle setup hooks the canonical plugin entrypoint and setup timing.
  PR Slice Grouping: Foundation
  Contract/Risk Checks:
  - Validate against `specs/contracts-spec.md` sections 2 through 9.
  - Validate against `specs/architecture/wordpress-data-model-map.md` conference and object meta maps.
  - Catch hardcoded `wp_` usage and missing required registration.

- [X] T014 [P] [US1] Add capability-boundary coverage in `tests/integration/test-capability-boundaries.php`
  Description: Add explicit tests for district announcements editor, district editor, and district admin permissions so role boundaries are not inferred loosely.
  Files/Modules Affected: `tests/integration/test-capability-boundaries.php`
  Dependencies: T005
  Acceptance Criteria:
  - Tests cover `svdp_district_announcements_editor` permissions.
  - Tests cover `svdp_district_editor` permissions.
  - Tests cover `svdp_district_admin` permissions.
  PR Slice Grouping: Foundation
  Contract/Risk Checks:
  - Validate against `specs/contracts-spec.md` capability-to-role matrix.
  - Prevent capability leakage across district roles.

**Checkpoint**: PR Slice 1 is ready when the canonical entrypoint and all foundational registry contracts are implemented and foundational contract tests pass.

---

## Phase 3: PR Slice 2 - Resolver Core (Priority: P2)

**Goal**: Create the single front-end visibility authority before route or feature rendering work begins.

**Independent Test**: The slice is complete when normalized user context and resolver decisions can be exercised independently and their order matches the targeting contract.

- [ ] T015 [P] [US2] Add normalized-user-context, resolver-order, and role-vs-profile separation tests in `tests/unit/test-targeting-resolver.php`
  Description: Write tests for normalized user context shape, empty-audience behavior, resolver order, and allow/deny evaluation before implementing the resolver.
  Files/Modules Affected: `tests/unit/test-targeting-resolver.php`
  Dependencies: T013, T014
  Acceptance Criteria:
  - Tests cover the canonical context schema fields.
  - Tests cover resolver order and empty-audience semantics.
  - Tests cover scope, audience, conference-mode, and group-flag cases.
  - Tests prove WordPress roles do not imply matching `svdp_role_profiles`.
  PR Slice Grouping: Resolver Core
  Contract/Risk Checks:
  - Validate against `specs/architecture/targeting-engine-rules.md` sections 3A, 7, and 8.
  - Catch partial or reordered resolver checks and role/profile conflation.

- [ ] T016 [US2] Implement conference-flag derivation helpers in `includes/conferences.php`
  Description: Add active-conference lookup and normalized conference-flag derivation used by the resolver context.
  Files/Modules Affected: `includes/conferences.php`
  Dependencies: T013
  Acceptance Criteria:
  - Conference flags derive from active `svdp_conf` meta only.
  - Derived flags normalize to `urban`, `rural`, `new_haven`, and `allen_county`.
  - Resolver consumers do not need to query conference meta directly during evaluation.
  PR Slice Grouping: Resolver Core
  Contract/Risk Checks:
  - Validate against `specs/architecture/wordpress-data-model-map.md` conference flags derivation.
  - Prevent direct conference-meta reads during resolver evaluation.

- [ ] T017 [US2] Implement normalized user context and allow/deny resolution in `includes/targeting-resolver.php`
  Description: Build the canonical normalized-user-context creator and the shared targeting resolver in the authoritative module.
  Files/Modules Affected: `includes/targeting-resolver.php`
  Dependencies: T015, T016
  Acceptance Criteria:
  - The normalized context shape matches the contract exactly.
  - Resolver evaluation order matches the targeting rules exactly.
  - Shared targeting keys and semantics remain canonical.
  PR Slice Grouping: Resolver Core
  Contract/Risk Checks:
  - Validate against `specs/architecture/targeting-engine-rules.md` resolver authority and anti-drift rules.
  - Validate against `specs/contracts-spec.md` shared targeting semantics.

- [ ] T018 [US2] Implement shared permission helpers in `includes/permissions.php`
  Description: Add reusable server-side permission helpers that consume the normalized context and resolver without becoming an alternate visibility engine.
  Files/Modules Affected: `includes/permissions.php`
  Dependencies: T017
  Acceptance Criteria:
  - Shared permission helpers call into the canonical resolver rather than duplicating checks.
  - Helpers can be reused by routes, document delivery, event delivery, feeds, and exports.
  - The module does not become a second resolver implementation.
  PR Slice Grouping: Resolver Core
  Contract/Risk Checks:
  - Validate against `specs/architecture/targeting-engine-rules.md` authorization boundary.
  - Prevent duplicated visibility logic outside `includes/targeting-resolver.php`.

- [ ] T019 [US2] Add protected-entry-point enforcement tests in `tests/integration/test-route-security-foundation.php`
  Description: Add integration coverage for shared protected-entry-point enforcement so resolver-backed permission behavior is validated before concrete feature routes are built.
  Files/Modules Affected: `tests/integration/test-route-security-foundation.php`
  Dependencies: T017, T018
  Acceptance Criteria:
  - Tests cover the requirement for server-side permission checks on protected entry points.
  - Tests fail if protected-entry-point helpers return objects without invoking the resolver.
  - The test suite can be extended by later concrete route slices without redefining shared security expectations.
  PR Slice Grouping: Resolver Core
  Contract/Risk Checks:
  - Validate against `specs/architecture/architecture.md` security model.
  - Validate against `specs/architecture/targeting-engine-rules.md` resolver-enforcement scope.

- [ ] T020 [US2] Add shortcode enforcement and explicit AJAX exclusion coverage in `includes/permissions.php` and `tests/integration/test-shortcode-ajax-protection.php`
  Description: Define and test resolver-backed protection expectations for shortcode-rendered content and explicitly record that protected AJAX object responses are out of MVP scope.
  Files/Modules Affected: `includes/permissions.php`, `tests/integration/test-shortcode-ajax-protection.php`
  Dependencies: T018, T019
  Acceptance Criteria:
  - Protected shortcode-rendered content enforces the resolver before output.
  - Protected AJAX object responses are explicitly documented as out of MVP scope.
  - The test coverage records the shortcode enforcement path and the AJAX scope exclusion.
  PR Slice Grouping: Resolver Core
  Contract/Risk Checks:
  - Validate against `specs/architecture/targeting-engine-rules.md` resolver enforcement for shortcode-rendered content.
  - Prevent shortcode access-control drift and silent AJAX-scope expansion.

**Checkpoint**: PR Slice 2 is ready when the canonical resolver exists, permission helpers use it, and resolver/security foundation tests pass.

---

## Phase 4: PR Slice 3 - Auth / Onboarding (Priority: P2)

**Goal**: Establish the approved, pending, disabled, and onboarding flows before protected content routes are exposed.

**Independent Test**: The slice is complete when authenticated users are routed into the correct gate flow based on approval status and account scope, without exposing protected portal content.

- [ ] T021 [P] [US2] Add auth and onboarding flow tests in `tests/integration/test-auth-onboarding.php`
  Description: Write integration coverage for approved, pending, disabled, and incomplete-onboarding flows before implementing auth/onboarding behavior.
  Files/Modules Affected: `tests/integration/test-auth-onboarding.php`
  Dependencies: T020
  Acceptance Criteria:
  - Tests cover approval-state gating.
  - Tests cover conference vs district account-scope handling.
  - Tests fail if protected content is reachable before the resolver-backed gate flow is satisfied.
  PR Slice Grouping: Auth / Onboarding
  Contract/Risk Checks:
  - Validate against `specs/contracts-spec.md` approval and account-scope enums.
  - Prevent onboarding from bypassing approval status.

- [ ] T022 [US2] Implement Google OAuth entry flow in `includes/auth-google.php`
  Description: Add the Google authentication flow owner module and persist canonical user-state inputs without inventing alternate user-state schema.
  Files/Modules Affected: `includes/auth-google.php`
  Dependencies: T021
  Acceptance Criteria:
  - Auth flow stores or updates canonical user meta inputs only.
  - No authorization decisions depend on `svdp_directory_source`.
  - Authentication behavior stays separate from portal content rendering.
  PR Slice Grouping: Auth / Onboarding
  Contract/Risk Checks:
  - Validate against `specs/contracts-spec.md` user meta semantics.
  - Keep WordPress as the source of truth for runtime user state.

- [ ] T023 [US2] Implement onboarding gate and conference-selection flow in `includes/onboarding.php`
  Description: Route authenticated users through onboarding and approval-state checks using canonical account-scope and conference-assignment semantics.
  Files/Modules Affected: `includes/onboarding.php`
  Dependencies: T022
  Acceptance Criteria:
  - Conference users require exactly one canonical conference assignment.
  - District users do not use conference targeting for access decisions.
  - Pending and disabled users are blocked from protected portal access.
  PR Slice Grouping: Auth / Onboarding
  Contract/Risk Checks:
  - Validate against `specs/contracts-spec.md` account-scope rules.
  - Prevent district flows from inheriting conference-targeting behavior.

- [ ] T024 [P] [US2] Wire auth-facing templates in `templates/login.php`, `templates/onboarding.php`, and `templates/pending-access.php`
  Description: Implement presentation-only templates for login, onboarding, and pending access without adding query or authorization logic to the template layer.
  Files/Modules Affected: `templates/login.php`, `templates/onboarding.php`, `templates/pending-access.php`
  Dependencies: T023
  Acceptance Criteria:
  - Templates render state already prepared by the owner modules.
  - Templates do not query protected objects or run authorization decisions.
  - Output remains compatible with the current theme baseline.
  PR Slice Grouping: Auth / Onboarding
  Contract/Risk Checks:
  - Validate against `specs/architecture/architecture.md` template ownership boundary.
  - Prevent templates from becoming authorization layers.

**Checkpoint**: PR Slice 3 is ready when auth/onboarding flows are enforced and no protected portal content is reachable before those gates are satisfied.

---

## Phase 5: PR Slice 4 - Conferences / Context (Priority: P2)

**Goal**: Centralize conference lookup, route-token resolution, linked-page mapping, and shortcode context before dashboard routes are built.

**Independent Test**: The slice is complete when conference context comes from canonical conference lookup and `svdp_conf_page_slug`, not from request-parameter shortcuts or post slugs.

- [ ] T025 [P] [US2] Add conference-route and shortcode-context tests in `tests/integration/test-conference-context.php`
  Description: Add tests for route-token resolution via `svdp_conf_page_slug`, linked page mapping, and shortcode context derivation before implementing those behaviors.
  Files/Modules Affected: `tests/integration/test-conference-context.php`
  Dependencies: T020
  Acceptance Criteria:
  - Tests fail if conference routes resolve from post slug/title instead of `svdp_conf_page_slug`.
  - Tests fail if shortcode context comes directly from request params instead of canonical conference context.
  - Tests cover active-conference-only flag derivation behavior.
  PR Slice Grouping: Conferences / Context
  Contract/Risk Checks:
  - Validate against `specs/contracts-spec.md` conference route slug source.
  - Validate against `specs/architecture/wordpress-data-model-map.md` conference data model.

- [ ] T026 [US2] Implement conference lookup, page linkage, and route-token helpers in `includes/conferences.php`
  Description: Extend the conferences module with the canonical route-token and linked-page behavior needed by dashboard and protected routes.
  Files/Modules Affected: `includes/conferences.php`
  Dependencies: T025
  Acceptance Criteria:
  - Conference lookup resolves from `svdp_conf_page_slug`.
  - Linked page and conference lookup helpers are reusable by route entry points.
  - Conference-specific helpers stay in `includes/conferences.php`, not in templates or route callbacks.
  PR Slice Grouping: Conferences / Context
  Contract/Risk Checks:
  - Validate against `specs/architecture/architecture.md` canonical module ownership.
  - Prevent route token drift and scattered conference lookup logic.

- [ ] T027 [US2] Implement canonical shortcode context injection in `includes/shortcode-context.php`
  Description: Inject conference context for shortcode-backed tools using canonical lookup/context helpers rather than direct request parsing.
  Files/Modules Affected: `includes/shortcode-context.php`
  Dependencies: T026
  Acceptance Criteria:
  - Shortcode context comes from canonical conference context.
  - The module does not become a second conference lookup layer.
  - Protected shortcode-backed content can reuse the resolver and permission helpers.
  PR Slice Grouping: Conferences / Context
  Contract/Risk Checks:
  - Validate against `specs/architecture/architecture.md` shortcode-context ownership.
  - Prevent request-param-derived context drift.

**Checkpoint**: PR Slice 4 is ready when conference route/context helpers are canonical and reusable by dashboard and protected feature slices.

---

## Phase 6: PR Slice 5 - Dashboard Shell (Priority: P2)

**Goal**: Deliver conference and district dashboards through canonical routes, centralized query logic, and presentation-only templates.

**Independent Test**: The slice is complete when authenticated users can reach only their authorized dashboard route, visible dashboard datasets come from the query module, and templates render without rebuilding visibility logic.

- [ ] T028 [P] [US2] Add dashboard-route and dashboard-visibility tests in `tests/integration/test-dashboard-routes.php`
  Description: Add integration tests for conference and district dashboard routes, including route-level protection and resolver-backed visibility filtering.
  Files/Modules Affected: `tests/integration/test-dashboard-routes.php`
  Dependencies: T027
  Acceptance Criteria:
  - Tests cover conference and district dashboard route access.
  - Tests fail if dashboard routes return protected datasets without server-side resolver checks.
  - Tests cover scope-appropriate dashboard visibility.
  PR Slice Grouping: Dashboard Shell
  Contract/Risk Checks:
  - Validate against `specs/contracts-spec.md` route patterns and security contract.
  - Validate against `specs/architecture/architecture.md` security model.

- [ ] T029 [US2] Implement protected dashboard route entry points in `includes/routes.php`
  Description: Add the conference and district dashboard route entry points and enforce server-side checks before any protected content is returned.
  Files/Modules Affected: `includes/routes.php`
  Dependencies: T028
  Acceptance Criteria:
  - Dashboard route entry points are declared from `includes/routes.php`.
  - Route callbacks construct normalized user context and enforce resolver-backed permission checks.
  - No dashboard authorization logic lives only in templates or client-side code.
  PR Slice Grouping: Dashboard Shell
  Contract/Risk Checks:
  - Validate against `specs/architecture/architecture.md` canonical route ownership.
  - Prevent route-level permission gaps.

- [ ] T030 [US2] Implement visible dashboard dataset assembly in `includes/dashboard-query.php`
  Description: Build the query layer that assembles dashboard data using the centralized resolver instead of ad hoc feature-specific checks.
  Files/Modules Affected: `includes/dashboard-query.php`
  Dependencies: T029
  Acceptance Criteria:
  - Query logic uses the normalized context and resolver.
  - The query layer does not become the rendering layer.
  - Dataset assembly stays reusable for conference and district dashboards.
  PR Slice Grouping: Dashboard Shell
  Contract/Risk Checks:
  - Validate against `specs/architecture/architecture.md` dashboard query/render separation.
  - Prevent duplicated visibility logic in the query layer.

- [ ] T031 [US2] Implement dashboard composition in `includes/dashboard-renderer.php` and templates in `templates/dashboard-conference.php` and `templates/dashboard-district.php`
  Description: Compose dashboard sections from prepared datasets and render them through presentation-only templates.
  Files/Modules Affected: `includes/dashboard-renderer.php`, `templates/dashboard-conference.php`, `templates/dashboard-district.php`
  Dependencies: T030
  Acceptance Criteria:
  - Renderer consumes prepared datasets instead of rebuilding resolver logic.
  - Templates only render provided data.
  - Output follows the current theme baseline and mobile-first requirements.
  PR Slice Grouping: Dashboard Shell
  Contract/Risk Checks:
  - Validate against `specs/architecture/architecture.md` template ownership boundary.
  - Prevent renderer/query ownership bleed and template query logic.

**Checkpoint**: PR Slice 5 is ready when protected dashboard routes, dataset assembly, and rendering all operate through the canonical route/query/render/template boundaries.

---

## Phase 7: PR Slice 6 - Announcements (Priority: P2)

**Goal**: Add announcement behavior as a focused content slice that reuses the shared targeting model and existing dashboard security boundaries.

**Independent Test**: The slice is complete when announcement visibility follows the shared targeting block and announcement CRUD boundaries remain capability-based in admin contexts.

- [ ] T032 [P] [US2] Add announcement visibility tests in `tests/integration/test-announcements.php`
  Description: Add coverage for announcement visibility within the shared targeting model and ensure no announcement-specific access shortcuts are introduced.
  Files/Modules Affected: `tests/integration/test-announcements.php`
  Dependencies: T031
  Acceptance Criteria:
  - Tests cover announcement visibility through shared targeting keys.
  - Tests fail if announcements introduce feature-specific access rules outside the resolver.
  - Tests cover district-announcements-editor capability boundaries where relevant.
  PR Slice Grouping: Announcements
  Contract/Risk Checks:
  - Validate against `specs/contracts-spec.md` shared targeting block and capability matrix.
  - Prevent announcement-specific targeting drift.

- [ ] T033 [US2] Implement announcement object behavior in `includes/announcements.php`
  Description: Add the announcement-specific runtime behavior needed by dashboard and portal surfaces while reusing shared targeting and capability boundaries.
  Files/Modules Affected: `includes/announcements.php`
  Dependencies: T032
  Acceptance Criteria:
  - Announcement behavior uses canonical announcement fields and shared targeting keys.
  - Front-end visibility uses resolver decisions rather than local access logic.
  - Admin authorization remains capability-based.
  PR Slice Grouping: Announcements
  Contract/Risk Checks:
  - Validate against `specs/contracts-spec.md` announcement enum values and capability boundaries.
  - Validate against `specs/architecture/wordpress-data-model-map.md` announcement object map.

**Checkpoint**: PR Slice 6 is ready when announcements behave as a focused content slice without introducing new access-control paths.

---

## Phase 8: PR Slice 7 - Documents (Priority: P2)

**Goal**: Deliver protected document detail, preview, and download behavior through the canonical document owner and protected routes.

**Independent Test**: The slice is complete when document detail, preview, and download each enforce server-side access independently and document templates do not bypass the document-delivery owner.

- [ ] T034 [P] [US2] Add protected document access tests in `tests/integration/test-documents.php`
  Description: Add integration coverage for document detail, preview, and download access so protected delivery cannot be implemented without server-side enforcement.
  Files/Modules Affected: `tests/integration/test-documents.php`
  Dependencies: T031
  Acceptance Criteria:
  - Tests cover document detail, preview, and download as separate protected entry points.
  - Tests fail if list-query filtering is the only protection.
  - Tests cover source-aware delivery behavior for protected documents.
  PR Slice Grouping: Documents
  Contract/Risk Checks:
  - Validate against `specs/architecture/architecture.md` canonical document delivery ownership.
  - Validate against `specs/architecture/targeting-engine-rules.md` detail-route enforcement scope.

- [ ] T035 [US2] Implement protected document behavior in `includes/documents.php`
  Description: Add the canonical document-detail, preview, download, and source-aware delivery logic in the document owner module.
  Files/Modules Affected: `includes/documents.php`
  Dependencies: T034
  Acceptance Criteria:
  - Document detail, preview, and download logic lives in `includes/documents.php`.
  - Delivery behavior enforces resolver-backed permissions before protected file access.
  - The module respects canonical document fields and source semantics.
  PR Slice Grouping: Documents
  Contract/Risk Checks:
  - Validate against `specs/architecture/wordpress-data-model-map.md` document object map.
  - Prevent document-specific local access logic from diverging from the resolver.

- [ ] T036 [US2] Wire protected document routes in `includes/routes.php` and presentation in `templates/document-detail.php`
  Description: Register the canonical document detail route entry point and render the already-authorized document through a presentation-only template.
  Files/Modules Affected: `includes/routes.php`, `templates/document-detail.php`
  Dependencies: T035
  Acceptance Criteria:
  - `/resource-library/<doc-slug>/` is owned by `includes/routes.php`.
  - Route handling enforces server-side access before content is returned.
  - The document template does not perform delivery or authorization decisions.
  PR Slice Grouping: Documents
  Contract/Risk Checks:
  - Validate against `specs/contracts-spec.md` route and security contract.
  - Prevent template or route-callback bypass of document-delivery ownership.

**Checkpoint**: PR Slice 7 is ready when document detail, preview, and download all enforce canonical protected delivery behavior.

---

## Phase 9: PR Slice 8 - Events / Calendar Export (Priority: P2)

**Goal**: Deliver protected event detail and calendar export behavior while keeping event logic and feed/export logic in their canonical owners.

**Independent Test**: The slice is complete when event detail, personalized feeds, and single-event downloads each enforce server-side access and event/calendar responsibilities remain separated.

- [ ] T037 [P] [US2] Add event-detail and ICS visibility tests in `tests/integration/test-events-calendar.php`
  Description: Add integration tests for event detail, personalized feed access, and single-event ICS downloads before implementing the event and calendar modules.
  Files/Modules Affected: `tests/integration/test-events-calendar.php`
  Dependencies: T031
  Acceptance Criteria:
  - Tests cover `/events/<event-slug>/`, `/portal-calendar/feed/<token>/`, and `/portal-calendar/event/<event_id>/download/`.
  - Tests fail if feeds or downloads bypass server-side checks.
  - Tests cover personalized feed token behavior and single-event visibility.
  PR Slice Grouping: Events / Calendar Export
  Contract/Risk Checks:
  - Validate against `specs/contracts-spec.md` canonical routes.
  - Validate against `specs/architecture/architecture.md` canonical event and calendar ownership.

- [ ] T038 [US2] Implement event behavior in `includes/events.php`
  Description: Add event-detail logic, event relationships, and event-specific queries without absorbing calendar-export responsibilities.
  Files/Modules Affected: `includes/events.php`
  Dependencies: T037
  Acceptance Criteria:
  - Event-detail behavior lives in `includes/events.php`.
  - Event-specific relationships and queries remain in the event module.
  - The module does not become the owner of feed/export logic.
  PR Slice Grouping: Events / Calendar Export
  Contract/Risk Checks:
  - Validate against `specs/architecture/wordpress-data-model-map.md` event object map.
  - Prevent event/calendar responsibility bleed.

- [ ] T039 [US2] Implement feed and export behavior in `includes/calendar-ics.php`
  Description: Add personalized feed generation, single-event ICS output, and add-to-calendar support in the canonical calendar module.
  Files/Modules Affected: `includes/calendar-ics.php`
  Dependencies: T037
  Acceptance Criteria:
  - Feed/export behavior lives in `includes/calendar-ics.php`.
  - Feed/export code enforces resolver-backed visibility and token checks.
  - Single-event export behavior remains separate from generic event-detail rendering.
  PR Slice Grouping: Events / Calendar Export
  Contract/Risk Checks:
  - Validate against `specs/architecture/architecture.md` canonical event and calendar ownership.
  - Prevent ad hoc export logic in templates or route callbacks.

- [ ] T040 [US2] Wire protected event and calendar routes in `includes/routes.php` and `templates/event-detail.php`
  Description: Register the event detail, personalized feed, and single-event download routes and render event detail through a presentation-only template.
  Files/Modules Affected: `includes/routes.php`, `templates/event-detail.php`
  Dependencies: T038, T039
  Acceptance Criteria:
  - Canonical event and calendar routes are owned by `includes/routes.php`.
  - Route callbacks enforce server-side checks before returning protected content.
  - The event template remains presentation-only.
  PR Slice Grouping: Events / Calendar Export
  Contract/Risk Checks:
  - Validate against `specs/contracts-spec.md` route/security contract.
  - Prevent route-callback duplication of event or calendar ownership.

**Checkpoint**: PR Slice 8 is ready when event detail and calendar exports are protected and owned by their canonical modules.

---

## Phase 10: PR Slice 9 - Settings / Admin / Imports (Priority: P3)

**Goal**: Finish admin-facing wiring and import behavior without changing established content-security boundaries.

**Independent Test**: The slice is complete when branding settings, admin menu access, and drive-import behavior follow canonical settings/capability rules and preserve WordPress as the runtime authority.

- [ ] T041 [P] [US3] Add settings/admin capability tests in `tests/integration/test-settings-admin.php`
  Description: Add coverage for settings access, branding setting ownership, and admin capability boundaries before implementing admin-facing polish.
  Files/Modules Affected: `tests/integration/test-settings-admin.php`
  Dependencies: T040
  Acceptance Criteria:
  - Tests cover district-admin-only settings access where required.
  - Tests cover the canonical branding setting key.
  - Tests fail if lower roles gain unintended admin access.
  PR Slice Grouping: Settings / Admin / Imports
  Contract/Risk Checks:
  - Validate against `specs/contracts-spec.md` header logo contract and capability boundaries.
  - Prevent admin capability leakage.

- [ ] T042 [US3] Implement plugin settings and branding behavior in `includes/settings.php`
  Description: Add the plugin settings owner, including branding behavior for the canonical logo setting key and fallback behavior.
  Files/Modules Affected: `includes/settings.php`
  Dependencies: T041
  Acceptance Criteria:
  - `vincentian_hub_logo_attachment_id` is the branding setting key.
  - Branding behavior includes fallback text when no logo is present.
  - Settings logic stays in the settings owner module.
  PR Slice Grouping: Settings / Admin / Imports
  Contract/Risk Checks:
  - Validate against `specs/contracts-spec.md` header logo contract.
  - Prevent settings drift into templates or admin-menu callbacks.

- [ ] T043 [P] [US3] Implement admin menu access wiring in `includes/admin-menu.php`
  Description: Add admin menu registration and capability-gated access wiring for the plugin administration surfaces.
  Files/Modules Affected: `includes/admin-menu.php`
  Dependencies: T042
  Acceptance Criteria:
  - Admin menu access is wired through canonical capabilities.
  - Menu exposure stays aligned with role boundaries from the contracts spec.
  - Menu logic does not substitute for front-end visibility targeting.
  PR Slice Grouping: Settings / Admin / Imports
  Contract/Risk Checks:
  - Validate against `specs/contracts-spec.md` role and capability boundaries.
  - Prevent backend authorization drift.

- [ ] T044 [P] [US3] Implement Google Drive import admin behavior in `includes/drive-imports.php`
  Description: Add document import/sync intake behavior for admin users while preserving WordPress as the system of record at runtime.
  Files/Modules Affected: `includes/drive-imports.php`
  Dependencies: T042
  Acceptance Criteria:
  - Drive imports are capability-gated.
  - Imported data feeds canonical document storage and meta rather than bypassing WordPress authority.
  - Import behavior does not become a runtime visibility authority.
  PR Slice Grouping: Settings / Admin / Imports
  Contract/Risk Checks:
  - Validate against `specs/contracts-spec.md` system-of-record boundary.
  - Prevent Google Drive from becoming an active content authority.

**Checkpoint**: PR Slice 9 is ready when admin-facing configuration and import behavior are implemented without capability or authority drift.

---

## Phase 11: PR Slice 10 - UI Polish / Validation Docs (Priority: P3)

**Goal**: Apply bounded theme-baseline polish and close the plan with up-to-date validation guidance.

**Independent Test**: The slice is complete when the UI remains within the current theme baseline, client-side code does not become an access-control layer, and quickstart guidance reflects the final PR sequence.

- [ ] T045 [US3] Apply bounded theme-baseline polish in `assets/css/hub.css` and `assets/js/hub.js`
  Description: Refine the portal’s mobile-first presentation and minimal JS behavior without turning assets into alternate access-control or query layers.
  Files/Modules Affected: `assets/css/hub.css`, `assets/js/hub.js`
  Dependencies: T031, T036, T040, T042, T043
  Acceptance Criteria:
  - UI remains mobile-first and aligned to the current theme baseline.
  - JS does not perform protected-content authorization.
  - Styling supports the portal templates without redesign drift.
  PR Slice Grouping: UI Polish / Validation Docs
  Contract/Risk Checks:
  - Validate against `specs/issues/SPEC.md` UI constraints.
  - Prevent client-side-only access-control behavior.

- [ ] T046 [US3] Run full slice validation and update execution guidance in `specs/001-vincentian-hub-plan/quickstart.md`
  Description: Record the final validation flow and ensure each slice’s readiness criteria are reflected in the quickstart before closing the planning cycle.
  Files/Modules Affected: `specs/001-vincentian-hub-plan/quickstart.md`
  Dependencies: T043, T044, T045
  Acceptance Criteria:
  - `quickstart.md` reflects the final slice-validation order.
  - The document explicitly calls out the PR-ready checkpoint for the last slice.
  - Cross-cutting validation steps cover contracts, resolver/security checks, admin checks, and UI checks.
  PR Slice Grouping: UI Polish / Validation Docs
  Contract/Risk Checks:
  - Validate against `specs/issues/SPEC.md` PR-first workflow requirements.
  - Prevent unfinished cross-slice validation from being deferred silently.

**Checkpoint**: PR Slice 10 is ready when bounded UI polish is complete and the final validation guidance is current.

---

## Dependencies & Execution Order

### Phase Dependencies

- **Phase 1: Setup** starts immediately.
- **Phase 2: Foundation** depends on Setup and blocks all later slices.
- **Phase 3: Resolver Core** depends on Foundation.
- **Phase 4: Auth / Onboarding** depends on Foundation and Resolver Core.
- **Phase 5: Conferences / Context** depends on Foundation and Resolver Core.
- **Phase 6: Dashboard Shell** depends on Foundation, Resolver Core, and Conferences / Context.
- **Phase 7: Announcements** depends on Dashboard Shell.
- **Phase 8: Documents** depends on Dashboard Shell.
- **Phase 9: Events / Calendar Export** depends on Resolver Core, route-security foundation, and Dashboard Shell.
- **Phase 10: Settings / Admin / Imports** depends on earlier slices as noted per task.
- **Phase 11: UI Polish / Validation Docs** depends on the relevant protected-surface and admin slices.

### What Gets Built First

1. Testing conventions and bootstrap scaffolding
2. Canonical plugin entrypoint, foundational registries, and lifecycle wiring
3. Normalized user context and central resolver
4. Auth/onboarding gates
5. Conference context and route-token ownership
6. Dashboard shell
7. Announcements
8. Documents
9. Events / calendar export
10. Settings / admin / imports
11. Bounded UI polish and validation docs

### What Gets Tested First

1. Foundation contract and lifecycle tests
2. Capability-boundary tests
3. Resolver, role-vs-profile, and protected-entry-point tests
4. Auth/onboarding flow tests
5. Conference-context tests
6. Dashboard route/visibility tests
7. Announcement visibility tests
8. Document detail/preview/download tests
9. Event detail and ICS visibility tests
10. Settings/admin capability tests

### What Ends Each PR Slice

- **Foundation** ends at T014
- **Resolver Core** ends at T020
- **Auth / Onboarding** ends at T024
- **Conferences / Context** ends at T027
- **Dashboard Shell** ends at T031
- **Announcements** ends at T033
- **Documents** ends at T036
- **Events / Calendar Export** ends at T040
- **Settings / Admin / Imports** ends at T044
- **UI Polish / Validation Docs** ends at T046

### When A Slice Is Complete Enough To Commit, Push, And Open A PR

- The slice’s final checkpoint task is complete.
- The slice’s acceptance criteria are met.
- The slice’s contract/risk checks have been verified against:
  - `specs/contracts-spec.md`
  - `specs/architecture/targeting-engine-rules.md`
  - `specs/architecture/wordpress-data-model-map.md`
- The slice does not include unrelated work from the next boundary.

---

## Parallel Opportunities

- T005 and T006 can run in parallel after T004.
- T010 can run in parallel with meta-registration work after T005 begins.
- T038 and T039 can run in parallel after T037.
- T043 and T044 can run in parallel after T042.

### Parallel Example: Foundation

```text
T005 Register canonical capabilities and WordPress roles in includes/capabilities.php and includes/roles.php
T006 Register canonical CPTs and taxonomy in includes/post-types.php and includes/taxonomies.php
T010 Register required user meta and normalization helpers in includes/user-meta.php
```

### Parallel Example: Events / Calendar Export

```text
T038 Implement event behavior in includes/events.php
T039 Implement feed and export behavior in includes/calendar-ics.php
```

---

## Implementation Strategy

### MVP First

1. Complete Phase 1: Setup
2. Complete Phase 2: Foundation
3. Stop and validate plugin identity, registries, roles/capabilities, user meta, and runtime table semantics
4. Open the Foundation PR before starting the next branch

### Incremental Delivery

1. Merge Foundation
2. Merge Resolver Core
3. Merge Auth / Onboarding
4. Merge Conferences / Context
5. Merge Dashboard Shell
6. Merge Announcements
7. Merge Documents
8. Merge Events / Calendar Export
9. Merge Settings / Admin / Imports
10. Merge UI Polish / Validation Docs

### PR Discipline

1. Finish the last task in the active slice
2. Run the slice’s tests and contract checks
3. Commit only the files touched by that slice
4. Push the branch
5. Open a PR and complete `.github/pull_request_template.md`
6. Merge before starting the next slice on a new branch

---

## Notes

- All tasks use explicit repository file paths.
- `[P]` marks tasks that can be executed in parallel without same-file conflicts.
- Story labels are mapped to the planning stories because the approved plan is itself the feature being decomposed.
- The suggested MVP scope is **PR Slice 1: Foundation**.
