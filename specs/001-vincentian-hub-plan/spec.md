# Feature Specification: Vincentian Hub Plugin Implementation

**Feature Branch**: `001-vincentian-hub-plan`  
**Created**: 2026-03-13  
**Status**: Draft  
**Input**: User description: "Implement the Vincentian Hub WordPress plugin using the binding contracts and architecture documents."

## User Scenarios & Testing *(mandatory)*

### User Story 1 - Protected Dashboard MVP (Priority: P1)

As an approved Vincentian Hub user, I need to reach the correct district or conference dashboard and only see content I am allowed to access.

**Why this priority**: This is the first user-visible protected portal behavior and establishes the resolver, auth, onboarding, route, and dashboard foundation for everything else.

**Independent Test**: An approved district user can access `/district-resources/district/`; an approved conference user can access `/district-resources/<conference-name>/`; pending and disabled users cannot reach protected dashboard content; visible dashboard content is filtered only by the canonical resolver.

**Acceptance Scenarios**:

1. **Given** an approved conference-scope user with a valid assigned conference, **When** they visit `/district-resources/<conference-name>/`, **Then** the route resolves by `svdp_conf_page_slug` and returns only resolver-eligible content.
2. **Given** an approved district-scope user, **When** they visit `/district-resources/district/`, **Then** they see only content matching district scope and the resolver rules.
3. **Given** a `pending` or `disabled` user, **When** they attempt to access a protected dashboard route, **Then** they are denied portal access and sent to the correct gated state.
4. **Given** a content object with empty `svdp_audience_profiles`, **When** an approved in-scope user accesses the dashboard, **Then** audience filtering is skipped but all other targeting checks still apply.

---

### User Story 2 - Protected Announcements and Documents (Priority: P2)

As an approved Vincentian Hub user, I need to access targeted announcements and protected documents without unauthorized users being able to view, preview, or download them.

**Why this priority**: Announcements and documents are core portal content systems and rely on the dashboard, resolver, and route-security foundation from User Story 1.

**Independent Test**: An approved eligible user can see targeted announcements and access allowed document detail/preview/download endpoints; ineligible, pending, or disabled users are denied.

**Acceptance Scenarios**:

1. **Given** an approved eligible user, **When** they view dashboard or announcement content, **Then** only announcements matching the shared targeting block are shown.
2. **Given** an approved eligible user, **When** they open `/resource-library/<doc-slug>/`, **Then** the document detail route is allowed only after server-side resolver checks.
3. **Given** an approved but ineligible user, **When** they request a protected document detail, preview, or download endpoint, **Then** access is denied server-side.
4. **Given** a document imported from Google Drive, **When** it exists in WordPress, **Then** WordPress remains the runtime system of record for access and delivery behavior.

---

### User Story 3 - Protected Events, Calendar Exports, and Admin Branding (Priority: P3)

As an approved Vincentian Hub user or district admin, I need protected event/calendar access and capability-gated branding/settings management.

**Why this priority**: Events and exports are separate protected-delivery surfaces, and branding/settings finalize admin functionality without changing the core security model.

**Independent Test**: Eligible users can access protected event detail and calendar exports; invalid or unauthorized requests are denied; district admins can manage branding/settings while non-admins cannot.

**Acceptance Scenarios**:

1. **Given** an approved eligible user, **When** they visit `/events/<event-slug>/`, **Then** the route enforces server-side resolver checks before event data is returned.
2. **Given** a valid eligible calendar token, **When** `/portal-calendar/feed/<token>/` is requested, **Then** only visible events are returned.
3. **Given** an invalid or rotated token, **When** a calendar feed or single-event export is requested, **Then** access is denied.
4. **Given** a district admin, **When** they manage branding settings, **Then** the `vincentian_hub_logo_attachment_id` setting is available and non-admin roles remain blocked.

### Edge Cases

- A conference-scope user exists without a valid `svdp_conference_id`
- A district-scope user has directory-table `conference_id` data that must not affect targeting
- A protected object has empty `svdp_audience_profiles`
- A shared-targeting object is district-only but has an incorrect conference-targeting mode
- A user is assigned to an inactive conference
- A protected object is inactive or outside its publish window
- A document detail route is requested directly without prior dashboard navigation
- A calendar token is invalid, rotated, or belongs to an ineligible user

## Requirements *(mandatory)*

### Functional Requirements

