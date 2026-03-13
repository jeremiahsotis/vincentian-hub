# Vincentian Hub Targeting Engine Rules

This document is binding and defines the single visibility model for Vincentian Hub.

## 1. Resolver Authority

The targeting resolver is the single visibility authority for:
- dashboards
- dashboard items
- announcements
- documents
- events
- front-end detail pages
- downloads
- feeds
- export endpoints

It is not the authorization mechanism for WordPress admin CRUD screens.

### Canonical Implementation Location

The canonical implementation owner for normalized user context and resolver logic is:

- `includes/targeting-resolver.php`

Related support may exist elsewhere, but the authoritative implementation must live there.

This file owns:
- normalized user context creation
- object targeting resolution
- allow/deny evaluation
- reusable resolver helpers

No other module may become an alternate resolver implementation.

### Resolver Enforcement Scope

The resolver must be enforced on **all entry points that expose protected objects**, including:

- list queries
- detail pages
- document preview
- document download
- event detail routes
- calendar feeds
- export endpoints
- shortcode-rendered content
- AJAX endpoints returning protected objects

Resolver enforcement must occur **before object data is returned to the client**.

Checking visibility only at list-query time is insufficient.  
Detail routes must independently enforce the resolver.

## 2. Canonical Shared Targeting Keys

- `svdp_scope`
- `svdp_audience_profiles`
- `svdp_target_conference_mode`
- `svdp_target_conference_ids`
- `svdp_target_group_flags`
- `svdp_is_active`
- `svdp_publish_start`
- `svdp_publish_end`

These keys are the resolver interface.

## 3. Allowed `svdp_role_profiles`

### Conference
- `member`
- `president`
- `executive_leadership`
- `spiritual_advisor`

### District
- `assistance_line`
- `district_staff`
- `district_announcements_editor`
- `district_editor`
- `district_admin`

## 3A. Empty Audience Behavior

If `svdp_audience_profiles` is empty or not defined on an object, audience filtering is skipped.

This does **not** make the object public.

The following resolver checks must still apply:

- user existence
- approval status
- object active state
- publish window
- scope match
- conference/district targeting match

An empty audience therefore means:

> All role profiles are eligible **within the remaining valid targeting constraints**.

This allows content intended for all volunteers or all district users without enumerating every profile.

## 4. Scope Values

- `conference`
- `district`
- `both`

## 5. Conference Targeting Modes

- `all`
- `selected`
- `group_flags`
- `district_only`
- `none`

### Clarification: district-only objects

`svdp_scope` and `svdp_target_conference_mode` operate at different layers of the resolver.

- `svdp_scope` controls whether an object is eligible for conference users, district users, or both.
- `svdp_target_conference_mode` controls how conference targeting is evaluated.

For district-only shared-targeting objects in MVP, the canonical configuration is:

- `svdp_scope = district`
- `svdp_target_conference_mode = district_only`

This pairing ensures that:

- conference users are excluded by scope
- the shared targeting interface remains explicit and consistent

Developers should not rely on `svdp_scope = district` alone when the object participates in the shared targeting schema.

### Clarification: `svdp_target_conference_mode = none`

`none` means that no conference-specific filtering is applied.

When `svdp_target_conference_mode = none`, visibility is determined only by:

- `svdp_scope`
- `svdp_audience_profiles`
- `svdp_is_active`
- `svdp_publish_start`
- `svdp_publish_end`

Additional rules:

- `none` does not bypass approval checks, scope checks, audience intersection, active state, or publish window validation.
- `none` does not make the object public.
- `none` simply means the conference-targeting layer is not used.

For district-only shared-targeting objects, the canonical configuration remains:

- `svdp_scope = district`
- `svdp_target_conference_mode = district_only`

### Clarification: `district_only` vs `svdp_scope = district`

These two controls operate at different layers.

#### `svdp_scope = district`
Use this to declare that an object is for district-scope users only.

#### `svdp_target_conference_mode = district_only`
Use this only when the object participates in the shared targeting interface and you need the conference-targeting layer to explicitly resolve as district-only.

### Practical rule

For district-only content:

- `svdp_scope = district` is required
- `svdp_target_conference_mode = district_only` is the canonical paired value for shared-targeting objects

This pairing avoids ambiguity and keeps shared-targeting behavior explicit.

### Anti-drift rule

Do not use:
- `svdp_scope = both` with `district_only`
unless a future version explicitly defines that behavior.

For MVP, district-only objects should use:

- `svdp_scope = district`
- `svdp_target_conference_mode = district_only`

## 6. Group Flags

- `urban`
- `rural`
- `allen_county`
- `new_haven`

## 7. Resolver Order

1. user exists
2. user approved
3. object active
4. publish window
5. scope match
6. audience intersection
7. conference/district targeting match

This ordering is canonical.

## 8. Normalized User Context Schema

The normalized user context schema is contract-locked to these fields:

- `user_id`
- `approval_status`
- `account_scope`
- `conference_id`
- `role_profiles`
- `conference_flags`
- `calendar_feed_token`

This normalized user context must be reused everywhere visibility is evaluated.

## 9. Authorization Boundary

- Front-end visibility uses normalized user context + targeting resolver
- Backend/admin authorization uses WordPress roles + capabilities
- Do not use capabilities as a substitute for front-end targeting
- Do not use the targeting resolver as a substitute for admin CRUD authorization

## 10. Anti-Drift Rule

No feature may:
- rename targeting keys
- alias targeting keys
- partially reimplement resolver checks
- perform access checks only in templates, CSS, or JavaScript
