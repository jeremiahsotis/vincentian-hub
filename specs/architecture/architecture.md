# Vincentian Hub Architecture

This document defines the implementation structure of Vincentian Hub.

## Binding Architecture Companions

The following documents are binding anti-drift companions:

- `/specs/contracts-spec.md`
- `/specs/architecture/targeting-engine-rules.md`
- `/specs/architecture/wordpress-data-model-map.md`
- `/specs/architecture/system-diagram.md`

Developers must consult these before changing:
- CPT structures
- meta registration
- user meta handling
- resolver inputs
- object relationships
- route/security behavior

## Core Principle

WordPress is the system of record.

Google Drive is an upstream document source only.

## Authorization Model

### Backend/Admin
Backend/admin authorization uses:
- WordPress roles
- capabilities
- admin menu access rules

### Front-end/Gated Content
Front-end visibility uses:
- normalized user context
- targeting resolver

These boundaries must not be mixed.

## Layers

### 1. Authentication Layer
Google OAuth sign-in and onboarding

### 2. User Context Layer
Approved user state, account scope, conference assignment, role profiles, conference flags

### 3. Targeting Engine
Central visibility resolver

### 4. Content Layer
Custom Post Types:
- Dashboard Items
- Announcements
- Documents
- Events

### 5. Presentation Layer
Dashboard rendering, detail pages, mobile-first current-theme-based UI

### 6. Integration Layer
Google Drive imports, ICS feeds, external links

## Canonical Route Ownership

The canonical implementation owner for front-end routes and endpoint wiring is:

- `includes/routes.php`

This file is responsible for:
- conference dashboard route interception
- district dashboard route handling
- document detail route registration
- event detail route registration
- calendar feed endpoints
- single-event export endpoints

## Route implementation rule

Route registration may delegate to object-specific modules, but route entry points must be declared from the routing layer and must enforce server-side permission checks before protected content is returned.

## Canonical Module Ownership

The `includes/` directory is the canonical implementation layer for application logic.

Each module has a primary ownership boundary:

- `bootstrap.php`
  plugin bootstrap and module loading

- `vincentian-hub.php`
  canonical plugin entrypoint, activation/deactivation hook registration, and bootstrap handoff

### Bootstrap / Activation Rule

Activation/deactivation responsibilities must not be distributed arbitrarily across feature modules.

The canonical plugin entrypoint and bootstrap layer together own:
- plugin load handoff
- activation hook registration
- deactivation hook registration
- early bootstrap sequencing

- `roles.php`
  WordPress role registration

- `capabilities.php`
  capability registration and mapping

- `post-types.php`
  CPT registration

- `taxonomies.php`
  taxonomy registration

- `meta-registration.php`
  canonical meta key registration, validation hooks, and registration-related schema enforcement

- `user-meta.php`
  user meta registration and normalization helpers specific to user-meta handling

- `directory-table.php`
  trusted directory custom table creation and access helpers

- `auth-google.php`
  Google OAuth authentication flow

- `onboarding.php`
  post-auth onboarding and conference selection flow

- `conferences.php`
  conference model, conference lookup, linked page mapping, and conference flag derivation

- `targeting-resolver.php`
  normalized user context + targeting resolver logic

- `dashboard-query.php`
  gathering visible dashboard data sets

- `dashboard-renderer.php`
  dashboard section composition and rendering orchestration

- `announcements.php`
  announcement object behavior and rendering helpers specific to announcements

- `documents.php`
  document detail, preview, download, and protected delivery behavior

- `events.php`
  event detail behavior, event queries, and event-specific relationships

- `calendar-ics.php`
  ICS feed generation, single-event ICS output, and add-to-calendar support

- `routes.php`
  front-end route registration, endpoint mapping, and route-level permission entry points

- `permissions.php`
  shared permission helpers that are not the resolver itself

- `shortcode-context.php`
  conference-context injection for shortcode-backed tools

- `settings.php`
  plugin settings, branding settings, and settings screen behavior

- `admin-menu.php`
  admin menu registration and menu access wiring

- `drive-imports.php`
  Google Drive import/sync intake behavior for documents

  ## Template Ownership Boundary

The `templates/` directory is the canonical presentation layer for portal-facing PHP templates.

Expected template ownership:

- `templates/dashboard-conference.php`
- `templates/dashboard-district.php`
- `templates/document-detail.php`
- `templates/event-detail.php`
- `templates/login.php`
- `templates/onboarding.php`
- `templates/pending-access.php`

Rules:
- templates render provided data
- templates do not become alternate query layers
- templates do not become alternate resolver layers
- templates do not contain standalone authorization logic beyond receiving already-authorized context

  ## Dashboard Query / Render Separation

The dashboard implementation must preserve this separation:

- `includes/dashboard-query.php`
  responsible for assembling visible datasets

- `includes/dashboard-renderer.php`
  responsible for composing sections and rendering output

Rules:
- query code must not become the rendering layer
- rendering code must not independently rebuild visibility logic
- both must rely on the targeting resolver for visibility decisions

## Anti-drift rule

Codex or developers must not freely redistribute these responsibilities across random files without explicit architectural review.

Refactors across module boundaries are architecture changes, not convenience edits.

## Security Model

Server-side permission checks are required for:
- dashboard content queries
- document preview
- document download
- event detail pages
- calendar feeds
- export endpoints

Client-side filtering is not sufficient.

## Minimum Required Test Ownership

The `tests/` layer must at minimum cover:

### Resolver / Context
- normalized user context creation
- resolver order
- scope matching
- audience matching
- conference targeting
- group-flag targeting

### Route / Security
- document detail access
- document preview access
- document download access
- event detail access
- calendar feed access
- export endpoint access

### Capability Boundaries
- district announcements editor permissions
- district editor permissions
- district admin permissions

These tests may be organized however the implementation chooses, but these coverage areas are mandatory.

## Canonical Document Delivery Ownership

The canonical implementation owner for protected document behavior is:

- `includes/documents.php`

This file is responsible for:
- document detail behavior
- preview behavior
- download behavior
- source-aware delivery behavior
- enforcing permission checks before protected file access

Document delivery must not bypass this module through ad hoc template logic.

## Canonical Event and Calendar Ownership

The canonical implementation owners are:

- `includes/events.php`
  event detail behavior, event relationships, and event-specific queries

- `includes/calendar-ics.php`
  personalized ICS feeds, single-event ICS output, and add-to-calendar URL generation

Event export and feed behavior must not be implemented ad hoc in templates or route callbacks.

## Theme Integration Constraint

The portal must adopt the basics of the current WordPress theme.

Design effort should focus on:
- mobile-first layout
- readable typography
- spacing
- large tap targets
- calm predictable hierarchy

## Header Logo Constraint

A rectangular header logo area must be reserved and support district-admin upload behavior as defined in the contracts specification.