- **FR-001**: The system MUST preserve the canonical plugin identity defined in `specs/contracts-spec.md`.
- **FR-002**: The system MUST register the canonical CPTs `svdp_conf`, `svdp_dash_item`, `svdp_announcement`, `svdp_doc`, and `svdp_event`.
- **FR-003**: The system MUST register the canonical taxonomy `svdp_doc_cat`.
- **FR-004**: The system MUST use the runtime custom table naming convention `{$wpdb->prefix}svdp_directory` and must not hardcode `wp_`.
- **FR-005**: The system MUST register and use the required `svdp_` user meta keys from the contracts spec.
- **FR-006**: The system MUST register and use the canonical shared targeting keys without aliases.
- **FR-007**: The system MUST implement normalized user context and the targeting resolver as the only front-end visibility authority.
- **FR-008**: The system MUST preserve the canonical resolver order and targeting semantics defined in `specs/architecture/targeting-engine-rules.md`.
- **FR-009**: The system MUST use WordPress roles and capabilities for backend/admin authorization only.
- **FR-010**: The system MUST use `svdp_role_profiles` for front-end targeting only.
- **FR-011**: The system MUST implement Google auth and onboarding flows that honor `svdp_approval_status` and `svdp_account_scope`.
- **FR-012**: The system MUST resolve conference dashboard routes by `svdp_conf_page_slug`.
- **FR-013**: The system MUST register and protect the canonical routes:
  - `/district-resources/<conference-name>/`
  - `/district-resources/district/`
  - `/resource-library/<doc-slug>/`
  - `/events/<event-slug>/`
  - `/portal-calendar/feed/<token>/`
  - `/portal-calendar/event/<event_id>/download/`
- **FR-014**: The system MUST enforce server-side permission checks before returning protected dashboard, document, event, feed, or export content.
- **FR-015**: The system MUST keep templates as presentation-only and must not implement query, resolver, or authorization logic in templates.
- **FR-016**: The system MUST keep WordPress as the system of record and treat Google Drive as an upstream document source only.
- **FR-017**: The system MUST implement the branding setting key `vincentian_hub_logo_attachment_id` with district-admin-only management.

### Non-Functional Requirements

- **NFR-001**: Protected routes must enforce visibility server-side before any protected object data is returned to the client.
- **NFR-002**: No canonical field names, route patterns, enums, or capability meanings may drift without explicit contract versioning.
- **NFR-003**: Resolver evaluation must reuse normalized user context and must not perform ad hoc conference-meta reads during evaluation.
- **NFR-004**: The implementation must include test coverage for resolver behavior, capability boundaries, route security, and calendar export visibility before those slices merge.
- **NFR-005**: The UI must use the current WordPress theme baseline and remain mobile-first.
- **NFR-006**: The implementation must preserve the separation between backend/admin authorization and front-end visibility targeting.

### Key Entities *(include if feature involves data)*

- **Conference**: The `svdp_conf` CPT record that controls conference identity, routing, linked-page mapping, and derived conference flags.
- **User Access State**: WordPress user plus required `svdp_` user meta for approval, scope, conference assignment, role profiles, and calendar token state.
- **Normalized User Context**: The canonical runtime structure used by the resolver for front-end visibility decisions.
- **Shared Targeting Block**: The canonical targeting meta keys reused across dashboard items, announcements, documents, and events.
- **Dashboard Item**: The `svdp_dash_item` CPT representing tools, links, references, or shortcode-backed dashboard utilities.
- **Announcement**: The `svdp_announcement` CPT representing targeted volunteer or district communications.
- **Document**: The `svdp_doc` CPT representing protected resource-library content.
- **Event**: The `svdp_event` CPT representing protected event detail and exportable calendar content.
- **Trusted Directory Entry**: The runtime table entry used for bootstrap identity/default data before WordPress user state becomes authoritative.
- **Branding Setting**: The settings record containing `vincentian_hub_logo_attachment_id`.

## Success Criteria *(mandatory)*

### Measurable Outcomes

- **SC-001**: Approved users can reach the correct conference or district dashboard and only see resolver-eligible content.
- **SC-002**: Unauthorized, pending, disabled, or ineligible users cannot access protected dashboard, document, event, feed, or export endpoints.
- **SC-003**: Canonical CPT names, taxonomy names, user meta keys, shared targeting keys, route patterns, and enums remain unchanged from the contracts spec.
- **SC-004**: Resolver, capability, route-security, and calendar export tests exist for each completed slice before merge.
- **SC-005**: Branding/settings access is limited to the correct admin capability boundary.
